<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budget';

    protected $fillable = [
        'total_budget',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
