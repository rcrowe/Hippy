<?php

require 'Hippy.php';

Hippy::config(array(
    'token'  => 'your_token',
    'room'   => 'your_room',
    'from'   => 'your_name',
    'notify' => true
));

Hippy::speak('Hello from Hippy');

Hippy::speak('Look how I can change my name', array('from' => 'Hippy'));

?>