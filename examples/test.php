<?php

require '../Hippy.php';

Hippy::config(array(
    'token'  => '8ec292d59c3d7f07edf2b56e1f7ee1',
    'room'   => 'Hippy',
    'from'   => 'rcrowe',
    'notify' => true          //(Optional) - whether to notify users of message
));

Hippy::speak('Hello from Hippy');

?>