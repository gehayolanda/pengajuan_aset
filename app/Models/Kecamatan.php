<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = "kecamatan";
    protected $fillable = [
        "nama_kecamatan",
        "kabupaten_id",
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function sekolah()
    {
        return $this->hasMany(Sekolah::class, 'kecamatan_id');
    }

}
