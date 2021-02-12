<?php

namespace Christophedlr\Dbm\Template;

/**
 * Base interface for DB driver
 * @package Christophedlr\Dbm\Template
 */
interface DriverInterface
{
    public const SELECT = 0x01;

    /**
     * Select type of query
     * @return $this
     */
    public function select(): self;

    /**
     * Select all fields
     * @return $this
     */
    public function all(): self;

    /**
     * Table concerning by query
     * @param string $table Name of table
     * @return $this
     */
    public function from(string $table): self;

    /**
     * Return a query string
     * @return string
     */
    public function query(): string;
}
