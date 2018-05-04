<?php

namespace Neo\PusherBeams;

use Illuminate\Support\ServiceProvider;
use Pusher\PushNotifications\PushNotifications;

class PusherBeamsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->when(PusherBeams::class)
            ->needs(PushNotifications::class)
            ->give(function () {
                $pusherConfig = config('broadcasting.connections.pusher');

                return new PushNotifications([
                    'secretKey' => array_get($pusherConfig, 'beams.secret_key'),
                    'instanceId' => array_get($pusherConfig, 'beams.instance_id'),
                ]);
            });
    }
}
