<?php

class Hippy_Curl extends Hippy_Driver
{
	const STATUS_OK = 200;
	
	public function request($url)
	{
		$ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $code     = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        //Check we got a response
        if(strlen($response) == 0)
		{
            $errno = curl_errno($ch);
            $error = curl_error($ch);
			
			throw new HippyResponseException("CURL error: $errno - $error", $url);
        }
        
        //Check we got the correct http code
        if($code !== self::STATUS_OK)
		{
            throw new HippyResponseException("HTTP status code: $code, response=$response", $url);
        }
        
        curl_close($ch);

        //Return JSON
        return $response;
	}
}