<?php

namespace Michaeljennings\Carpenter\Tests\Components;

use Michaeljennings\Carpenter\Components\Action;
use Michaeljennings\Carpenter\Tests\TestCase;

class ActionTest extends TestCase
{
    public function testActionCanBeRendered()
    {
        $carpenter = $this->makeCarpenter();

        $table = $carpenter->make('test', function ($table) {
            $table->action('create')->setLabel('Create');
        });

        $value = $table->actions()['create']->render();

        $this->assertInternalType('string', $value);
        $this->assertContains('Create', $value);
    }

    public function testActionsCanBeEdited()
    {
        $table = $this->makeTable();

        $action = $table->action('create')->setLabel('Create');

        $this->assertContains('Create', $action->render());

        $table->action('create')->setLabel('Foo');

        $this->assertContains('Foo', $action->render());
    }

    public function testPresenterMethodCanBeChained()
    {
    	$table = $this->makeTable();

        $value = $table->action('create')->setPresenter(function() {
        	return 'foo';
        });

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $value);
    }

    public function testPresenterCanBeAddedAndRendered()
    {
    	$table = $this->makeTable();

        $action = $table->action('create')->setPresenter(function() {
        	return 'foo';
        });

        $value = $action->render();

        $this->assertNotNull($action->getPresenter());
        $this->assertInternalType('string', $value);
        $this->assertContains('foo', $value);
    }

    public function testSetPresenterAlias()
    {
    	$table = $this->makeTable();

        $action = $table->action('create')->presenter(function() {
        	return 'foo';
        });

        $value = $action->render();

        $this->assertNotNull($action->getPresenter());
        $this->assertInternalType('string', $value);
        $this->assertContains('foo', $value);
    }

    public function testLabelCanBeSet()
    {
    	$table = $this->makeTable();

        $action = $table->action('create')->setLabel('Foo');

        $this->assertContains('Foo', $action->render());

        $action->setLabel(function() {
            return 'Test Value';
        });

        $this->assertContains('Test Value', $action->render());
    }

    public function testTagCanBeSet()
    {
    	$table = $this->makeTable();

        $action = $table->action('create')->setTag('div');

        $this->assertContains('div', $action->render());
    }

    public function testHrefCanBeSet()
    {
    	$table = $this->makeTable();

        $action = $table->action('create')->setHref('/foo');

        $this->assertContains('href="/foo"', $action->render());
    }

    public function testClassCanBeSet()
    {
    	$table = $this->makeTable();

        $action = $table->action('create')->setClass('btn');

        $this->assertContains('class="btn"', $action->render());
    }

    public function testCustomAttributesCanBeSet()
    {
    	$table = $this->makeTable();

        $action = $table->action('create')->setAttribute('ng-click', 'custom');
        $action->title('click this');
        $action->setAttribute('data-title', function() {
            return 'TEST VALUE';
        });

        $this->assertContains('ng-click="custom"', $action->render());
        $this->assertContains('title="click this"', $action->render());
        $this->assertContains('data-title="TEST VALUE"', $action->render());
    }

    public function testActionColumnCanBeChangedFromDefault()
    {
        $table = $this->makeTableWithData();
        $action = $table->action('edit', 'row');

        $this->assertEquals('id', $action->getColumn());

        $action->setColumn('foo');

        $this->assertEquals('foo', $action->getColumn());
    }

    public function testValueAndRowArePassedToActionClosures()
    {
        $table = $this->makeTableWithData();
        $action = $table->action('edit', 'row')->setColumn('foo')->setValue('Test')->setRow($this->getData()[0]);

        $action->setLabel(function($value) {
            return $value;
        });

        $this->assertContains('Test', $action->render());

        $action->setLabel(function($value, $row) {
            return $row['foo'];
        });

        $this->assertContains('Test 1', $action->render());
    }

    public function testClosureCanBePassedToHref()
    {
        $table = $this->makeTableWithData();
        $action = $table->action('edit', 'row')->setColumn('foo')->setValue('Test')->setRow($this->getData()[0]);

        $action->href(function($value) {
            return 'edit/' . $value;
        });

        $this->assertContains('href="edit/Test"', $action->render());

        $action->href(function($value, $row) {
            return 'edit/' . $row['foo'];
        });

        $this->assertContains('href="edit/Test 1"', $action->render());
    }

    public function testClosureCanBePassedToAttribute()
    {
        $table = $this->makeTableWithData();
        $action = $table->action('edit', 'row')->setColumn('foo')->setValue('Test')->setRow($this->getData()[0]);

        $action->setAttribute('title', function($value) {
            return $value;
        });

        $this->assertContains('title="Test"', $action->render());

        $action->setAttribute('ng-click', function($value, $row) {
            return 'edit(' . $row['foo'] . ')';
        });

        $this->assertContains('ng-click="edit(Test 1)"', $action->render());
    }

    public function testClosureCanBePassedToClass()
    {
        $table = $this->makeTableWithData();
        $action = $table->action('edit', 'row')->setColumn('foo')->setValue('Test')->setRow($this->getData()[0]);

        $action->setClass(function($value) {
            return $value;
        });

        $this->assertContains('class="Test"', $action->render());

        $action->setClass(function($value, $row) {
            return $row['foo'];
        });

        $this->assertContains('class="Test 1"', $action->render());
    }

    public function testActionsCanBeDisplayedWhenAConditionIsMet()
    {
        $action = $this->makeAction();
        $row = ['foo' => 'bar'];

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $action->when(function($row) {
            return ($row['foo'] == 'bar');
        }));

        $this->assertTrue($action->valid($row));

        $action->when(function($row) {
            return ($row['foo'] == 'baz');
        });

        $this->assertFalse($action->valid($row));
    }

    public function testColumnCanBeSet()
    {
        $action = $this->makeAction();

        $this->assertFalse($action->setColumn());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $action->setColumn('foo'));
        $this->assertEquals('foo', $action->getColumn());
    }

    public function testSetColumnAlias()
    {
        $action = $this->makeAction();

        $this->assertFalse($action->column());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $action->column('foo'));
        $this->assertEquals('foo', $action->getColumn());
    }

    public function testValueCanBeSet()
    {
        $action = $this->makeAction();

        $this->assertFalse($action->setValue());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $action->setValue('Test'));
    }

    public function testSetValueAlias()
    {
        $action = $this->makeAction();

        $this->assertFalse($action->value());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $action->value('Test'));
    }

    public function testRowCanBeSet()
    {
        $action = $this->makeAction();

        $this->assertFalse($action->setRow());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $action->setRow(['foo' => 'bar']));
    }

    public function testSetRowAlias()
    {
        $action = $this->makeAction();

        $this->assertFalse($action->row());
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Action', $action->row(['foo' => 'bar']));
    }

    public function makeAction()
    {
        return new Action('create');
    }
}