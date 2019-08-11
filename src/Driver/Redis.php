<?php

namespace Cevin\Lock\Driver;

use Redis as Connector;

class Redis implements Driver
{
    /**
     * @var Connector
     */
    private $connector;

    public function __construct($host,$port=6379,$auth=null)
    {
        $this->connector = new Connector();
        $this->connector->connect($host,$port);
        if($auth)
            $this->connector->auth($auth);
    }

    public function tryGetLock($name, $content, $expire)
    {
        return $this->connector->set($name,$content,[
            "nx","ex",$expire
        ]);
    }

    public function unlock($name)
    {
        return $this->connector->del($name);
    }

    public function viewLock($name)
    {
        return $this->connector->get($name);
    }
}