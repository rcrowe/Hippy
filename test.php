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
Hippy::settings('xxxxxx');

Hippy::speak('Build failed', array(
    'room'   => 'General',
    'from'   => 'rcrowe',
    'notify' => false
));



//Send multiple messages
Hippy::settings('yyyyyy');

$room = Hippy::room('General')->from('eworcr')->notify(true);

$room->speak('Unit test failed on branch master');

$room->speak('Fail on line 27', array(
    'from' => 'HippyBot'
));



//Chain calls
$settings['token'] = 'zzzzzz';

Hippy::settings($settings);

Hippy::room()->speak('Testing')->speak('from')->speak('Hippy!');


?>