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

Broadcast::channel('message.sent', function ($message) {

    info("Load from chanell");

    return (int) auth()->user()->id != (int) $message->owner_id;

});

/*Broadcast::channel('user.{userId}', function ($user, $userId) {
    return $user->id === $userId;
});

Broadcast::channel('sms_delivery.update_status', function ($user) {
    return true;
    /*$record = Delivery::where('message_sid', '=', $messageSid)->first();
    if ($record) {
        return $user->id === $record->sender_id;
    } else {
        return false;
    }
});*/
