<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pengaduan;

class Category extends Model
{
    protected $fillable=[
        'name'
    ];

    public function pengaduans(){
        return $this->hasMany(Pengaduan::class);
    }
}
