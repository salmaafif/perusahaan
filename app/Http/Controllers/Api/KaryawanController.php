<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    public function index()
    {
        $data = Karyawan::orderBy('tanggal_masuk', 'asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $dataKaryawan = new Karyawan;

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:karyawan,email',
            'nomor_telepon' => 'string|max:15',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'tanggal_masuk' => 'required|date',
            'departemen_id' => 'required|exists:departemen,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Menyimpan data karyawan baru ke database
        $karyawan = Karyawan::create($request->all());

        $post = $dataKaryawan->save();
        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Ditambahkan',
        ]);
    }

    public function show(string $id)
    {
        $data = Karyawan::find($id);
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data Ditemukan',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        // Cari karyawan berdasarkan id
        $karyawan = Karyawan::find($id);

        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan tidak ditemukan'], 404);
        }

        // Validasi hanya kolom yang dikirimkan
        $request->validate([
            'nama_lengkap' => 'nullable|string|max:100',  // Nullable untuk memperbolehkan field kosong
            'email' => 'nullable|email|unique:karyawan,email,' . $id,  // Email harus unik kecuali untuk karyawan ini
            'nomor_telepon' => 'nullable|string|max:15',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'tanggal_masuk' => 'nullable|date',
            'departemen_id' => 'nullable|exists:departemen,id',
            'jabatan_id' => 'nullable|exists:jabatan,id',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        // Mengupdate hanya kolom yang dikirim dalam request
        $karyawan->update($request->only([
            'nama_lengkap',
            'email',
            'nomor_telepon',
            'tanggal_lahir',
            'alamat',
            'tanggal_masuk',
            'departemen_id',
            'jabatan_id',
            'status'
        ]));

        return response()->json($karyawan);
    }


    public function destroy(string $id)
    {
        $dataBuku = Karyawan::find($id);
        if (empty($dataBuku)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }
        $post = $dataBuku->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Delete',
        ]);
    }
    public function showByName($name)
    {
        $karyawan = Karyawan::where('nama_lengkap', 'like', '%' . $name . '%')->get();
        return response()->json($karyawan);
    }
}
