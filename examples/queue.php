<?php

// Include the Hippy library
// Make sure Hippy/config.php is set with your Hipchat details
require '../Hippy.php';

// Add an item to the queue
Hippy::add('[Hippy Queue Example] - Message 1');
Hippy::add('[Hippy Queue Example] - Message 2');

// Send the queue of messages
// By default this joins the messages with a line break
Hippy::go();


// If you want to send the queue of messages seperatly
Hippy::add('[Hippy Queue Example] - Message 1');
Hippy::add('[Hippy Queue Example] - Message 2');

Hippy::go(false);


// If the queue is empty when using Hippy::go() and exception is called
// try
// {
// 	Hippy::go();
// }
// catch(HippyEmptyQueueException $ex)
// {
// 	echo "HippyEmptyQueueException Exception: " . $ex->getMessage();
// }

// To get around the fact when your not sure if there are messages
// in the queue or not without generating an exception, then theres
// flush_queue
Hippy::flush_queue();