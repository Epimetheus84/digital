<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $table = 'likes';

    protected $fillable = [
        'ip_address'
    ];

    public function review()
    {
        return $this->hasOne('App\Models\Review');
    }
}
