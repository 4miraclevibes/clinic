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
                'tanggal' => 'required|date|after_or_equal:today',
                'keterangan' => 'nullable|string',
            ]);

            $selectedDate = Carbon::parse($request->tanggal);

            // Cek apakah dokter memiliki jadwal pada tanggal yang dipilih
            $schedule = DoctorSchedule::where('doctor_id', $request->doctor_id)
                ->where('date', $selectedDate->format('Y-m-d'))
                ->where('status', 'available')
                ->first();

            // KODE INI DAPAT DIUNCOMMENT JIKA INGIN MEMAKSA PENGECEKAN JADWAL DOKTER
            // if (!$schedule) {
            //     return redirect()->back()
            //         ->withInput()
            //         ->withErrors(['doctor_id' => 'Dokter tidak memiliki jadwal pada tanggal ' . $selectedDate->format('d/m/Y') . '. Silakan pilih dokter lain atau buat jadwal terlebih dahulu.']);
            // }

            // Cek apakah pasien sudah ada dalam antrian pada tanggal yang dipilih
            $existingQueue = Queue::where('patient_id', $request->patient_id)
                ->whereDate('created_at', $selectedDate->format('Y-m-d'))
                ->first();

            if ($existingQueue) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['patient_id' => 'Pasien sudah terdaftar dalam antrian pada tanggal ' . $selectedDate->format('d/m/Y') . '. Satu pasien hanya boleh mendaftar sekali per hari.']);
            }

            // Cek apakah dokter sudah terlalu banyak antrian pada tanggal yang dipilih (opsional)
            $doctorDateQueues = Queue::where('doctor_id', $request->doctor_id)
                ->whereDate('created_at', $selectedDate->format('Y-m-d'))
                ->count();

            if ($doctorDateQueues >= 20) { // Batas maksimal 20 antrian per dokter per hari
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['doctor_id' => 'Dokter sudah mencapai batas maksimal antrian pada tanggal ' . $selectedDate->format('d/m/Y') . ' (20 pasien). Silakan pilih dokter lain atau tanggal lain.']);
            }

            // Generate nomor antrian untuk tanggal yang dipilih
            $dateQueues = Queue::whereDate('created_at', $selectedDate->format('Y-m-d'))->count();
            $noAntrian = $dateQueues + 1;

            Queue::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'user_id' => Auth::user()->id,
                'no_antrian' => $noAntrian,
                'status' => 'pending',
                'keterangan' => $request->keterangan,
                'created_at' => $selectedDate,
            ]);

            return redirect()->route('registrations.index')->with('success', 'Pendaftaran berhasil ditambahkan dengan nomor antrian #' . $noAntrian . ' untuk tanggal ' . $selectedDate->format('d/m/Y'));

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
