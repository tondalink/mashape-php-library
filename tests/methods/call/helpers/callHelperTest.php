<?php

/*
 * Mashape PHP library.
 *
 * Copyright (C) 2010 Mashape, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * The author of this software is Mashape, Inc.
 * For any question or feedback please contact us at: support@mashape.com
 *
 */

require_once(dirname(__FILE__) . "/../../../../mashape/configuration/restMethod.php");
require_once(dirname(__FILE__) . "/../../../../mashape/mashape.php");
require_once(dirname(__FILE__) . "/../../../../mashape/methods/call/helpers/callHelper.php");

class CallTest extends PHPUnit_Framework_TestCase
{
	function testNull() {
		// Test NULL Simple result
		$method = new RESTMethod();
		$method->setName("touchNull");
		$method->setResult("message");
		$this->assertEquals('{"version":"V03","errors":[],"result":{"message":null}}', doCall($method, null, new NewSampleAPI()));

		// Test NULL Object
		$method = new RESTMethod();
		$method->setName("touchNull");
		$method->setObject("Ret");
		$this->assertEquals('{"version":"V03","errors":[],"result":null}', doCall($method, null, new NewSampleAPI()));
	}

	function testSimple() {
		$method = new RESTMethod();
		$method->setName("touchSimple");
		$method->setResult("message");
		$this->assertEquals('{"version":"V03","errors":[],"result":{"message":"simpleValue"}}', doCall($method, null, new NewSampleAPI()));

		$method = new RESTMethod();
		$method->setName("touchSimpleArray");
		$method->setResult("message");
		$method->setArray(true);
		$this->assertEquals('{"version":"V03","errors":[],"result":{"message":["value1",3,"value3"]}}', doCall($method, null, new NewSampleAPI()));
	}

	function testComplex() {
		RESTConfigurationLoader::reloadConfiguration(dirname(__FILE__) . "/test.xml");
		$method = new RESTMethod();
		$method->setName("touchComplex");
		$method->setObject("ClassOne");
		$this->assertEquals('{"version":"V03","errors":[],"result":{"field1":"value1","field2":"value2"}}', doCall($method, null, new NewSampleAPI()));

		RESTConfigurationLoader::reloadConfiguration(dirname(__FILE__) . "/test2.xml");
		$method = new RESTMethod();
		$method->setName("touchComplex2");
		$method->setObject("ClassOne");
		$this->assertEquals('{"version":"V03","errors":[],"result":{"field2":"value2"}}', doCall($method, null, new NewSampleAPI()));

		RESTConfigurationLoader::reloadConfiguration(dirname(__FILE__) . "/test3.xml");
		$method = new RESTMethod();
		$method->setName("touchComplex3");
		$method->setObject("ClassOne");
		$this->assertEquals('{"version":"V03","errors":[],"result":{"field1":"value1","field2":{"childField1":"child value 1","childField2":"child value 2"}}}', doCall($method, null, new NewSampleAPI()));

		RESTConfigurationLoader::reloadConfiguration(dirname(__FILE__) . "/test3.xml");
		$method = new RESTMethod();
		$method->setName("touchComplex4");
		$method->setObject("ClassOne");
		$this->assertEquals('{"version":"V03","errors":[],"result":{"field1":"value1","field2":null}}', doCall($method, null, new NewSampleAPI()));

		RESTConfigurationLoader::reloadConfiguration(dirname(__FILE__) . "/test4.xml");
		$method = new RESTMethod();
		$method->setName("touchComplex5");
		$method->setObject("ClassOne");
		$this->assertEquals('{"version":"V03","errors":[],"result":{"field1":["value1",3,"value3"]}}', doCall($method, null, new NewSampleAPI()));

		RESTConfigurationLoader::reloadConfiguration(dirname(__FILE__) . "/test5.xml");
		$method = new RESTMethod();
		$method->setName("touchComplex6");
		$method->setObject("ClassOne");
		$this->assertEquals('{"version":"V03","errors":[],"result":{"field1":[{"childField1":"child value 1","childField2":"child value 2"},{"childField1":"second child value 1","childField2":"second child value 2"}]}}', doCall($method, null, new NewSampleAPI()));

		RESTConfigurationLoader::reloadConfiguration(dirname(__FILE__) . "/test6.xml");
		$method = new RESTMethod();
		$method->setName("touchComplex7");
		$method->setObject("ClassThree");
		$this->assertEquals('{"version":"V03","errors":[],"result":{"field1":"this is field1","field2":["this","is","field",2,true],"field4":[{"field1":"child value 1","field2":"child value 2"},{"field1":"second child value 1","field2":"second child value 2"}]}}', doCall($method, null, new NewSampleAPI()));
		
		RESTConfigurationLoader::reloadConfiguration(dirname(__FILE__) . "/test.xml");
		$method = new RESTMethod();
		$method->setName("touchComplex8");
		$method->setObject("ClassOne");
		$method->setArray(true);
		$this->assertEquals('{"version":"V03","errors":[],"result":[{"field1":"value1","field2":"value2"},{"field1":"second value1","field2":"second value2"},{"field1":"third value1","field2":"third value2"}]}', doCall($method, null, new NewSampleAPI()));
	}
	
