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


?>