<?php

namespace Michaeljennings\Carpenter\Tests\Components;

use Michaeljennings\Carpenter\Components\Column;
use Michaeljennings\Carpenter\Session\SessionManager;
use Michaeljennings\Carpenter\Tests\TestCase;

class ColumnTest extends TestCase
{
    public function testColumnImplementsContract()
    {
        $column = $this->makeColumn();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $column);
    }

    public function testHrefIsCreatedOnConstruct()
    {
        $column = $this->makeColumn('foobar');

        $this->assertContains('foobar', $column->getHref());
    }

    public function testPresentersCanBeAddedAndRendered()
    {
        $table = $this->makeTableWithData();

        $column = $table->column('foo');

        $this->assertFalse($column->hasPresenter());

        $column->setPresenter(function() {
            return 'TEST VALUE';
        });

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $column);
        $this->assertTrue($column->hasPresenter());

        $rows = $table->rows();

        $this->assertEquals('TEST VALUE', $rows[0]->cells()['foo']);
    }

    public function testSetPresenterAlias()
    {
        $table = $this->makeTableWithData();

        $column = $table->column('foo');

        $this->assertFalse($column->hasPresenter());

        $column->presenter(function() {
            return 'TEST VALUE';
        });

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $column);
        $this->assertTrue($column->hasPresenter());

        $rows = $table->rows();

        $this->assertEquals('TEST VALUE', $rows[0]->cells()['foo']);
    }

    public function testCustomSortCanBeSetForAColumn()
    {
        $table = $this->makeTableWithData();

        $column = $table->column('foo');

        $this->assertFalse($column->hasSort());

        $column->sort(function($q) {
            $q->orderBy('foo');
        });

        $this->assertTrue($column->hasSort());
        $this->assertInstanceOf('Closure', $column->getSort());
    }

    public function testColumnLabelCanBeCustomised()
    {
        $table = $this->makeTableWithData();

        $column = $table->column('foo');

        $this->assertEquals('Foo', $column->getLabel());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $column->setLabel('TEST TITLE'));
        $this->assertEquals('TEST TITLE', $column->getLabel());
    }

    public function testColumnCanBeMadeSortable()
    {
        $table = $this->makeTableWithData();

        $column = $table->column('foo');

        $this->assertTrue($column->isSortable());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $column->unsortable());
        $this->assertFalse($column->isSortable());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $column->sortable());
        $this->assertTrue($column->isSortable());
    }

    public function testAttributesCanBeSetDynamically()
    {
        $table = $this->makeTableWithData();

        $column = $table->column('foo');

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $column->title('test'));
        $this->assertCount(1, $column->getAttributes());
    }

    protected function makeColumn($key = 'foo')
    {
        return new Column($key, 'foo_table', new SessionManager($this->getConfig()), $this->getConfig());
    }
}