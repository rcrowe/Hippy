<?php


require 'Hippy.php';

Hippy::speak('Testing notification', array(
    'token' => '67ef88155f50c32346ec506e8cc193',
    'room'  => 'Hippy',
    'from'  => 'Rob C',
    'notify'=> false
));


//Or pass all of the configuration in when you speak
Hippy::speak('HipChat rocks', array(
    'token'  => '67ef88155f50c32346ec506e8cc193',
    'room'   => 'Hippy', //Name or id of room to send message to
    'from'   => 'rcrowe', //Who message is from
    'notify' => true      //Optional - whether this message should trigger a notification
));


//Or pass the settings to setttings()
//so you dont need to enter them again
$settings = array(
    'token'  => '67ef88155f50c32346ec506e8cc193',
    'room'   => 'Hippy', //Name or id of room to send message to
    'from'   => 'rcrowe', //Who message is from
    'notify' => true      //Optional - whether this message should trigger a notification
);

Hippy::settings($settings);

Hippy::speak('Did the build succedded');
Hippy::speak('Yes, build succedded');


//Or just pass the token
//and set the rest when you send
Hippy::settings('67ef88155f50c32346ec506e8cc193');

Hippy::speak('Build failed', array(
    'room'   => 'Hippy',
    'from'   => 'rcrowe',
    'notify' => false
));



//Send multiple messages
Hippy::settings('67ef88155f50c32346ec506e8cc193');

$room = Hippy::room('Hippy')->from('eworcr')->notify(true);

$room->speak('Unit test failed on branch master');

$room->speak('Fail on line 27', array(
    'from'   => 'HippyBot',
    'notify' => false
));



//Chain calls
$settings['token'] = '67ef88155f50c32346ec506e8cc193';

Hippy::settings($settings);

Hippy::room()->speak('Testing')->speak('from')->speak('Hippy!');


?>