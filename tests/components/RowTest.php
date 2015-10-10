<?php

namespace Michaeljennings\Carpenter\Tests\Components;

use Michaeljennings\Carpenter\Components\Action;
use Michaeljennings\Carpenter\Components\Cell;
use Michaeljennings\Carpenter\Components\Column;
use Michaeljennings\Carpenter\Components\Row;
use Michaeljennings\Carpenter\Session\SessionManager;
use Michaeljennings\Carpenter\Tests\TestCase;

class RowTest extends TestCase
{
    public function testRowImplementsContract()
    {
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Row', $this->makeRow());
    }

    public function testCellCanBeAddedToRow()
    {
        $row = $this->makeRow();

        $row->addCell('foo', $this->makeCell('foo', 'bar', ['foo' => 'bar']));

        $this->assertTrue($row->hasCells());
        $this->assertCount(1, $row->getCells());
    }

    public function testActionCanBeAddedToRow()
    {
        $row = $this->makeRow();

        $row->addAction($this->makeAction());

        $this->assertTrue($row->hasActions());
        $this->assertCount(1, $row->getActions());
    }

    public function testIdCanBeSetOnRow()
    {
        $row = $this->makeRow();

        $row->setId('1');

        $this->assertEquals('1', $row->getId());
    }

    public function testSetIdAliasWorks()
    {
        $row = $this->makeRow();

        $row->id('1');

        $this->assertEquals('1', $row->getId());
    }

    public function testCellAliasesWork()
    {
        $row = $this->makeRow();

        $row->cell('foo', $this->makeCell('foo', 'bar', ['foo' => 'bar']));

        $this->assertTrue($row->hasCells());
        $this->assertCount(1, $row->cells());
    }

    public function testActionAliasesWork()
    {
        $row = $this->makeRow();

        $row->action($this->makeAction());

        $this->assertTrue($row->hasActions());
        $this->assertCount(1, $row->actions());
    }

    protected function makeRow()
    {
        return new Row();
    }

    protected function makeCell($key, $value, $data)
    {
        $config = $this->getConfig();

        return new Cell(new Column($key, 'test', new SessionManager($config['session']), $config), $data, $value);
    }

    protected function makeAction()
    {
        return new Action('create');
    }
}