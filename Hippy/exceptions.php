<?php

class HippyUnknownDriverException extends Exception {};
class HippyMissingSettingException extends Exception {};
class HippyEmptyQueueException extends Exception {};

class HippyResponseException extends Exception
{
    public function __construct($info, $url = NULL)
	{    
        $message = "Hippy error: info=$info";
        
        //Include URL in message if supplied
        if(!$url !== null) $message .= " url=$url";
        
        parent::__construct($message);
    }
}

class HippyNotSentException extends HippyResponseException {};