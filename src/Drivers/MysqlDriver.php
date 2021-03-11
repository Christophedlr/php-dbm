<?php

namespace Christophedlr\Dbm\Drivers;

use Christophedlr\Dbm\Template\DriverInterface;

class MysqlDriver implements DriverInterface
{
    private $type;
    private $query;
    private $fields = [];
    private $where = [];
    private $from = [];
    private $parameters = [];

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function all(string $table = ''): DriverInterface
    {
        $field = '';

        if (!empty($table)) {
            $field = '`' . $table . '`.*';
        }

        $this->fields[] = empty($field) ? '*' : $field;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function from(string $table): DriverInterface
    {
        $this->from[] = '`' . $table . '`';

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function andWhere(string $condition): DriverInterface
    {
        $this->where[] = [
            'condition' => $condition,
            'type' => 'and',
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setParameter($parameter, $value): DriverInterface
    {
        $this->parameters[$parameter] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function query(): string
    {
        $this->generate();

        $pos = -1;
        $i = 1;

        while ($pos !== false) {
            $pos = strpos($this->query, '?');

            if ($pos !== false) {
                $this->query = substr_replace(
                    $this->query,
                    $this->parameters[$i],
                    $pos,
                    1);
                $i++;
            }
        }

        foreach ($this->parameters as $key => $value) {
            if (is_string($key)) {
                $this->query = str_replace($key, $value, $this->query);
            }
        }

        return $this->query;
    }

    /**
     * Generate a query string
     */
    protected function generate()
    {
        $query = '';

        switch ($this->type) {
            case self::SELECT:
                $query .= 'SELECT ';
                break;
        }

        $query .= implode(', ', $this->fields);
        $query .= ' FROM ' . implode(', ', $this->from);

        $i = 0;

        if (!empty($this->where)) {
            $query .= ' WHERE ';
        }

        foreach ($this->where as $where) {
            $clause = '';

            if ($where['type'] === 'and' && $i > 0) {
                $clause .= ' AND ';
            }

            $condition = preg_replace(
                '#(\w{1,})(\s{0,}(=|>|<|>=|<=|<>|!=)\s{0,})(.+)$#',
                '`$1`$2$4',
                $where['condition']
            );
            $clause .= (is_string($condition)) ? $condition : $where['condition'];
            $query .= $clause;

            $i++;
        }

        $query .= ';';

        $this->query = $query;
    }
}
