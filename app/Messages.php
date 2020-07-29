<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = [
        'name',
        'order',
        'phone',
        'message',
        'picked_up',
        'created_at'
    ];

    protected $attributes = [
        'picked_up' => false,
        'owner_id' => null,
    ];

    public function owner()
    {
        return $this->belongsTo('App\User')->withDefault();
    }

    public function status()
    {
        return $this->hasOne('App\Delivery');
    }

}
