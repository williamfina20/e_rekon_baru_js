<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatRekon extends Model
{
    protected $guarded = [];


    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class, 'maskapai_id');
    }

    public function bandara()
    {
        return $this->belongsTo(Bandara::class, 'bandara_id');
    }
    use HasFactory;
}