	function testError() {
		RESTConfigurationLoader::reloadConfiguration(dirname(__FILE__) . "/test7.xml");
		$method = new RESTMethod();
		$method->setName("touchError");
		$method->setObject("ClassOne");
		$method->setArray(true);
		$this->assertEquals('{"version":"V03","errors":[{"code":1,"message":"custom message"}],"result":null}', doCall($method, null, new NewSampleAPI()));
	}
}

class ClassOne {
	public $field1;
	public $field2;
}

class ClassTwo {
	public $childField1;
	public $childField2;
}

class ClassThree {
	public $f1;
	public $f2;
	public $f3;
	public $f4;

	public function getField1() {
		return $this->f1;
	}

	public function getField2() {
		return $this->f2;
	}

	public function getField3() {
		return $this->f3;
	}

	public function getField4() {
		return $this->f4;
	}
}

class NewSampleAPI extends MashapeRestAPI {

	public function touchNull() {
		return null;
	}

	public function touchSimple() {
		return "simpleValue";
	}

	public function touchSimpleArray() {
		$result = array("value1", 3, "value3");
		return $result;
	}

	public function touchComplex() {
		$result = new ClassOne();
		$result->field1 = "value1";
		$result->field2 = "value2";
		return $result;
	}

	public function touchComplex2() {
		$result = new ClassOne();
		$result->field1 = null;
		$result->field2 = "value2";
		return $result;
	}

	public function touchComplex3() {
		$result = new ClassOne();
		$result->field1 = "value1";
		$child = new ClassTwo();
		$child->childField1 = "child value 1";
		$child->childField2 = "child value 2";
		$result->field2 = $child;
		return $result;
	}

	public function touchComplex4() {
		$result = new ClassOne();
		$result->field1 = "value1";
		$result->field2 = null;
		return $result;
	}

	public function touchComplex5() {
		$result = new ClassOne();
		$result->field1 = array("value1", 3, "value3");
		$result->field2 = null;
		return $result;
	}

	public function touchComplex6() {
		$result = new ClassOne();
		$childs = array();

		$child = new ClassTwo();
		$child->childField1 = "child value 1";
		$child->childField2 = "child value 2";
		array_push($childs, $child);
		$child = new ClassTwo();
		$child->childField1 = "second child value 1";
		$child->childField2 = "second child value 2";
		array_push($childs, $child);

		$result->field1 = $childs;
		$result->field2 = null;
		return $result;
	}

	public function touchComplex7() {
		$result = new ClassThree();
		$result->f1 = "this is field1";
		$result->f2 = array("this", "is", "field", 2, true);
		$result->f3 = null;

		$childs = array();

		$child = new ClassOne();
		$child->field1 = "child value 1";
		$child->field2 = "child value 2";
		array_push($childs, $child);

		$child = new ClassOne();
		$child->field1 = "second child value 1";
		$child->field2 = "second child value 2";
		array_push($childs, $child);

		$result->f4 = $childs;
		return $result;
	}
	
	public function touchComplex8() {
		$result = array();
		$obj = new ClassOne();
		$obj->field1 = "value1";
		$obj->field2 = "value2";
		array_push($result, $obj);
		$obj = new ClassOne();
		$obj->field1 = "second value1";
		$obj->field2 = "second value2";
		array_push($result, $obj);
		
		$obj = new ClassOne();
		$obj->field1 = "third value1";
		$obj->field2 = "third value2";
		array_push($result, $obj);
		
		return $result;
	}
	
	public function touchError() {
		parent::addError(1, "custom message");
		return null;
	}
}

?>