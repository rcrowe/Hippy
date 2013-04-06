<?php

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <hello@vivalacrowe.com>
 * @copyright Copyright (c) 2013, Rob Crowe.
 * @license MIT
 */

namespace rcrowe\Hippy;

use rcrowe\Hippy\Message\SenderInterface;
use rcrowe\Hippy\Message\MessageInterface;

/**
 * Store multiple messages before sending them all at once.
 */
class Queue implements SenderInterface, \ArrayAccess, \Iterator, \Countable
{
    /**
     * The queue it's self. Just a simple array.
     *
     * @var array
     */
    protected $container = array();

    /**
     * Add an item to the queue.
     *
     * The benefit of using the add function over using the queue as an
     * array is that you are given back an index of the new item in the queue.
     * This index allows you to remove it at a later stage if you choose.
     *
     * @see \rcrowe\Hippy\Message\Queue::offsetSet()
     *
     * @param \rcrowe\Hippy\Message\MessageInterface $message
     * @return int Index of the new item in the queue.
     */
    public function add(MessageInterface $message)
    {
        // Get the next index in the container that we can insert into
        if (count($this->container) > 0) {
            $keys  = array_keys($this->container);
            $index = ($keys[ count($keys) - 1 ]) + 1;
        } else {
            $index = 0;
        }

        $this->container[$index] = $message;

        return $index;
    }

    /**
     * Remove an item from the queue.
     *
     * @see \rcrowe\Campfire\Message\Queue::add()
     *
     * @param int $index Index for the message in the queue you want removed. NULL empties queue.
     * @return bool TRUE when the item is removed from the queue
     *
     * @throws OutOfRangeException Thrown when the index does not exist.
     */
    public function remove($index = null)
    {
        if ($index !== null) {

            if (!$this->offsetExists($index)) {
                throw new \OutOfRangeException('Unknown index: '.$index);
            }

            unset($this->container[$index]);
        } else {
            $this->container = array();
        }
    }

    /**
     * Set an index of the queue with an array interface.
     *
     * For example $queue[] = 'string' or $queue[1] = 'string'.
     * NOTE: Currently if adding a new item, even when passing an offset, the value will be
     * appended and not interested at that index. If the index exists, then the old value will
     * be removed and then the new value appended.
     *
     * @param int|null                               $offset Index of the queue to remove/add.
     * @param \rcrowe\Hippy\Message\MessageInterface $message
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->add($value);
        } else {

            if ($this->offsetExists($offset)) {
                $this->remove($offset);
            }

            $this->add($value);
        }
    }

    /**
     * Using the array interface, check whether an index in the queue exists.
     *
     * For example isset($queue[0]);
     *
     * @param int $offset Index that you want to check the existence for.
     * @return bool Whether the index exists or not.
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Using the array interface, remove an item from the queue.
     *
     * For example unset($queue[1]);
     *
     * @param int $offset Index that you want to remove from the queue.
     * @return bool TRUE when the item is removed.
     *
     * @throws OutOfRangeException Thrown when the index does not exist.
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * Using the array interface, get an item from the queue.
     *
     * For example echo $queue[0];
     *
     * @param int $offset Index that you want to get the value for.
     * @return string|null If the index can not be found NULL is returned, else the value in the queue.
     */
    public function offsetGet($offset)
    {
        return ($this->offsetExists($offset)) ? $this->container[$offset] : null;
    }

    /**
     * Rewind back to the first element in the queue.
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->container);
    }

    /**
     * Returns the current element.
     *
     * @return string
     */
    public function current()
    {
        return current($this->container);
    }

    /**
     * Returns the key of the current element.
     *
     * @return int
     */
    public function key()
    {
        return key($this->container);
    }

    /**
     * Moves the current position to the next queue.
     *
     * @return void
     */
    public function next()
    {
        next($this->container);
    }

    /**
     * This method is called after Iterator::rewind() and Iterator::next() to check if the current position is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    /**
     * Return the number of items currently in the queue.
     *
     * For example: count($queue)
     *
     * @return int
     */
    public function count()
    {
        return count($this->container);
    }
}
