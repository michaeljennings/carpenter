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

    public function testGetPresenterReturnsFalseIfNoPresenterIsSet()
    {
        $column = $this->makeColumn();

        $this->assertFalse($column->getPresenter());
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

    public function testHrefContainsWillSortByColumnIfNotBeingSorted()
    {
        $column = $this->makeTableWithData()->column('foo');

        $this->assertContains('sort=foo', $column->getHref());
    }

    public function testHrefContainsDirectionIfColumnIsBeingSortedAsc()
    {
        $_SERVER['QUERY_STRING'] = 'sort=foo';
        $_SERVER['REQUEST_URI'] = 'http://localhost?sort=foo';
        $_GET['sort'] = 'foo';

        $column = $this->makeTableWithData()->column('foo');

        $this->assertContains('sort=foo&dir=desc', $column->getHref());

        $this->setPage();
        unset($_GET['sort']);
    }

    public function testHrefReturnsRootIfSortingDesc()
    {
        $_SERVER['QUERY_STRING'] = 'sort=foo&dir=desc';
        $_SERVER['REQUEST_URI'] = 'http://localhost?sort=foo&dir=desc';
        $_GET['sort'] = 'foo';
        $_GET['dir'] = 'desc';

        $column = $this->makeTableWithData()->column('foo');

        $this->assertNotContains('sort=foo&dir=desc', $column->getHref());

        $this->setPage();
        unset($_GET['sort']);
        unset($_GET['dir']);
    }

    public function testHrefGetsResetIfRemovingSort()
    {
        $_SERVER['QUERY_STRING'] = '';
        $_SERVER['REQUEST_URI'] = 'http://localhost';
        $_GET['sort'] = 'foo';
        $_GET['dir'] = 'desc';

        $column = $this->makeTableWithData()->column('foo');

        $this->assertNotContains('dir=desc', $column->getHref());
        $this->assertContains('sort=foo', $column->getHref());

        $this->setPage();
        unset($_GET['sort']);
        unset($_GET['dir']);
    }

    public function testAttributesCanBeSetDynamically()
    {
        $table = $this->makeTableWithData();

        $column = $table->column('foo');

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $column->title('test'));
        $this->assertEquals('test', $column->get('title'));
    }

    protected function makeColumn($key = 'foo')
    {
        return new Column($key, 'foo_table', new SessionManager($this->getConfig()), $this->getConfig());
    }
}