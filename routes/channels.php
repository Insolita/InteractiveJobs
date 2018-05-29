<?php

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

use App\User;

Broadcast::channel('User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});


Broadcast::channel('Job.{id}', function (User $user, $id) {
    return $user->isAdmin() || $user->hasJob($id);
});

Broadcast::channel('JobsMonitor.{id}', function (User $user, $id) {
    return $user->isAdmin() || (int)$user->id === (int)$id;
});