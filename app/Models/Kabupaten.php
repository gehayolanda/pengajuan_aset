<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = "kabupaten";
    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class);
    }

    public function sekolah()
    {
        return $this->hasMany(Sekolah::class, 'kabupaten_id');
    }
}
