<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    protected $table = "mahasiswa";
    protected $primaryKey = "nrp";
    public $incrementing = false;

    public $guarded = [];

    public function jurusan()
    {
        return $this->hasOne(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function detail()
    {
        return $this->hasMany(DetailKehadiran::class, 'nrp');
    }
}
