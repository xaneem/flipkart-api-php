<?php
/**
 * PHP Wrapper for Flipkart API (unofficial)
 * GitHub: https://github.com/xaneem/flipkart-api-php
 * Demo: http://www.clusterdev.com/flipkart-api-demo
 * License: MIT License
 *
 * @author Saneem (@xaneem, xaneem@gmail.com)
 * @version 0.5
 */

namespace clusterdev;

class Flipkart
{
	//Affiliate ID and token are entered through the constructor
    private $affiliateId;
    private $token;
    private $response_type;

    private $api_base = 'https://affiliate-api.flipkart.net/affiliate/api/';
    private $verify_ssl   = false;

    /**
     * Obtains the values for required variables during initialization
     * @param string $affiliateId Your affiliate id.
     * @param string $token Access token for the API.
     * @param string $response_type Can be json/xml.
     * @return void
     **/
    function __construct($affiliateId, $token, $response_type="json")
    {
        $this->affiliateId = $affiliateId;
        $this->token = $token;
        $this->response_type = $response_type;

        //Add the affiliateId and response_type to the base URL to complete it.
        $this->api_base.= $this->affiliateId.'.'.$this->response_type;
    }

    /**
     * Calls the API directory page and returns the response.
     *
     * @return string Response from the API
     **/
    public function api_home(){
        return $this->sendRequest($this->api_base);
    }

    /**
     * Used to call URLs that are taken from the API directory.
     * Any change in the URL makes it invalid and the API refuses to respond.
     * The URLs have a timeout of ~4 hours, after which a new URL is to be 
     * taken from the API homepage.
     *
     * @return string Response from the API
     **/
    public function call_url($url){
        return $this->sendRequest($url);
    }

    /**
     * Sends the HTTP request using cURL.
     * 
     * @param string $url The URL for the API
	 * @param int $timeout Timeout before the request is cancelled.
     * @return string Response from the API
     **/
    private function sendRequest($url, $timeout=30){
    	//Make sure cURL is available
    	if (function_exists('curl_init') && function_exists('curl_setopt')){
	        //The headers are required for authentication
	        $headers = array(
	            'Cache-Control: no-cache',
	            'Fk-Affiliate-Id: '.$this->affiliateId,
	            'Fk-Affiliate-Token: '.$this->token
	            );

	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-ClusterDev-Flipkart/0.1');
	        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        $result = curl_exec($ch);
	        curl_close($ch);

	        return $result ? $result : false;
	    }else{
            //Cannot work without cURL
			return false;
	    }        
    }
}