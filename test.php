<?php


require 'Hippy.php';

$settings = array(
    'token'  => 'abc123',
    'room'   => 'General', //Name or id of room to send message to
    'from'   => 'rcrowe', //Who message is from
    'notify' => true      //Optional - whether this message should trigger a notification
);

Hippy::settings($settings);

Hippy::speak('Build succedded');



//Or just pass the token
Hippy::settings('abc123');

Hippy::speak('Build failed', array(
    'room'   => 'General',
    'from'   => 'rcrowe',
    'notify' => false
));



//Send multiple messages
Hippy::settings('abc123');

$room = Hippy::room('General')->from('rcrowe')->notify(true);

$room->speak('Unit test failed on branch master');

$room->speak('Fail on line 27', array(
    'from' => 'HippyBot'
));



//Chain calls
Hippy::settings($settings);

Hippy::room()->speak('Testing')->speak('from')->speak('Hippy!');


?>