<?php
namespace HTTP\Jobs;

use Illuminate\Container\Container as IlluminateContainer;

class Container extends IlluminateContainer {

    /**
     * Determine if the application is in maintenance mode.
     * @return bool
     */
    public function isDownForMaintenance() {
        return false;
    }
}
