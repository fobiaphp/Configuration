<?php

// namespace Fobia\Configuration;

use Fobia\Configuration\ConfigurationHandler;
use Fobia\Configuration\Configuration;

class HandlerTest extends ConfigurationHandler
{

}

class MyConfiguration extends Configuration
{
    protected $defaults = array(
        'self.version' => '1.2',
        'self.name' => 'MyConfiguration',
    );
}

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-03-17 at 13:10:43.
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public $defaults = array(
        'self.version' => '1.2',
        'self.name' => 'MyConfiguration',
    );

    /**
     * @var Configuration
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $cfg = array(
            'path1.to1' => 'value11',
            'path1.to2' => 'value12',
            'path2' => array(
                'to1' => 'value21'
            )
        );
        $this->object = new Configuration(new ConfigurationHandler());
        $this->object->setArray($cfg);
    }
    
    public function testConstructorOptions()
    {
        $values = array("param" => "value");
        $con = new Configuration($values);
        
        $this->assertSame($values['param'], $con['param']);
        
        $con->setArray(array('param' => 'foo'));
        $con['param1'] = 'bar';
        $this->assertEquals('foo', $con["param"]);
        $this->assertEquals('bar', $con["param1"]);
        
        $con->setDefaults();
        $this->assertEquals('value', $con["param"]);
    }
    
    public function testConstructorDefaultHandler()
    {
        $cfg = new Configuration();
        $this->assertEquals(get_class($this->object), get_class($cfg));
    }

    public function testConstructorExtends()
    {
        $values = array("param" => "value");
        $con = new MyConfiguration();
        $con->setArray($values);

        $this->assertSame('1.2', $con['self.version']);
        $this->assertSame($values['param'], $con['param']);
    }
    
    public function testConstructorSetDefault()
    {
        $values = array("param" => "value");
        $con = new Configuration(null, $values);

        $this->assertSame($values['param'], $con['param']);
        
        $defaults = $con->getDefaults();
        $this->assertEquals($defaults["param"], $values["param"]);
        
        $con->setArray(array('param' => 'foo'));
        $con['param1'] = 'bar';
        $this->assertEquals('foo', $con["param"]);
        $this->assertEquals('bar', $con["param1"]);
        
        $con->setDefaults();
        $this->assertEquals('value', $con["param"]);
    }
    
    public function testConstructorDefault()
    {
        $values = array("param" => "value");
        $con = new Configuration();
        $con->setArray($values);

        $this->assertSame($values['param'], $con['param']);
    }

    public function testConstructorInjection()
    {
        $values = array("param" => "value");
        $con = new Configuration(new HandlerTest);
        $con->setArray($values);

        $this->assertSame($values['param'], $con['param']);
    }

    public function testSetDefaultValues()
    {
        $con = new MyConfiguration(new HandlerTest);

        foreach ($this->defaults as $key => $value) {
            $this->assertEquals($con[$key], $value);
        }
    }

    public function testGetDefaultValues()
    {
        $con = new MyConfiguration(new HandlerTest);
        $defaults = $con->getDefaults();

        foreach ($this->defaults as $key => $value) {
            $this->assertEquals($defaults[$key], $value);
        }
    }

    public function testCallHandlerMethod()
    {
        $con = new Configuration(new HandlerTest);
        $defaultKeys = array_keys($this->defaults);
        $defaultKeys = ksort($defaultKeys);
        $configKeys = $con->callHandlerMethod('getKeys');
        $configKeys = ksort($configKeys);

        $this->assertEquals($defaultKeys, $configKeys);
    }

    /**
     * @covers Fobia\Configuration\Configuration::setArray
     * @todo   Implement testSetArray().
     */
    public function testSetArray()
    {
        $this->object->setArray(array('foo' => 'bar'));
        $this->assertEquals('value11', $this->object['path1.to1']);
        $this->assertEquals('bar', $this->object['foo']);

        $this->object->setArray(array('foo1' => array('foo2' => 'bar')));
        $this->assertEquals('bar', $this->object['foo1.foo2']);
    }

    /**
     * @covers Fobia\Configuration\Configuration::setDefaults
     * @todo   Implement testSetDefaults().
     */
    public function testSetDefaults()
    {
        $con = new MyConfiguration(new HandlerTest);
        $con->setArray(array('self.version' => 'bar'));
        $this->assertEquals('bar', $con['self.version']);

        $con->setDefaults();
        $this->assertEquals('1.2', $con['self.version']);
    }

    /**
     * @covers Fobia\Configuration\Configuration::getDefaults
     * @todo   Implement testGetDefaults().
     */
    // public function testGetDefaults()
    // {
    //     // Remove the following lines when you implement this test.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    // }

    /**
     * @covers Fobia\Configuration\Configuration::callHandlerMethod
     * @todo   Implement testCallHandlerMethod().
     */
    // public function testCallHandlerMethod()
    // {
    //     // Remove the following lines when you implement this test.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    // }

    /**
     * @covers Fobia\Configuration\Configuration::offsetGet
     * @todo   Implement testOffsetGet().
     */
    public function testOffsetGet()
    {
        $this->assertEquals('value11', $this->object['path1.to1']);
        $this->assertEquals('value21', $this->object['path2.to1']);
    }

    /**
     * @covers Fobia\Configuration\Configuration::offsetSet
     * @todo   Implement testOffsetSet().
     */
    public function testOffsetSet()
    {
        $this->object['path1.to3'] = 'value13';
        $this->assertEquals('value13', $this->object['path1.to3']);

        //$this->object['path1']['to4'] = 'value14';
        //$this->assertEquals('value14', $this->object['path1.to4']);
    }

    /**
     * @covers Fobia\Configuration\Configuration::offsetExists
     * @todo   Implement testOffsetExists().
     */
    public function testOffsetExists()
    {
        $this->assertEquals(true, isset($this->object['path1.to1']));
        $this->assertEquals(false, isset($this->object['path1.to5']));
    }

    /**
     * @covers Fobia\Configuration\Configuration::offsetUnset
     * @todo   Implement testOffsetUnset().
     */
    public function testOffsetUnset()
    {
        $this->assertEquals('value11', $this->object['path1.to1']);
        unset($this->object['path1.to1']);
        $this->assertEquals(null, $this->object['path1.to1']);
    }

    /**
     * @covers Fobia\Configuration\Configuration::getIterator
     * @todo   Implement testGetIterator().
     */
    // public function testGetIterator()
    // {
    //     // Remove the following lines when you implement this test.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    // }
}
