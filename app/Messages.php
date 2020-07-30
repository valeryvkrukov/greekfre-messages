<?php

namespace App;

use App\Events\MessageSent;
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

    protected $dispatchesEvents = [
        'created' => MessageSent::class,
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
