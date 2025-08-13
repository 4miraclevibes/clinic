<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function index()
    {
        $queues = Queue::with(['patient', 'doctor', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $patients = Patient::all();
        $doctors = Doctor::all();

        return view('pages.registrations.index', compact('queues', 'patients', 'doctors'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctors,id',
                'keterangan' => 'nullable|string',
            ]);

            // Cek apakah dokter memiliki jadwal hari ini
            $todaySchedule = DoctorSchedule::where('doctor_id', $request->doctor_id)
                ->where('date', Carbon::today())
                ->where('status', 'available')
                ->first();

            if (!$todaySchedule) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['doctor_id' => 'Dokter tidak memiliki jadwal hari ini. Silakan pilih dokter lain atau buat jadwal terlebih dahulu.']);
            }

            // Cek apakah pasien sudah ada dalam antrian hari ini
            $existingQueue = Queue::where('patient_id', $request->patient_id)
                ->whereDate('created_at', Carbon::today())
                ->first();

            if ($existingQueue) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['patient_id' => 'Pasien sudah terdaftar dalam antrian hari ini. Satu pasien hanya boleh mendaftar sekali per hari.']);
            }

            // Cek apakah dokter sudah terlalu banyak antrian hari ini (opsional)
            $doctorTodayQueues = Queue::where('doctor_id', $request->doctor_id)
                ->whereDate('created_at', Carbon::today())
                ->count();

            if ($doctorTodayQueues >= 20) { // Batas maksimal 20 antrian per dokter per hari
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['doctor_id' => 'Dokter sudah mencapai batas maksimal antrian hari ini (20 pasien). Silakan pilih dokter lain atau daftar besok.']);
            }

            // Generate nomor antrian
            $todayQueues = Queue::whereDate('created_at', Carbon::today())->count();
            $noAntrian = $todayQueues + 1;

            Queue::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'user_id' => Auth::user()->id,
                'no_antrian' => $noAntrian,
                'status' => 'pending',
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('registrations.index')->with('success', 'Pendaftaran berhasil ditambahkan dengan nomor antrian #' . $noAntrian);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $queue = Queue::findOrFail($id);

            $request->validate([
                'status' => 'required|in:pending,in_progress,completed,cancelled',
                'keterangan' => 'nullable|string',
            ]);

            $queue->update($request->all());

            return redirect()->route('registrations.index')->with('success', 'Status antrian berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Terjadi kesalahan saat memperbarui status antrian. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $queue = Queue::findOrFail($id);
            $queue->delete();

            return redirect()->route('registrations.index')->with('success', 'Antrian berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => 'Terjadi kesalahan saat menghapus antrian. Silakan coba lagi.']);
        }
    }

    public function show($id)
    {
        try {
            $queue = Queue::with(['patient', 'doctor', 'user', 'medicalRecords'])->findOrFail($id);
            return view('pages.registrations.show', compact('queue'));

        } catch (\Exception $e) {
            return redirect()->route('registrations.index')->withErrors(['general' => 'Data antrian tidak ditemukan.']);
        }
    }

    public function getDoctorsByDate(Request $request)
    {
        try {
            $date = $request->date;
            $doctors = DoctorSchedule::where('date', $date)
                ->where('status', 'available')
                ->with('doctor')
                ->get()
                ->pluck('doctor');

            return response()->json($doctors);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data dokter'], 500);
        }
    }
}
