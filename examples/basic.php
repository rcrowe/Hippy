<?php

// Include the Hippy library
// Make sure Hippy/config.php is set with your Hipchat details
require '../Hippy.php';

// Send a message
Hippy::speak('[Hippy Basic Example] - Hello world');