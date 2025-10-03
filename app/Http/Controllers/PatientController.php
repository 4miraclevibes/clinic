<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        if(Auth::user()->userDetails->role == 'admin'){
            $patients = Patient::orderBy('created_at', 'desc')->get();
        } else {
            $patients = Patient::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        }

        return view('pages.patients.index', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_rekam_medis' => 'required|string|max:50|unique:patients',
            'nik' => 'required|string|max:16|unique:patients',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        Patient::create($data);

        return redirect()->route('patients.index')->with('success', 'Pasien berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'no_rekam_medis' => 'required|string|max:50|unique:patients,no_rekam_medis,' . $id,
            'nik' => 'required|string|max:16|unique:patients,nik,' . $id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil diperbarui');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Pasien berhasil dihapus');
    }

    public function show($id)
    {
        $patient = Patient::with(['queues.doctor', 'queues.medicalRecords.doctor'])->findOrFail($id);
        return view('pages.patients.show', compact('patient'));
    }
}
