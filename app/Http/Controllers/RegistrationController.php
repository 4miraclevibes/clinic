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
            return redirect()->back()->withErrors(['Dokter tidak memiliki jadwal hari ini']);
        }

        // Cek apakah pasien sudah ada dalam antrian hari ini
        $existingQueue = Queue::where('patient_id', $request->patient_id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($existingQueue) {
            return redirect()->back()->withErrors(['Pasien sudah terdaftar dalam antrian hari ini']);
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

        return redirect()->route('registrations.index')->with('success', 'Pendaftaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $queue = Queue::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'keterangan' => 'nullable|string',
        ]);

        $queue->update($request->all());

        return redirect()->route('registrations.index')->with('success', 'Status antrian berhasil diperbarui');
    }

    public function destroy($id)
    {
        $queue = Queue::findOrFail($id);
        $queue->delete();

        return redirect()->route('registrations.index')->with('success', 'Antrian berhasil dihapus');
    }

    public function show($id)
    {
        $queue = Queue::with(['patient', 'doctor', 'user', 'medicalRecords'])->findOrFail($id);
        return view('pages.registrations.show', compact('queue'));
    }

    public function getDoctorsByDate(Request $request)
    {
        $date = $request->date;
        $doctors = DoctorSchedule::where('date', $date)
            ->where('status', 'available')
            ->with('doctor')
            ->get()
            ->pluck('doctor');

        return response()->json($doctors);
    }
}
