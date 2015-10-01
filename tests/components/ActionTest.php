<?php

namespace Michaeljennings\Carpenter\Tests\Components;

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

    public function testLabelCanBeSet()
    {
    	$table = $this->makeTable();

        $action = $table->action('create')->setLabel('Foo');

        $this->assertContains('Foo', $action->render());
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
        $action = $action->title('click this');

        $this->assertContains('ng-click="custom"', $action->render());
        $this->assertContains('title="click this"', $action->render());
    }
}