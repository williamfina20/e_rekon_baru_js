<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekon extends Model
{

    protected $table = 'rekons';
    protected $guarded = [];

    public function bandara()
    {
        return $this->belongsTo(Bandara::class, 'bandara_id');
    }

    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class, 'maskapai_id');
    }

    public function users_invoice()
    {
        return $this->belongsTo(User::class, 'user_invoice');
    }

    public function riwayat_rekon()
    {
        return $this->hasMany(RiwayatRekon::class, 'rekons_id');
    }

    use HasFactory;
}
