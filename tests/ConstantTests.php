<?php

require_once 'simpletest/autorun.php';
require_once '../Hippy.php';
require_once 'settings.php';

class ConstantTests extends UnitTestCase
{
    public function __construct()
    {
        //Make sure we start a fresh
        Hippy::destroy();
    }
    
    function testTarget()
    {
        $this->assertEqual(TEST_HIPCHAT_TARGET, Hippy::HIPCHAT_TARGET);
    }
    
    function testVersion()
    {
        $this->assertEqual(TEST_HIPCHAT_VERSION, Hippy::HIPCHAT_VERSION);
    }
    
    function testRequest()
    {
        $this->assertEqual(TEST_HIPCHAT_REQUEST, Hippy::HIPCHAT_REQUEST);
    }
    
    function testBadResponse()
    {
        $this->assertEqual(TEST_BAD_RESPONSE, Hippy::STATUS_BAD_RESPONSE);
    }
    
    function testStatusOK()
    {
        $this->assertEqual(TEST_OK, Hippy::STATUS_OK);
    }
    
    function testBadRequest()
    {
        $this->assertEqual(TEST_BAD_REQUEST, Hippy::STATUS_BAD_REQUEST);
    }
}

?>