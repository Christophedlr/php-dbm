<?php

namespace Christophedlr\Dbm\Tests\MysqlDriver;

use Christophedlr\Dbm\Dbm;
use Christophedlr\Dbm\Drivers\MysqlDriver;
use PHPUnit\Framework\TestCase;

/**
 * Copyright (c) 2021 Christophe Daloz - De Los Rios
 *  This code is licensed under MIT license (see LICENSE for details)
 */

class SelectTest extends TestCase
{
    /**
     * @var MysqlDriver
     */
    private $dbm;

    protected function setUp(): void
    {
        $dbm = new Dbm('Mysql');
        $this->dbm = $dbm->getDriver();
    }

    /**
     * Test if select all fields in table
     */
    public function testSelectAll()
    {
        $query = $this->dbm->select()->all()->from('test')->query();

        $this->assertEquals('SELECT * FROM `test`;', $query);
    }

    /**
     * Test if select all fields in table with table name
     */
    public function testSelectAllWithTablename()
    {
        $query = $this->dbm->select()->all('test')->from('test')->query();

        $this->assertEquals('SELECT `test`.* FROM `test`;', $query);
    }

    /**
     * Test if select a field in table
     */
    public function testSelect()
    {
        $query = $this->dbm->select('name')->from('test')->query();

        $this->assertEquals('SELECT `name` FROM `test`;', $query);
    }

    /**
     * Test if select a field in table with table name
     */
    public function testSelectWithTablename()
    {
        $query = $this->dbm->select('name', 'test')->from('test')->query();

        $this->assertEquals('SELECT `test`.`name` FROM `test`;', $query);
    }

    /**
     * Test if select a field in table with as alias
     */
    public function testSelectWithAs()
    {
        $query = $this->dbm->select('name', '', 'n')->from('test')->query();

        $this->assertEquals('SELECT `name` AS `n` FROM `test`;', $query);
    }

    /**
     * Test if select a field in table with table name and as alias
     */
    public function testSelectWithTablenameAndAs()
    {
        $query = $this->dbm->select('name', 'test', 'n')->from('test')->query();

        $this->assertEquals('SELECT `test`.`name` AS `n` FROM `test`;', $query);
    }

    /**
     * Test if select a from two tables
     */
    public function testSelectFromTwoTables()
    {
        $query = $this->dbm->
        select('name', 'test')->
        all('t2')->
        from('test')->
        from('t2')->
        query();

        $this->assertEquals('SELECT `test`.`name`, `t2`.* FROM `test`, `t2`;', $query);
    }

    /**
     * Test if select all field in test table with equal where clause
     */
    public function testSelectWhereId()
    {
        $query = $this->dbm
            ->select()
            ->all()
            ->from('test')
            ->andWhere('id = 0')
            ->query();

        $this->assertEquals('SELECT * FROM `test` WHERE `id` = 0;', $query);
    }

    /**
     * Test if select all field in test table with equals where clauses (AND)
     */
    public function testSelectWhereIdAndName()
    {
        $query = $this->dbm
            ->select()
            ->all()
            ->from('test')
            ->andWhere('id = 0')
            ->andWhere('name=\'test\'')
            ->query();

        $this->assertEquals("SELECT * FROM `test` WHERE `id` = 0 AND `name`='test';", $query);
    }
}
