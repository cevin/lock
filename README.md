# A Simple & Open lock driver for php

## usage

```php
require __DIR__.'/vendor/autoload.php';

$driver = new Cevin\Lock\Driver\Redis('localhost');
$lock = new Cevin\Lock\Lock($driver);

$lockKey = sprintf('goods:%d',1);
$userId = 1;
$lockExpire = 10;
$waitSecondsForGetingLock = 10;

$status = $lock->waitLock($lockKey,$userId,$lockExpire,$waitSecondsForGetingLock);

sleep(5);// another processor status will be waiting
# sleep(11); // current lock will be expired
# sleep(11); // lock fail, $status=false

$lock->unlock($lockKey);

if ($lock->waitLock($lockKey,$uid,10,5))
{
    // do something
    $lock->unlock($lockKey);
}

```

## custom driver

```php
class dbDriver implements Driver
{
    public function tryGetLock($name, $content, $expire)
    {
        if(DB::select('exists lock'))
            return false;
        else
            return DB::query('insert...');
        
    }

    public function unlock($name)
    {
        return DB::from('lock')->where('pk',$name)->delete();
    }

    public function viewLock($name)
    {
        return DB::select('content')->from('lock')->where('pk',$name)->value();
    }
}
$lock = new Lock(new dbDriver());
```