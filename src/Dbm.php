<?php

namespace Christophedlr\Dbm;

/**
 * Database Manager
 * @package Christophedlr\Dbm
 */
class Dbm
{
    private $driver;

    /**
     * Dbm constructor.
     */
    public function __construct(string $driver)
    {
        $fqdn = 'Christophedlr\\Dbm\\Drivers\\' . $driver . 'Driver';
        $this->driver = new $fqdn();
    }

    /**
     * @return object
     */
    public function getDriver(): object
    {
        return $this->driver;
    }
}
