<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = [
        'name', 'order', 'phone', 'message', 'picked_up',
    ];

    protected $attributes = [
        'picked_up' => false,
        'owner_id' => null,
    ];

    public function owner()
    {
        return $this->belongsTo('App\User')->withDefault();
    }

}
