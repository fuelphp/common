<?php

namespace Fuel\Common;

use Codeception\TestCase\Test;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-25 at 17:46:44.
 */
class FormatTest extends Test
{
	public static function arrayProvider()
	{
		return array(
			array(
				array(
					array('field1' => 'Value 1', 'field2' => 35, 'field3' => 123123),
					array('field1' => 'Value 1', 'field2' => "Value\nline 2", 'field3' => 'Value 3'),
				),
				'"field1","field2","field3"
"Value 1","35","123123"
"Value 1","Value
line 2","Value 3"',
				array(
					array('field1', 'field2', 'field3'),
					array('field1' => 'Value 1', 'field2' => 35, 'field3' => 123123),
					array('field1' => 'Value 1', 'field2' => "Value\nline 2", 'field3' => 'Value 3'),
				),
			),
		);
	}

	public static function configProvider()
	{
		return array(
			array(
				'csv' => array(
					'import' => array(
						'delimiter' => ',',
						'enclosure' => '"',
						'newline'   => "\n",
						'escape'    => '"',
					),
					'export' => array(
						'delimiter' => ',',
						'enclosure' => '"',
						'newline'   => "\n",
						'escape'    => '"',
					),
					'regex_newline'   => '\n',
				),
				'xml' => array(
					'basenode' => 'xml',
					'use_cdata' => false,
				),
				'json' => array(
					'encode' => array(
						'options' => JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP,
					)
				)
			)
		);
	}

	/**
	 * @dataProvider arrayProvider
	 *
	 * @covers Fuel\Common\Format::__construct
	 * @expectedException \InvalidArgumentException
	 * @group Common
	 */
	public function testConstructorException($array, $csv)
	{
		new Format($csv, 'illegal');
	}

	/**
	 * @dataProvider arrayProvider
	 *
	 * @covers Fuel\Common\Format::__construct
	 * @covers Fuel\Common\Format::_fromCsv
	 * @covers Fuel\Common\Format::toArray
	 * @group Common
	 */
	public function testFromCsv($array, $csv)
	{
		$instance = new Format($csv, 'csv');
		$this->assertEquals($array, $instance->toArray());
	}

	/**
	 * @dataProvider arrayProvider
	 *
	 * @covers Fuel\Common\Format::__construct
	 * @covers Fuel\Common\Format::_fromCsv
	 * @covers Fuel\Common\Format::toCsv
	 * @group Common
	 */
	public function testToCsv($array1, $csv, $array2)
	{
		$instance = new Format($array1);
		$this->assertEquals($csv, $instance->toCsv());

		$instance = new Format($array2);
		$this->assertEquals($csv, $instance->toCsv());
	}

	/**
	 * @covers Fuel\Common\Format::toCsv
	 * @group Common
	 */
	public function testToCsvFromObject()
	{
		$object = new \StdClass();
		$object->field1 = 'Value 1';
		$object->field2 = 35;
		$object->field3 = 123123;

		$expected = '"field1","field2","field3"
"Value 1","35","123123"';

		$instance = new Format($object);
		$this->assertEquals($expected, $instance->toCsv());
	}

	/**
	 * @covers Fuel\Common\Format::toArray
	 * @group Common
	 */
	public function testToArray()
	{
		$instance = new Format();
		$expected = array('varA' => 'varA');
		$input = new \stdClass();
		$input->varA = 'varA';
		$output = $instance->toArray($input);
		$this->assertEquals($expected, $output);

		$output = $instance->toArray(array());
		$this->assertEquals(array(), $output);
	}

	/**
	 * @covers Fuel\Common\Format::toArray
	 * @group Common
	 */
	public function testToArrayEmpty()
	{
		$array = null;
		$expected = array();
		$instance = new Format($array);
		$this->assertEquals($expected, $instance->toArray());
	}

