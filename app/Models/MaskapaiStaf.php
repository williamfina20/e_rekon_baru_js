<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaskapaiStaf extends Model
{
    protected $table = 'maskapai_staf';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class, 'maskapai_id');
    }

    use HasFactory;
}
