<?php

namespace rcrowe\Hippy\Tests\Queue;

use rcrowe\Hippy\Message;
use rcrowe\Hippy\Queue;

class QueueInterfaceTest extends \PHPUnit_Framework_TestCase
{
    protected $queue;

    public function setUp()
    {
        $this->queue = new Queue;
    }

    public function testSingleMessage()
    {
        $index = $this->queue->add(new Message);

        $this->assertTrue(is_int($index));
        $this->assertEquals(0, $index);

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertEquals(1, count($queue));
        $this->assertTrue(isset($queue[0]));
        $this->assertTrue(is_a($queue[0], 'rcrowe\Hippy\Message\MessageInterface'));
    }

    public function testQueingMessage()
    {
        $this->queue->add(new Message);
        $this->queue->add(new Message);
        $this->queue->add(new Message);

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertEquals(3, count($queue));

        $this->assertTrue(isset($queue[0]));
        $this->assertTrue(isset($queue[1]));
        $this->assertTrue(isset($queue[2]));

        $this->assertTrue(is_a($queue[0], 'rcrowe\Hippy\Message\MessageInterface'));
        $this->assertTrue(is_a($queue[1], 'rcrowe\Hippy\Message\MessageInterface'));
        $this->assertTrue(is_a($queue[2], 'rcrowe\Hippy\Message\MessageInterface'));
    }

    public function testQueueAsArray()
    {
        $this->queue[] = new Message;
        $this->queue[] = new Message;
        $this->queue[] = new Message;

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertEquals(3, count($queue));

        $this->assertTrue(isset($queue[0]));
        $this->assertTrue(isset($queue[1]));
        $this->assertTrue(isset($queue[2]));

        $this->assertTrue(is_a($queue[0], 'rcrowe\Hippy\Message\MessageInterface'));
        $this->assertTrue(is_a($queue[1], 'rcrowe\Hippy\Message\MessageInterface'));
        $this->assertTrue(is_a($queue[2], 'rcrowe\Hippy\Message\MessageInterface'));
    }

    // Trying to set an item to a non-existing index
    public function testQueueAsArrayWithBadIndex()
    {
        $this->queue[0]  = new Message;
        $this->queue[1]  = new Message;
        $this->queue[4]  = new Message;
        $this->queue[10] = new Message;

        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertTrue(is_a($queue[0], 'rcrowe\Hippy\Message\MessageInterface'));
        $this->assertTrue(is_a($queue[1], 'rcrowe\Hippy\Message\MessageInterface'));
        $this->assertTrue(is_a($queue[2], 'rcrowe\Hippy\Message\MessageInterface'));
        $this->assertTrue(is_a($queue[3], 'rcrowe\Hippy\Message\MessageInterface'));
    }

    // Setting at an index just removes it and adds a new message to the end
    public function testQueueAsArrayWithIndex()
    {
        $message = new Message;
        $message->setText('Test 1');
        $this->queue[] = $message;

        $message = new Message;
        $message->setText('Test 2');
        $this->queue[] = $message;

        $message = new Message;
        $message->setText('Test 3');
        $this->queue[] = $message;

        $message = new Message;
        $message->setText('Test 4');
        $this->queue[] = $message;

        $message = new Message;
        $message->setText('Test 5');
        $this->queue[] = $message;

        $message = new Message;
        $message->setText('Test 6');
        $this->queue[] = $message;


        $message = new Message;
        $message->setText('There was a big fish');
        $this->queue[1] = $message;

        $message = new Message;
        $message->setText('that sat on a cat');
        $this->queue[3] = $message;

        $message = new Message;
        $message->setText('smoking Mr Dogs finest');
        $this->queue[4] = $message;


        $refObj  = new \ReflectionObject($this->queue);
        $refProp = $refObj->getProperty('container');
        $refProp->setAccessible(true);
        $queue = $refProp->getValue($this->queue);

        $this->assertTrue(isset($queue[0]));
        $this->assertFalse(isset($queue[1]));
        $this->assertTrue(isset($queue[2]));
        $this->assertFalse(isset($queue[3]));
        $this->assertFalse(isset($queue[4]));
        $this->assertTrue(isset($queue[5]));
        $this->assertTrue(isset($queue[6]));
        $this->assertTrue(isset($queue[7]));
        $this->assertTrue(isset($queue[8]));

        $this->assertEquals($queue[0]->getMessage(), 'Test 1');
        $this->assertEquals($queue[2]->getMessage(), 'Test 3');
        $this->assertEquals($queue[5]->getMessage(), 'Test 6');
        $this->assertEquals($queue[6]->getMessage(), 'There was a big fish');
        $this->assertEquals($queue[7]->getMessage(), 'that sat on a cat');
        $this->assertEquals($queue[8]->getMessage(), 'smoking Mr Dogs finest');
    }

