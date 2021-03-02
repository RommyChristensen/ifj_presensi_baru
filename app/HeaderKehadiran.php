<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeaderKehadiran extends Model
{
    use SoftDeletes;

    protected $table = "header_kehadiran";
    protected $primaryKey = "id_header_kehadiran";

    public $guarded = [];

    public function detail()
    {
        return $this->hasMany(DetailKehadiran::class, 'id_header_kehadiran');
    }
}
