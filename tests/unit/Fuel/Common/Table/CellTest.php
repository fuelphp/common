<?php

namespace Fuel\Common\Table;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-25 at 15:51:45.
 */
class CellTest extends \Codeception\TestCase\Test
{

	/**
	 * @var Cell
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function _before()
	{
		$this->object = new Cell;
	}

	/**
	 * @covers Fuel\Common\Table\Cell::getContent
	 * @covers Fuel\Common\Table\Cell::setContent
	 * @group Common
	 */
	public function testGetSetContent()
	{
		$content = 'FooBarBaz';
		$this->object->setContent($content);

		$this->assertEquals($content, $this->object->getContent());
	}

	/**
	 * @covers Fuel\Common\Table\Cell::__construct
	 * @covers Fuel\Common\Table\Cell::getContent
	 * @covers Fuel\Common\Table\Cell::setContent
	 * @group Common
	 */
	public function testConstruct()
	{
		$new = new Cell;
		$this->assertInstanceOf('Fuel\Common\Table\Cell', $new);
	}

	/**
	 * @covers Fuel\Common\Table\Cell::setAttributes
	 * @covers Fuel\Common\Table\Cell::getAttributes
	 * @group Common
	 */
	public function testSetGetAttributes()
	{
		$attributes = array('class' => 'table', 'id' => 'test');

		$this->object->setAttributes($attributes);

		$this->assertEquals($attributes, $this->object->getAttributes());
	}

}
