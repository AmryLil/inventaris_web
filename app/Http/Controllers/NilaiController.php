<?php

namespace App\Http\Controllers;

use App\Models\Input;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NilaiController extends Controller
{
  public function index()
  {
    return view('admin.inputnilai');
  }

  public function index2()
  {
    $input = Input::all();
    return view('admin.nilaihasil', compact('input'));
  }

  public function storeOrUpdate(Request $request)
  {
    $request->validate([
      'nim'         => 'required|string',
      'jenis_nilai' => 'required|string|in:Kehadiran,T1,T2,T3,T4,T5,T6,R1,R2,R3,R4,R5',
      'nilai'       => 'required|integer|min:0|max:100',
    ]);

    $nim         = $request->input('nim');
    $jenis_nilai = strtolower($request->input('jenis_nilai'));  // Kehadiran, T1-T6, R1-R5
    $nilai_input = $request->input('nilai');

    // Cari atau buat data berdasarkan NIM
    $nilai = Input::firstOrCreate(['nim' => $nim]);

    // Update nilai sesuai jenis dropdown
    if ($jenis_nilai === 'kehadiran') {
      $nilai->nilai_hadir = $nilai_input;
    } elseif (in_array($jenis_nilai, ['t1', 't2', 't3', 't4', 't5', 't6'])) {
      // Simpan nilai tugas sesuai dengan kolom 'nilai_tugas'
      $nilai->nilai_tugas = $nilai_input;
    } elseif (in_array($jenis_nilai, ['r1', 'r2', 'r3', 'r4', 'r5'])) {
      // Simpan nilai respon sesuai dengan kolom 'nilai_respon'
      $nilai->nilai_project = $nilai_input;  // Project mewakili nilai respon.
    }

    // Hitung total berdasarkan bobot
    $total        = (
      ($nilai->nilai_hadir ?? 0) * 0.2
      + ($nilai->nilai_tugas ?? 0) * 0.3
      + ($nilai->nilai_project ?? 0) * 0.5
    );
    $nilai->total = $total;

    // Tentukan nilai huruf
    if ($total >= 80) {
      $nilai->huruf = 'A';
    } elseif ($total >= 70) {
      $nilai->huruf = 'B';
    } elseif ($total >= 60) {
      $nilai->huruf = 'C';
    } elseif ($total >= 50) {
      $nilai->huruf = 'D';
    } else {
      $nilai->huruf = 'E';
    }

    // Simpan perubahan
    $nilai->save();

    return redirect()->route('hasil')->with('success', 'Nilai berhasil disimpan.');
  }
}
