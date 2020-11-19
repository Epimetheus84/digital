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

    public static function isExists($review_id, $user_ip) {
        return (self::where([
            'review_id'  => $review_id,
            'ip_address' => $user_ip
        ])->count() > 0);
    }

    public static function createFromRequest($review_id, $ip_address) {
        $like = new self();
        $like->review_id  = $review_id;
        $like->ip_address = $ip_address;
        $like->save();
    }
}
