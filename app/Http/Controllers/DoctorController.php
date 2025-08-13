<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::orderBy('created_at', 'desc')->get();
        return view('pages.doctors.index', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'spesialis' => 'required|string|max:255',
            'no_str' => 'required|string|max:50|unique:doctors',
            'no_sip' => 'required|string|max:50|unique:doctors',
            'no_spesialis' => 'required|string|max:50|unique:doctors',
        ]);

        Doctor::create($request->all());

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'spesialis' => 'required|string|max:255',
            'no_str' => 'required|string|max:50|unique:doctors,no_str,' . $id,
            'no_sip' => 'required|string|max:50|unique:doctors,no_sip,' . $id,
            'no_spesialis' => 'required|string|max:50|unique:doctors,no_spesialis,' . $id,
        ]);

        $doctor->update($request->all());

        return redirect()->route('doctors.index')->with('success', 'Data dokter berhasil diperbarui');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil dihapus');
    }

    public function show($id)
    {
        $doctor = Doctor::with(['doctorSchedules', 'queues.patient', 'transactions'])->findOrFail($id);
        return view('pages.doctors.show', compact('doctor'));
    }
}
