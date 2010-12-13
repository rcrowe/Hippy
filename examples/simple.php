<?php

require '../Hippy.php';

/**
 * In order for a message to be sent, the following settings have to be set.
 * Otherwise a HippyException is thrown.
 *
 *   - token = Authentication token from HipChat dashboard
 *   - room  = Name of room or id to send message too
 *   - from  = Who's the message from
 */

//Set settings for all messages sent
Hippy::config(array(
    'token'  => 'your_token',
    'room'   => 'your_room',
    'from'   => 'your_name',
    'notify' => true          //(Optional) - whether to notify users of message
));

/**
 * Then we can go ahead and send as many messages as we want
 */
Hippy::speak('Hello from Hippy');
Hippy::speak('Build succedded');

/**
 * Maybe you want to change some settings
 */
Hippy::speak('Hello from rcrowe', array('from' => 'rcrowe'));

?>