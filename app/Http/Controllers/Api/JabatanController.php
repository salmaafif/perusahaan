<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $data = Jabatan::orderBy('nama_jabatan','asc')->get();
        return response()->json([
            'status'=>true,
            'message'=>'Data Ditemukan',
            'data'=>$data
        ],200);
    }

    public function store(Request $request)
    {
        $dataKaryawan = new Jabatan();

        $request->validate([
            'nama_jabatan' => 'required|string',
        ]);

        // Menyimpan data karyawan baru ke database
        $karyawan = Jabatan::create($request->all());

        $post = $dataKaryawan->save();
        return response()->json([
            'status'=>true,
            'message'=>'Data Berhasil Ditambahkan',
        ]);
    }

    public function show(string $id)
    {
        $data = Jabatan::find($id);
        if ($data) {
            return response()->json([
                'status'=>true,
                'message'=>'Data Ditemukan',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'Data Tidak Ditemukan'
            ]);
        }
    }

    public function update(Request $request, $id)
{
    // Cari karyawan berdasarkan id
    $karyawan = Jabatan::find($id);

    if (!$karyawan) {
        return response()->json(['message' => 'Karyawan tidak ditemukan'], 404);
    }

    // Validasi hanya kolom yang dikirimkan
    $request->validate([
        'nama_jabatan' => 'nullable|string|max:100',
    ]);

    // Mengupdate hanya kolom yang dikirim dalam request
    $karyawan->update($request->only([
        'nama_jabatan'
    ]));

    return response()->json($karyawan);
}


    public function destroy(string $id)
    {
        $dataBuku = Jabatan::find($id);
        if (empty($dataBuku)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ],404);
        }
        $post = $dataBuku->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Data Berhasil Delete',
        ]);
    }
}
