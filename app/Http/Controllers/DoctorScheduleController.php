<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $schedules = DoctorSchedule::with('doctor')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        $doctors = Doctor::all();

        return view('pages.schedules.index', compact('schedules', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:available,unavailable',
        ]);

        // Cek apakah sudah ada jadwal untuk dokter di tanggal dan waktu yang sama
        $existingSchedule = DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->first();

        if ($existingSchedule) {
            return redirect()->back()->withErrors(['Jadwal sudah ada untuk waktu tersebut']);
        }

        DoctorSchedule::create($request->all());

        return redirect()->route('schedules.index')->with('success', 'Jadwal dokter berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $schedule = DoctorSchedule::findOrFail($id);

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:available,unavailable',
        ]);

        // Cek konflik jadwal kecuali jadwal yang sedang diedit
        $existingSchedule = DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where('id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->first();

        if ($existingSchedule) {
            return redirect()->back()->withErrors(['Jadwal sudah ada untuk waktu tersebut']);
        }

        $schedule->update($request->all());

        return redirect()->route('schedules.index')->with('success', 'Jadwal dokter berhasil diperbarui');
    }

    public function destroy($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Jadwal dokter berhasil dihapus');
    }
}
