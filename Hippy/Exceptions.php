<?php

/**
 * Exception raised when anything not expected happens
 */
class HippyException extends Exception
{
    /**
     * Exception constructor. Use is to set the error message format.
     */
    public function __construct($code, $info, $url = NULL)
    {
        $message = "Hippy error: code=$code, info=$info";
        
        //Include URL in message if supplied
        if(!empty($url)) $message .= " url=$url";
        
        parent::__construct($message, (int)$code);
    }
}

?>