    public function testQueueGetAsArray()
    {
        $message = new Message;
        $message->setText('test 1');
        $this->queue->add($message);

        $message = new Message;
        $message->setText('1 tset');
        $this->queue->add($message);

        $this->assertEquals($this->queue[0]->getMessage(), 'test 1');
        $this->assertEquals($this->queue[1]->getMessage(), '1 tset');
    }

    public function testQueueCount()
    {
        $this->queue[] = new Message;
        $this->queue[] = new Message;
        $this->queue[] = new Message;

        $this->assertEquals($this->queue->count(), 3);
        $this->assertEquals(count($this->queue), 3);
    }

    public function testRemoveQueueItem()
    {
        // Array access doesn't support removal index
        // through normal function calls
        $this->assertFalse(is_int( $this->queue[] = new Message ));
        $this->assertEquals(count($this->queue), 1);
        unset($this->queue[0]);
        $this->assertEquals(count($this->queue), 0);

        // Adding to the queue with add() will return an index
        // so that you can call remove on it
        $index = $this->queue->add(new Message);
        $this->assertEquals($index, 0);

        $index = $this->queue->add(new Message);
        $this->assertEquals($index, 1);

        $this->assertEquals(count($this->queue), 2);

        $this->queue->remove(1);
        $this->assertEquals(count($this->queue), 1);

        $this->queue->remove(0);
        $this->assertEquals(count($this->queue), 0);


        // Now lets add & remove to make sure all the correct indexes exist
        $this->queue[] = new Message;
        $index = $this->queue->add(new Message);

        $this->assertEquals($index, 1);

        $this->queue->remove($index);

        $index = $this->queue->add(new Message);

        $this->assertEquals($index, 1);

        $this->queue[] = new Message;
        $index = $this->queue->add(new Message);
        $this->queue[] = new Message;

        $this->assertEquals($index, 3);

        $this->queue->remove($index);

        $this->assertEquals(count($this->queue), 4);
    }

    public function testEmptyQueue()
    {
        $this->queue[] = new Message;
        $this->queue[] = new Message;
        $this->queue[] = new Message;

        $this->assertEquals(count($this->queue), 3);
        $this->queue->remove();
        $this->assertEquals(count($this->queue), 0);
    }

    public function testMessageRemoveChecks()
    {
        $this->queue->add(new Message);
        $this->queue->add(new Message);
        $this->queue->add(new Message);

        $this->assertTrue($this->queue->offsetExists(0));
        $this->assertTrue($this->queue->offsetExists(1));
        $this->assertTrue($this->queue->offsetExists(2));
        $this->assertFalse($this->queue->offsetExists(3));

        try
        {
            $this->queue->remove(3);
            $this->assertFalse(true);
        }
        catch(\OutOfRangeException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Unknown index: 3');
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testMessageRemoveChecksAsArray()
    {
        $this->queue[] = new Message;
        $this->queue[] = new Message;

        $this->assertTrue($this->queue->offsetExists(0));
        $this->assertTrue($this->queue->offsetExists(1));
        $this->assertFalse($this->queue->offsetExists(2));

        $this->assertTrue(isset($this->queue[0]));
        $this->assertTrue(isset($this->queue[1]));
        $this->assertFalse(isset($this->queue[2]));

        try
        {
            unset($this->queue[2]);
            $this->assertFalse(true);
        }
        catch(\OutOfRangeException $ex)
        {
            $this->assertEquals($ex->getMessage(), 'Unknown index: 2');
        }
        catch(\Exception $ex)
        {
            $this->assertFalse(true);
        }
    }

    public function testQueueIteratorInterface()
    {
        $values = array('RC', 'Dog', 'Cat', 'Carrot Cake');

        foreach ($values as $value) {
            $message = new Message;
            $message->setText($value);
            $this->queue->add($message);
        }

        foreach ($this->queue as $value) {
            $this->assertTrue(in_array($value->getMessage(), $values));
        }

        foreach ($this->queue as $key => $value) {
            $this->assertTrue(is_integer($key));
            $this->assertEquals($value->getMessage(), $values[$key]);
        }
    }
}
