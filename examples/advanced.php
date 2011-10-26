<?php

require '../Hippy.php';

/**
 * There are lots of documented functions not shown in these examples. Check out the documentation.
 */

$hippy = Hippy::getInstance();


//Check we have set all the neccessery settings before we try sending
try {

    $hippy->validSettings();
    
} catch(HippyException $e) { }


//Maybe you want a clean instance of Hippy
Hippy::destroy();

//Set settings for all messages sent
Hippy::config(array(
    'token'  => 'your_token',
    'room'   => 'your_room',
    'from'   => 'your_name',
    'notify' => true
));

// Queue some messages up
Hippy::add('This is the 1st message');
Hippy::add('This is the 2nd message');

// Send the queue as one message
Hippy::go();

// Send the queue as seperate messages
Hippy::go(FALSE);


?>