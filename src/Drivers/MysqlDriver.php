<?php

namespace Christophedlr\Dbm\Drivers;

use Christophedlr\Dbm\Dbm;
use Christophedlr\Dbm\Template\DriverInterface;

class MysqlDriver implements DriverInterface
{
    private $type;
    private $fields = [];
    private $from = '';


    /**
     @inheritDoc
     */
    public function select(string $field = '', string $table = '', string $as = ''): DriverInterface
    {
        $this->type = self::SELECT;

        $selectField = '';

        if (!empty($field)) {
            $selectField = '`' . $field . '`';
        }

        if (!empty($table)) {
            $selectField = '`' . $table . '`.' . $selectField;
        }

        if (!empty($as)) {
            $selectField .= ' AS `' . $as . '`';
        }

        if (!empty($selectField)) {
            $this->fields[] = $selectField;
        }

        return $this;
    }

    /**
    @inheritDoc
     */
    public function all(string $table = ''): DriverInterface
    {
        $field = '';

        if (!empty($table)) {
            $field = '`' . $table . '.`*';
        }

        $this->fields[] = empty($field) ? '*' : $field;

        return $this;
    }

    /**
    @inheritDoc
     */
    public function from(string $table): DriverInterface
    {
        $this->from = '`' . $table . '`';

        return $this;
    }

    /**
    @inheritDoc
     */
    public function query(): string
    {
        $query = '';

        switch ($this->type) {
            case self::SELECT:
                $query .= 'SELECT ';
                break;
        }

        $query .= implode(', ', $this->fields);
        $query .= ' FROM ' . $this->from;
        $query .= ';';

        return $query;
    }
}
