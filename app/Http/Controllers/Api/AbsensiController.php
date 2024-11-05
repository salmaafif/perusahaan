<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $data = Absensi::orderBy('tanggal','asc')->get();
        return response()->json([
            'status'=>true,
            'message'=>'Data Ditemukan',
            'data'=>$data
        ],200);
    }

    // Menyimpan absensi baru
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'karyawan_id' => 'required|integer',
                'tanggal' => 'required|date',
                'waktu_masuk' => 'nullable',
                'waktu_keluar' => 'nullable',
                'status_absensi' => 'nullable',
            ]);

            if (!empty($validatedData['waktu_masuk'])) {
                $validatedData['status_absensi'] = 'hadir';
            }else{
                $validatedData['status_absensi'] = 'alpha';
            }

            // Cek apakah karyawan sudah absen pada tanggal tersebut
            $absenExist = Absensi::where('karyawan_id', $request->karyawan_id)
                                  ->where('tanggal', $request->tanggal)
                                  ->first();

            if ($absenExist) {
                // Jika sudah ada absensi pada tanggal tersebut, karyawan tidak boleh absen lagi pada hari yang sama
                return response()->json([
                    'message' => 'Karyawan sudah absen pada tanggal ini'
                ], 400);
            }

            // Menyimpan data absensi baru
            $absensi = Absensi::create($validatedData);

            return response()->json([
                'message' => 'Absensi berhasil ditambahkan',
                'data' => $absensi
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menangani error validasi
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Menangani error lainnya
            return response()->json([
                'message' => 'Terjadi kesalahan saat menambahkan absensi',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    // Menampilkan detail absensi berdasarkan ID
    public function show($id)
    {
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json(['message' => 'Absensi tidak ditemukan'], 404);
        }
        return response()->json($absensi);
    }

    // Mengupdate absensi
    public function update(Request $request, $id)
    {
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json(['message' => 'Absensi tidak ditemukan'], 404);
        }

        $request->validate([
            'waktu_keluar' => 'nullable',
        ]);

        // Pastikan tidak ada waktu keluar yang sudah ada
        if ($absensi->waktu_keluar) {
            return response()->json([
                'message' => 'Waktu keluar sudah terisi sebelumnya.'
            ], 400);
        }

        // Update waktu_keluar
        $absensi->update(['waktu_keluar' => $request->waktu_keluar, 'updated_at' => now()]);

        return response()->json([
            'message' => 'Waktu keluar berhasil diperbarui',
            'data' => $absensi
        ]);

    }

    // Menghapus absensi
    public function destroy($id)
    {
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json(['message' => 'Absensi tidak ditemukan'], 404);
        }

        $absensi->delete();
        return response()->json(['message' => 'Absensi berhasil dihapus']);
    }
}
