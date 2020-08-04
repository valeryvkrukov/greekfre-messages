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

Broadcast::channel('App.User.*', function($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.*', function ($user, $toUserId) {
    return $user->id == $toUserId;
});

Broadcast::channel('sms_delivery.*', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
