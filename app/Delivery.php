<?php

namespace App;

use App\Events\DeliveryStatusUpdated;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'sender_id',
        'messages_id',
        'message_sid',
        'account_sid',
        'status',
    ];

    protected $dispatchesEvents = [
        'updated' => DeliveryStatusUpdated::class,
    ];

    public function message()
    {
        return $this->belongsTo('App\Messages');
    }
}
