<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    public function index()
    {
        $data = Departemen::orderBy('nama_departemen','asc')->get();
        return response()->json([
            'status'=>true,
            'message'=>'Data Ditemukan',
            'data'=>$data
        ],200);
    }

    // Menyimpan departemen baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_departemen' => 'required|string|max:100',
        ]);

        $departemen = Departemen::create($request->all());
        return response()->json($departemen, 201);
    }

    // Menampilkan detail departemen berdasarkan ID
    public function show($id)
    {
        $departemen = Departemen::find($id);
        if (!$departemen) {
            return response()->json(['message' => 'Departemen tidak ditemukan'], 404);
        }
        return response()->json($departemen);
    }

    // Mengupdate departemen
    public function update(Request $request, $id)
    {
        $departemen = Departemen::find($id);
        if (!$departemen) {
            return response()->json(['message' => 'Departemen tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_departemen' => 'string|max:100',
        ]);

        $departemen->update($request->all());
        return response()->json($departemen);
    }

    // Menghapus departemen
    public function destroy($id)
    {
        $departemen = Departemen::find($id);
        if (!$departemen) {
            return response()->json(['message' => 'Departemen tidak ditemukan'], 404);
        }

        $departemen->delete();
        return response()->json(['message' => 'Departemen berhasil dihapus']);
    }
}
