<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemen';

    protected $fillable = [
        'nama_departemen',
    ];
    public $timestamps = false;
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
}
