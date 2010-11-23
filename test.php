<?php

require 'Hippy.php';

Hippy::config(array(
    'token'  => 'ca3b43383354248ab11aad2d7be8c4',
    'room'   => 'Hippy',
    'from'   => 'rcrowe',
    'notify' => true
));

Hippy::speak('test', array('from' => 'Elliot'));
Hippy::speak('test', array('from' => 'Elliot'));
Hippy::speak('test', array('from' => 'Elliot'));
Hippy::speak('test', array('from' => 'Elliot'));

?>