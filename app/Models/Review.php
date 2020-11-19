<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 'reviews';

    protected $fillable = [
        'author',
        'text',
        'ip_address'
    ];

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d.m.Y H:i:s', strtotime($value));
    }

    public function next()
    {
        return Review::query()->where('id', '>', $this->id)->max('id');
    }

    public function prev()
    {
        return Review::query()->where('id', '<', $this->id)->max('id');
    }

    public static function filteredData($order, $dir) {
        return Review::query()->withCount('likes')
                    ->groupBy('reviews.id')
                    ->orderBy($order,$dir)->get();
    }
}