	/**
	 * @covers Fuel\Common\Format::_fromXml
	 * @covers Fuel\Common\Format::toPhp
	 * @group Common
	 */
	public function testFromXml()
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?>

<phpunit colors="true" stopOnFailure="false" bootstrap="bootstrap_phpunit.php">
	<php>
		<server name="doc_root" value="../../"/>
		<server name="app_path" value="fuel/app"/>
		<server name="core_path" value="fuel/core"/>
		<server name="package_path" value="fuel/packages"/>
	</php>
	<testsuites>
		<testsuite name="core">
			<directory suffix=".php">../core/tests</directory>
		</testsuite>
		<testsuite name="packages">
			<directory suffix=".php">../packages/*/tests</directory>
		</testsuite>
		<testsuite name="app">
			<directory suffix=".php">../app/tests</directory>
		</testsuite>
	</testsuites>
</phpunit>';

		$expected = array (
			'@attributes' => array (
				'colors' => 'true',
				'stopOnFailure' => 'false',
				'bootstrap' => 'bootstrap_phpunit.php',
			),
			'php' => array (
				'server' => array (
					0 => array (
						'@attributes' => array (
							'name' => 'doc_root',
							'value' => '../../',
						),
					),
					1 => array (
						'@attributes' => array (
							'name' => 'app_path',
							'value' => 'fuel/app',
						),
					),
					2 => array (
						'@attributes' => array (
							'name' => 'core_path',
							'value' => 'fuel/core',
						),
					),
					3 => array (
						'@attributes' => array (
							'name' => 'package_path',
							'value' => 'fuel/packages',
						),
					),
				),
			),
			'testsuites' => array (
				'testsuite' => array (
					0 => array (
						'@attributes' => array (
							'name' => 'core',
						),
						'directory' => '../core/tests',
					),
					1 => array (
						'@attributes' =>
						array (
							'name' => 'packages',
						),
						'directory' => '../packages/*/tests',
					),
					2 => array (
						'@attributes' =>
						array (
							'name' => 'app',
						),
						'directory' => '../app/tests',
					),
				),
			),
		);

		$instance = new Format($expected);
		$result = new Format($xml, 'xml');
		$this->assertEquals($instance->toPhp(), $result->toPhp());
	}

	/**
	 * @covers Fuel\Common\Format::toXml
	 * @group Common
	 */
	public function testToXml()
	{
		include_once __DIR__ . '/../../../../resources/ConfigMock.php';
		include_once __DIR__ . '/../../../../resources/SecurityMock.php';
		$inflector = new Inflector(new ConfigMock(), new SecurityMock(), new Str());

		$array = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => 'foo',
				)
			)
		);

		$expected = '<?xml version="1.0" encoding="utf-8"?>
<xml><articles><article><title>test</title><author>foo</author></article></articles></xml>
';
		$instance = new Format($array, null, array(), null, $inflector);

		$this->assertEquals($expected, $instance->toXml());
	}

	/**
	 * @covers Fuel\Common\Format::toXml
	 * @group Common
	 */
	public function testToXmlInvalidDate()
	{
		include_once __DIR__ . '/../../../../resources/ConfigMock.php';
		include_once __DIR__ . '/../../../../resources/SecurityMock.php';
		$inflector = new Inflector(new ConfigMock(), new SecurityMock(), new Str());

		$expected = '<?xml version="1.0" encoding="utf-8"?>
<xml><item>this is clearly not an array</item></xml>
';
		$instance = new Format('this is clearly not an array', null, array(), null, $inflector);
		$this->assertEquals($expected, $instance->toXml());
	}

	/**
	 * @covers Fuel\Common\Format::toXml
	 * @group Common
	 */
	public function testToXmlBasenode()
	{
		include_once __DIR__ . '/../../../../resources/ConfigMock.php';
		include_once __DIR__ . '/../../../../resources/SecurityMock.php';
		$inflector = new Inflector(new ConfigMock(), new SecurityMock(), new Str());

		$array = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => 'foo',
				)
			)
		);

		$expected = '<?xml version="1.0" encoding="utf-8"?>
<root><articles><article><title>test</title><author>foo</author></article></articles></root>
';
		$instance = new Format($array, null, array(), null, $inflector);
		$this->assertEquals($expected, $instance->toXml(null, null, 'root'));
	}

	/**
	 * @covers Fuel\Common\Format::toXml
	 * @group Common
	 */
	public function testToXmlEscapeTags()
	{
		include_once __DIR__ . '/../../../../resources/ConfigMock.php';
		include_once __DIR__ . '/../../../../resources/SecurityMock.php';
		$inflector = new Inflector(new ConfigMock(), new SecurityMock(), new Str());

		$array = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => '<h1>hero</h1>',
				)
			)
		);

		$expected = '<?xml version="1.0" encoding="utf-8"?>
<xml><articles><article><title>test</title><author>&lt;h1&gt;hero&lt;/h1&gt;</author></article></articles></xml>
';

		$instance = new Format($array, null, array(), null, $inflector);
		$this->assertEquals($expected, $instance->toXml());
	}

	/**
	 * @dataProvider configProvider

	 * @covers Fuel\Common\Format::toXml
	 * @group Common
	 */
	public function testToXmlCData($config)
	{
		include_once __DIR__ . '/../../../../resources/ConfigMock.php';
		include_once __DIR__ . '/../../../../resources/SecurityMock.php';
		$inflector = new Inflector(new ConfigMock(), new SecurityMock(), new Str());

		$array = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => '<h1>hero</h1>',
				)
			)
		);

		$expected = '<?xml version="1.0" encoding="utf-8"?>
