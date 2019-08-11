<?php

namespace Cevin\Lock;

use Cevin\Lock\Driver\Driver;

class Lock
{
    /**
     * @var Driver
     */
    private $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public function setDriver(Driver $driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @param string $name
     * @param string $content
     * @param int $expire
     * @param int $waitSeconds
     * @return bool
     */
    public function waitLock($name, $content = '1', $expire = 60, $waitSeconds = 60)
    {
        retry:
        $lock = false;
        while ($waitSeconds && !$lock = $this->tryGetLock($name, $content, $expire))
            sleep(1) && --$waitSeconds;

        if ($lock) {
            if ($this->driver->viewLock($name)!=$content && $waitSeconds)
                goto retry;
        }

        return $lock;
    }

    public function tryGetLock($name, $content = 1, $expire = 60)
    {
        return $this->driver->tryGetLock($name, $content, $expire);
    }

    /**
     * @param string $name
     * @param string $content
     * @param bool $force
     * @return bool
     */
    public function unlock($name, $content='1', $force = true)
    {
        if ($force)
            goto force;

        if ($this->driver->viewLock($name) == $content) {
            force:
            return $this->driver->unlock($name);
        }

        return false;
    }
}