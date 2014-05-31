<?php

namespace Fuel\Common\Table\Render;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-25 at 15:51:45.
 */
class SimpleTableTest extends \Codeception\TestCase\Test
{

	/**
	 * @var SimpleTable
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new SimpleTable;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{

	}

	/**
	 * @covers \Fuel\Common\Table\Render::renderTable
	 * @covers \Fuel\Common\Table\Render\SimpleTable::container
	 * @covers \Fuel\Common\Table\Render\SimpleTable::row
	 * @covers \Fuel\Common\Table\Render\SimpleTable::cell
	 * @covers \Fuel\Common\Table\Render\SimpleTable::addAttributes
	 * @group Common
	 */
	public function testRender()
	{
		$table = new \Fuel\Common\Table();
		$table
			->addCell('foo')
			->addCell('bar')
			->addRow();

		$expected = '<table><thead></thead><tbody><tr><td>foo</td><td>bar</td></tr></tbody><tfoot></tfoot></table>';

		$result = $this->object->renderTable($table);

		$this->assertXmlStringEqualsXmlString(
			$expected,
			$result
		);
	}

	/**
	 * @covers \Fuel\Common\Table\Render::renderTable
	 * @covers \Fuel\Common\Table\Render\SimpleTable::container
	 * @covers \Fuel\Common\Table\Render\SimpleTable::row
	 * @covers \Fuel\Common\Table\Render\SimpleTable::cell
	 * @covers \Fuel\Common\Table\Render\SimpleTable::addAttributes
	 * @group Common
	 */
	public function testRenderWithAttributes()
	{
		$table = new \Fuel\Common\Table();
		$table
			->setAttributes(array('id' => 'table'))
			->addCell('foo', array('id' => 'foo'))
			->addCell('bar', array('id' => 'bar'))
			->setCurrentRowAttributes(array('id' => 'row'))
			->addRow();

		$expected = '<table id="table"><thead></thead><tbody><tr id="row"><td id="foo">foo</td><td id="bar">bar</td></tr></tbody><tfoot></tfoot></table>';

		$result = $this->object->renderTable($table);

		$this->assertXmlStringEqualsXmlString(
			$expected,
			$result
		);
	}

}
