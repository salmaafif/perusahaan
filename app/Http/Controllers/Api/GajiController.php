<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    public function index()
    {
        $data = Gaji::orderBy('karyawan_id','asc')->get();
        return response()->json([
            'status'=>true,
            'message'=>'Data Ditemukan',
            'data'=>$data
        ],200);
    }

    // Menyimpan gaji baru
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'bulan' => 'required|string|max:10',
            'gaji_pokok' => 'required|numeric',
            'tunjangan' => 'required|numeric',
            'potongan' => 'required|numeric',
            'total_gaji' => 'required|numeric',
        ]);

        $gaji = Gaji::create($request->all());
        return response()->json($gaji, 201);
    }

    // Menampilkan detail gaji berdasarkan ID
    public function show($id)
    {
        $gaji = Gaji::find($id);
        if (!$gaji) {
            return response()->json(['message' => 'Gaji tidak ditemukan'], 404);
        }
        return response()->json($gaji);
    }

    // Mengupdate gaji
    public function update(Request $request, $id)
    {
        $gaji = Gaji::find($id);
        if (!$gaji) {
            return response()->json(['message' => 'Gaji tidak ditemukan'], 404);
        }

        $request->validate([
            'karyawan_id' => 'exists:karyawan,id',
            'bulan' => 'string|max:10',
            'gaji_pokok' => 'numeric',
            'tunjangan' => 'numeric',
            'potongan' => 'numeric',
            'total_gaji' => 'numeric',
        ]);

        $gaji->update($request->all());
        return response()->json($gaji);
    }

    // Menghapus gaji
    public function destroy($id)
    {
        $gaji = Gaji::find($id);
        if (!$gaji) {
            return response()->json(['message' => 'Gaji tidak ditemukan'], 404);
        }

        $gaji->delete();
        return response()->json(['message' => 'Gaji berhasil dihapus']);
    }
}
