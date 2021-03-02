<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailKehadiran extends Model
{
    protected $table = "detail_kehadiran";
    protected $primaryKey = "id_detail_kehadiran";

    public $guarded = [];

    public function header()
    {
        return $this->belongsTo(HeaderKehadiran::class, 'id_header_kehadiran');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nrp');
    }
}
