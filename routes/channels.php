<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifications', function ($user) {
    return true;
});

Broadcast::channel('user-notifications.{userId}', function ($user, string $userId) {
    return $user->id === $userId;
});

Broadcast::channel("user-presence", function (\App\Models\User $user) {
    return [
        "id" => $user->id,
        "email" => $user->email,
        "name" => $user->name,
        "role" => $user->role,
    ];
});

Broadcast::channel("ssh-logs.server-initializing.{serverId}", function (\App\Models\User $user, string $serverId) {
    return $user->id === \Modules\Cloud\Models\Server::find($serverId)?->user_id;
});

Broadcast::channel("server-updated.{serverId}", function (\App\Models\User $user, string $serverId) {
    return $user->id === \Modules\Cloud\Models\Server::find($serverId)?->user_id;
});
