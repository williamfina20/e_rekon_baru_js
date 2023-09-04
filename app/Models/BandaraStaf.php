<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandaraStaf extends Model
{

    protected $table = 'bandara_staf';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function bandara()
    {
        return $this->belongsTo(Bandara::class, 'bandara_id');
    }

    use HasFactory;
}
