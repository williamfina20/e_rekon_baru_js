<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bandara extends Model
{
    protected $table = 'bandara';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function maskapai()
    {
        return $this->hasMany(Maskapai::class, 'bandara_id');
    }

    public function bandara_staf()
    {
        return $this->hasMany(BandaraStaf::class, 'bandara_id');
    }

    use HasFactory;
}
