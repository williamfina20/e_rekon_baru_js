<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maskapai extends Model
{
    protected $table = 'maskapai';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function rekon()
    {
        return $this->hasMany(Rekon::class, 'maskapai_id')->orderBy('bulan');
    }

    public function bandara()
    {
        return $this->belongsTo(Bandara::class, 'bandara_id');
    }

    public function maskapai_pusat()
    {
        return $this->belongsTo(User::class, 'maskapai_pusat_id', 'id');
    }

    public function maskapai_staf()
    {
        return $this->hasMany(MaskapaiStaf::class, 'maskapai_id');
    }

    use HasFactory;
}
