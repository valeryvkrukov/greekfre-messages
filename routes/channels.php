<?php

use Illuminate\Support\Facades\Broadcast;
use App\Delivery;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('sms_delivery.{messageSid}', function ($user, $messageSid) {
    $record = Delivery::where('message_sid', '=', $messageSid)->first();
    if ($record) {
        return $user->id === $record->sender_id;
    } else {
        return false;
    }
});
