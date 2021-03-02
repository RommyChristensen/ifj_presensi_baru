<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_jurusan');
    }
}
