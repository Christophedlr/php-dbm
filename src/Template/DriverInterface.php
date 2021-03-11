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
     * @param string $field Name of field
     * @param string $table Name of table
     * @param string $as Alias of field
     * @return $this
     */
    public function select(string $field = '', string $table = '', string $as = ''): self;

    /**
     * Select all fields
     * @return $this
     */
    public function all(string $table = ''): self;

    /**
     * Table concerning by query
     * @param string $table Name of table
     * @return $this
     */
    public function from(string $table): self;

    /**
     * Add and where condition
     * @param string $condition Where clause
     * @return $this
     */
    public function andWhere(string $condition): self;

    /**
     * Assign value to parameter
     * @param string|int $parameter Parameter name or id
     * @param mixed $value Value of parameter
     * @return $this
     */
    public function setParameter($parameter, $value): self;

    /**
     * Return a query string
     * @return string
     */
    public function query(): string;
}
