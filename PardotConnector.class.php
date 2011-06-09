<?php
class PardotConnector
{
	//A flag for echoing debug output
	private $debug = false;

	public function __construct()
	{
	}
	public function authenticate(){
		//gets a user api key back
		return $key;
	}
	/**
	 * Send
	 * @desc Sends a web request to the api
	 * @param
	 */
	private function send($module = 'prospect',$action = 'query',$parameters = array()){
		$baseurl = 'https://pi.pardot.com/api/';
		$version = 'version/3/do/';
		
		$url = $baseurl.$module.'/'.$version.$action.'?'.
		$context = stream_context_create(array(
			'http'	=> array(
				'method'	=> 'POST',
				'header'	=> 'Content-type: application/x-www-form-urlencoded',
				'content'	=> http_build_query($parameters),
			)	
		));
		$res = file_get_contents($url,false,$context);
		
		return simplexml_load_string($res);
	}
}?>
