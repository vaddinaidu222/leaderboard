<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRank extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'daily_rank', 'monthly_rank', 'yearly_rank'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

