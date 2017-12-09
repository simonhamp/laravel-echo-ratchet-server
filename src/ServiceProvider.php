<?php

namespace SimonHamp\LaravelEchoRatchetServer;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->mergeConfigFrom($this->packagePathTo('config/broadcasting.php'), 'broadcasting.connections');
        $this->mergeConfigFrom($this->packagePathTo('config/ratchet.php'), 'ratchet');
        $this->mergeConfigFrom($this->packagePathTo('config/zmq.php'), 'zmq');
    }

    private function packagePathTo($path)
    {
        return __DIR__.'/../' . $path;
    }
}