<xml><articles><article><title>test</title><author><![CDATA[<h1>hero</h1>]]></author></article></articles></xml>
';
		$instance = new Format($array, null, $config, null, $inflector);
		$this->assertEquals($expected, $instance->toXml(null, null, 'xml', true));
	}

	/**
	 * @dataProvider configProvider

	 * @covers Fuel\Common\Format::_fromXml
	 * @covers Fuel\Common\Format::toArray
	 * @group Common
	 */
	public function testXmlToArrayNamespaced($config)
	{
		include_once __DIR__ . '/../../../../resources/ConfigMock.php';
		include_once __DIR__ . '/../../../../resources/SecurityMock.php';
		$inflector = new Inflector(new ConfigMock(), new SecurityMock(), new Str());

		$xml = '<?xml version="1.0" encoding="utf-8"?>
<xml xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:app="http://www.w3.org/2007/app"><article><title>test</title><app:title>app test</app:title></article></xml>';

		$expected = array(
			'article' => array(
				'title' => 'test',
			)
		);

		$instance = new Format($xml, 'xml', $config, null, $inflector);
		$this->assertEquals($expected, $instance->toArray());
	}

	/**
	 * @dataProvider configProvider
	 *
	 * @covers Fuel\Common\Format::__construct
	 * @covers Fuel\Common\Format::_fromXml
	 * @covers Fuel\Common\Format::toArray
	 * @group Common
	 */
	public function testXmlToArrayNamespacedWithXmlNS($config)
	{
		include_once __DIR__ . '/../../../../resources/ConfigMock.php';
		include_once __DIR__ . '/../../../../resources/SecurityMock.php';
		$inflector = new Inflector(new ConfigMock(), new SecurityMock(), new Str());

		$xml = '<?xml version="1.0" encoding="utf-8"?>
<xml xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:app="http://www.w3.org/2007/app"><article><title>test</title><app:title>app test</app:title></article></xml>';

		$expected = array(
			'@attributes' => array(
				'xmlns' => 'http://www.w3.org/2005/Atom',
				'xmlns:media' => 'http://search.yahoo.com/mrss/',
				'xmlns:app' => 'http://www.w3.org/2007/app',
			),
			'article' => array(
				'title' => 'test',
				'app:title' => 'app test',
			)
		);

		$instance = new Format($xml, 'xml:ns', $config, null, $inflector);
		$this->assertEquals($expected, $instance->toArray());
	}

	/**
	 * @covers Fuel\Common\Format::__construct
	 * @covers Fuel\Common\Format::_fromJson
	 * @covers Fuel\Common\Format::toArray
	 * @group Common
	 */
	public function testFromJson()
	{
		$json = '{"articles":[{"title":"test","author":"foo","specials":"[],:","tag":"\u003Ctag\u003E","apos":"McDonald\u0027s","quot":"\u0022test\u0022","amp":"M\u0026M"}]}';

		$expected = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => 'foo',
					'specials' => '[],:',
					'tag' => '<tag>',
					'apos' => 'McDonald\'s',
					'quot' => '"test"',
					'amp' => 'M&M',

				)
			)
		);

		$instance = new Format($json, 'json');
		$this->assertEquals($expected, $instance->toArray());
	}

	/**
	 * @dataProvider configProvider
	 *
	 * @covers Fuel\Common\Format::toJson
	 * @covers Fuel\Common\Format::prettyJson
	 * @group Common
	 */
	public function testToJson($config)
	{
		// An invalid UTF8 sequence
		$input = "\xB1\x31";
		$instance = new Format($input);
		$this->assertFalse($instance->toJson(null, true));

		$expected = '"this is not an array"';
		$instance = new Format('this is not an array', null, $config);
		$this->assertEquals($expected, $instance->toJson());

		$array = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => 'foo',
					'specials' => '[],:',
					'tag' => '<tag>',
					'apos' => 'McDonald\'s',
					'quot' => '"test"',
					'amp' => 'M&M',

				)
			)
		);

		$expected = '{"articles":[{"title":"test","author":"foo","specials":"[],:","tag":"\u003Ctag\u003E","apos":"McDonald\u0027s","quot":"\u0022test\u0022","amp":"M\u0026M"}]}';

		$instance = new Format($array, null, $config);
		$this->assertEquals($expected, $instance->toJson());

		// pretty json
		$expected = '{
	"articles": [
		{
			"title": "test",
			"author": "foo",
			"specials": "[],:",
			"tag": "\u003Ctag\u003E",
			"apos": "McDonald\u0027s",
			"quot": "\u0022test\u0022",
			"amp": "M\u0026M"
		}
	]
}';
		$instance = new Format($array, null, $config);
		$this->assertEquals($expected, $instance->toJson(null, true));

		// change config options
		$config['json']['encode']['options'] = 0;

		$expected = <<<EOD
{"articles":[{"title":"test","author":"foo","specials":"[],:","tag":"<tag>","apos":"McDonald's","quot":"\"test\"","amp":"M&M"}]}
EOD;

		$instance = new Format($array, null, $config);
		$this->assertEquals($expected, $instance->toJson());

		// pretty json
		$expected = <<<EOD
{
	"articles": [
		{
			"title": "test",
			"author": "foo",
			"specials": "[],:",
			"tag": "<tag>",
			"apos": "McDonald's",
			"quot": "\"test\"",
			"amp": "M&M"
		}
	]
}
EOD;
		$this->assertEquals($expected, $instance->toJson(null, true));
	}

	/**
	 * @dataProvider configProvider
	 *
	 * @covers Fuel\Common\Format::toJsonp
	 * @group Common
	 */
	public function testToJsonP($config)
	{
		$array = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => 'foo',
					'specials' => '[],:',
					'tag' => '<tag>',
					'apos' => 'McDonald\'s',
					'quot' => '"test"',
					'amp' => 'M&M',

				)
			)
		);

		$expected = 'response({"articles":[{"title":"test","author":"foo","specials":"[],:","tag":"\u003Ctag\u003E","apos":"McDonald\u0027s","quot":"\u0022test\u0022","amp":"M\u0026M"}]})';

		include_once __DIR__ . '/../../../../resources/InputMock.php';
		$instance = new Format($array, null, $config, new InputMock());
		$this->assertEquals($expected, $instance->toJsonp());

		// An invalid UTF8 sequence
		$input = "\xB1\x31";
		$instance = new Format($input, null, $config, new InputMock());
		$this->assertEquals('response()', $instance->toJsonp(null, true));
	}

	/**
	 * @covers Fuel\Common\Format::ToSerialized
	 * @group Common
	 */
	public function testToSerialized()
	{
		$array = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => 'foo',
				)
			)
		);

		$expected = 'a:1:{s:8:"articles";a:1:{i:0;a:2:{s:5:"title";s:4:"test";s:6:"author";s:3:"foo";}}}';

		$instance = new Format($array);
		$this->assertEquals($expected, $instance->toSerialized());
	}

	/**
	 * @covers Fuel\Common\Format::_FromSerialized
	 * @group Common
	 */
	public function testFromSerialized()
	{
		$input = 'a:1:{s:8:"articles";a:1:{i:0;a:2:{s:5:"title";s:4:"test";s:6:"author";s:3:"foo";}}}';

		$expected = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => 'foo',
				)
			)
		);

		$instance = new Format($input, 'serialized');
		$this->assertEquals($expected, $instance->toArray());
	}

	/**
	 * @covers Fuel\Common\Format::_fromYaml
	 * @group Common
	 */
	public function testYamlMissingYamlClass1()
	{
		global $composer;

		// disable loading Yaml
		$this->testFromYaml();
	}

	/**
	 * @covers Fuel\Common\Format::toYaml
	 * @group Common
	 */
	public function testToYaml()
	{
		$array = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => 'foo',
				)
			)
		);

		$expected = 'articles:
    - { title: test, author: foo }
';

		$instance = new Format($array);
		$this->assertEquals($expected, $instance->toYaml());
	}

	/**
	 * @covers Fuel\Common\Format::_fromYaml
	 * @covers Fuel\Common\Format::toArray
	 * @group Common
	 */
	public function testFromYaml()
	{
		$input = 'articles:
    - { title: test, author: foo }
';

		$expected = array(
			'articles' => array(
				array(
					'title' => 'test',
					'author' => 'foo',
				)
			)
		);

		$instance = new Format($input, 'yaml');
		$this->assertEquals($expected, $instance->toArray());
	}

	/**
	 * @covers Fuel\Common\Format::_fromYaml
	 * @covers Fuel\Common\Format::toYaml
	 * @group Common
	 */
	public function testYamlMissingYamlClass2()
	{
		$this->testToYaml();
	}
}
