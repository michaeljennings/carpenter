<?php

namespace Michaeljennings\Carpenter\Tests;

class TableTest extends TestCase
{
    public function testModelCanBeSet()
    {
        $table = $this->makeTable();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->store('codeigniter'));
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table',
            $table->model('Michaeljennings\Carpenter\Tests\Store\ExampleEloquentModel'));
    }

    public function testColumnCanBeAdded()
    {
        $table = $this->makeTable();

        $this->assertCount(0, $table->columns());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $table->column('test'));
        $this->assertCount(1, $table->columns());
    }

    public function testColumnsCanBeEdited()
    {
        $table = $this->makeTable();
        $column = $table->column('test')->setLabel('foo');

        $this->assertContains('foo', $column->getLabel());

        $column->setLabel('bar');

        $this->assertContains('bar', $column->getLabel());
    }

    public function testColumnAddsDefaultLabel()
    {
        $table = $this->makeTable();
        $column = $table->column('foo_bar');

        $this->assertEquals('Foo Bar', $column->getLabel());
    }

    public function testLabelSelectsLastSegmentIfDotSeparated()
    {
        $table = $this->makeTable();
        $column = $table->column('foo.bar');

        $this->assertEquals('Bar', $column->getLabel());
    }

    public function testTableActionsCanBeAdded()
    {
        $table = $this->makeTable();

        $this->assertCount(0, $table->actions());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $table->action('test'));
        $this->assertCount(1, $table->actions());
    }

    public function testRowActionsCanBeAddedAndRendered()
    {
        $table = $this->makeTableWithData();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $table->action('test', 'row'));

        $table->rows();
        $row = $table->getRows()[0];

        $this->assertCount(1, $row->getActions());
        $this->assertContains('name="test"', $table->render());
    }

    public function testTheItemCanBeObtainedFromTheRow()
    {
        $table = $this->makeTableWithData();

        $table->rows();
        $row = $table->getRows()[0];

        $this->assertArrayHasKey('id', $row->getResult());
    }

    public function testActionsCanBeEdited()
    {
        $table = $this->makeTable();
        $action = $table->action('test')->setLabel('Foo');

        $this->assertContains('Foo', $action->render());

        $action->setLabel('Bar');

        $this->assertContains('Bar', $action->render());
        $this->assertNotContains('Foo', $action->render());
    }

    public function testTableDataCanBeSet()
    {
        $table = $this->makeTable();
        $table->data($this->getData());

        $this->assertCount(3, $table->rows());
    }

    public function testTableDataCanBeArrayOfObjects()
    {
        $table = $this->makeTable();
        $table->data($this->getDataAsObjects());

        $this->assertCount(3, $table->rows());
    }

    public function testColumnCanAccessNestedData()
    {
        $table = $this->makeTable();
        $table->data($this->getDataAsObjects());

        $table->column('nested.foo');

        $row = $table->rows()[0];

        $this->assertEquals('bar', $row->getCells()['nested.foo']->value);
    }

    public function testTableResultsCanBePaginated()
    {
        $this->setPage(1);
        $table = $this->makeTableWithData();

        $this->assertCount(3, $this->getData());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->paginate(2));
        $this->assertCount(2, $table->rows());
        $this->assertEquals(2, $table->getTotalPerPage());
    }

    public function testFiltersCanRunOnResults()
    {
        $table = $this->makeTableWithData();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->filter(function($q) {
            $q->orderBy('foo', 'desc');
        }));

        $rows = $table->rows();

        $this->assertEquals('Test 7', $rows[0]->cells()['foo']->value);
    }

    public function testRenderReturnsString()
    {
        $table = $this->makeTableWithData();

        $this->assertInternalType('string', $table->__toString());

        $table = $table->render();

        $this->assertInternalType('string', $table);
        $this->assertContains('Test 7', $table);
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ViewNotFoundException
     */
    public function testRenderThrowsExceptionIfViewNotFound()
    {
        $table = $this->makeTableWithData();

        $table->render('non-existent-view.php');
    }

    public function testRowCreation()
    {
        $table = $this->makeTableWithData();

        $rows = $table->rows();

        $this->assertCount(3, $rows);
        $this->assertInternalType('array', $rows);
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Row', $rows[0]);
    }

    public function testGetRowsAlias()
    {
        $table = $this->makeTableWithData();

        $rows = $table->getRows();

        $this->assertCount(3, $rows);
        $this->assertInternalType('array', $rows);
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Row', $rows[0]);
    }

    public function testHasRows()
    {
        $table = $this->makeTableWithData();

        $this->assertFalse($table->hasRows());

        $table->rows();

        $this->assertTrue($table->hasRows());
    }

    public function testColumnsMethod()
    {
        $table = $this->makeTable();
        $table->data($this->getData());

        $this->assertCount(0, $table->columns());
        $this->assertCount(0, $table->getColumns());

        $table->column('foo');

        $this->assertCount(1, $table->columns());
        $this->assertCount(1, $table->getColumns());
    }

    public function testHasColumns()
    {
        $table = $this->makeTable();
        $table->data($this->getData());

        $this->assertFalse($table->hasColumns());

        $table->column('foo');

        $this->assertTrue($table->hasColumns());
    }

    public function testActionsReturnsAllActions()
    {
        $table = $this->makeTable();

        $this->assertCount(0, $table->actions());
        $this->assertCount(0, $table->getActions());

        $table->action('foo');

        $this->assertCount(1, $table->actions());
        $this->assertCount(1, $table->getActions());
    }

    public function testHasActions()
    {
        $table = $this->makeTable();

        $this->assertFalse($table->hasActions());

        $table->action('foo');

        $this->assertTrue($table->hasActions());
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ViewNotFoundException
     */
    public function testTemplateCanBeChanged()
    {
        $table = $this->makeTable();

        $this->assertInternalType('string', $table->render());

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->setTemplate('non-existant-template.php'));

        $table->render();
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ViewNotFoundException
     */
    public function testSetTemplateAlias()
    {
        $table = $this->makeTable();

        $this->assertInternalType('string', $table->render());

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->template('non-existant-template.php'));

        $table->render();
    }

    public function testTableTitleCanBeSet()
    {
        $table = $this->makeTable();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->setTitle('Foo'));
        $this->assertEquals('Foo', $table->getTitle());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->title('Bar'));
        $this->assertEquals('Bar', $table->getTitle());
    }

    public function testFormActionCanBeSet()
    {
        $table = $this->makeTable();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->setFormAction('GET'));
        $this->assertEquals('GET', $table->getFormAction());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->formAction('POST'));
        $this->assertEquals('POST', $table->getFormAction());
    }

    public function testFormMethodCanBeSet()
    {
        $table = $this->makeTable();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->setFormMethod('GET'));
        $this->assertEquals('GET', $table->getFormMethod());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->formMethod('POST'));
        $this->assertEquals('POST', $table->getFormMethod());
    }

    public function testPaginationLinksCanBeAccessed()
    {
        $table = $this->makeTableWithData();

        $this->assertFalse($table->getLinks());
        $this->assertFalse($table->links());

        $table->paginate(2)->rows();

        $this->assertInternalType('string', $table->getLinks());
        $this->assertInternalType('string', $table->links());
    }

    public function testHasLinks()
    {
        $table = $this->makeTableWithData();

        $this->assertFalse($table->hasLinks());

        $table->paginate(2)->rows();

        $this->assertTrue($table->hasLinks());
    }

    public function testGetTotalReturnsTotalCount()
    {
        $table = $this->makeTableWithData();
        $table->paginate(2)->rows();

        $this->assertEquals(3, $table->getTotal());
        $this->assertEquals(2, $table->getTotalPerPage());
    }

    public function testGetTotalPerPageReturnsNullIfNotPaginated()
    {
        $table = $this->makeTableWithData();
        $table->rows();

        $this->assertNull($table->getTotalPerPage());
    }

    public function testColumnsCanBeSorted()
    {
        $_SERVER['QUERY_STRING'] = 'sort=foo&table=test';
        $_SERVER['REQUEST_URI'] = 'http://localhost?sort=foo&table=test';
        $_GET['sort'] = 'foo';
        $_GET['table'] = 'test';

        $table = $this->makeTableWithData();

        $this->assertTrue($table->isSorted());
        $this->assertFalse($table->isDescending());

        $rows = $table->rows();

        $this->assertEquals('Test 1', $rows[0]->getCells()['foo']->value());

        $this->setPage(1);
        unset($_GET['sort']);
        unset($_GET['table']);
    }

    public function testColumnsCanBeSortedInDescendingOrder()
    {
        $_SERVER['QUERY_STRING'] = 'sort=foo&dir=desc&table=test';
        $_SERVER['REQUEST_URI'] = 'http://localhost?sort=foo&dir=desc&table=test';
        $_GET['sort'] = 'foo';
        $_GET['dir'] = 'desc';
        $_GET['table'] = 'test';

        $table = $this->makeTableWithData();

        $this->assertTrue($table->isSorted());
        $this->assertTrue($table->isDescending());

        $rows = $table->rows();

        $this->assertEquals('Test 7', $rows[0]->getCells()['foo']->value());

        $this->setPage(1);
        unset($_GET['sort']);
        unset($_GET['dir']);
        unset($_GET['table']);
    }

    public function testCustomSortCanBeSetForColumn()
    {
        $_SERVER['QUERY_STRING'] = 'sort=foo&table=test';
        $_SERVER['REQUEST_URI'] = 'http://localhost?sort=foo&table=test';
        $_GET['sort'] = 'foo';
        $_GET['table'] = 'test';

        $table = $this->makeTableWithData();

        $table->column('foo')->sort(function($q) {
            $q->orderBy('baz', 'desc');
        });

        $this->assertTrue($table->isSorted());
        $this->assertFalse($table->isDescending());

        $rows = $table->rows();

        $this->assertEquals('Test 7', $rows[0]->getCells()['foo']->value());

        $this->setPage(1);
        unset($_GET['sort']);
        unset($_GET['table']);
    }

}