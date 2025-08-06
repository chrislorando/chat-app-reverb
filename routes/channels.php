<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// }, ['guards' => ['web', 'auth']]);

Broadcast::channel('public-room', function ($user) {
    return true;
}, ['guards' => ['web']]);

// Broadcast::channel('room.{user1_id}.{user2_id}', function ($user1, $user2) {
//     return $user1->id === auth()->id() || $user2->id === auth()->id();
// }, ['guards' => ['web']]);

Broadcast::channel('room.{user1_id}.{user2_id}', function ($user, $user1_id, $user2_id) {
    if ((int)$user->id === (int)$user1_id || (int)$user->id === (int)$user2_id) {
        return ['id' => $user->id, 'name' => $user->name]; // data ini akan tersedia di client Echo.here()
    }

    return false;
});

Broadcast::channel('chatlist.{receiverId}', function ($receiver) {
    return (int) auth()->id() === (int) $receiver->id;
}, ['guards' => ['web']]);

Broadcast::channel('chat.{receiverId}', function ($receiver) {
    return (int) auth()->id() === (int) $receiver->id;
}, ['guards' => ['web']]);

// Broadcast::channel('online.{senderId}', function ($sender) {
//     return (int) auth()->id() === (int) $sender->id;
// }, ['guards' => ['web']]);

Broadcast::channel('presence-status', function ($user) {
    return ['id' => $user->id];
});

// Broadcast::private('private-room.{id}');
