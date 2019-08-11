<?php

namespace Cevin\Lock\Driver;

interface Driver
{
    /**
     * @param string $name
     * @param string $content
     * @param int $expire
     * @return mixed
     */
    public function tryGetLock($name,$content,$expire);

    /**
     * @param string $name
     * @return mixed
     */
    public function unlock($name);

    /**
     * @param $name
     * @return mixed
     */
    public function viewLock($name);
}