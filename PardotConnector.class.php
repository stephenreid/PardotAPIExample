<?php
class PardotConnector
{
	//A flag for echoing debug output
	private $debug = false;
	private $userKey;
	private $apiKey;

	public function __construct()
	{
	}
	public function authenticate($username,$password,$userKey){
		//gets a user api key back
		$this->userKey= $userKey;
		$ret = $this->send('login','',array($username,$password,$userKey));
		$this->apiKey=$ret->api_key;//add error handling to this later
		return $ret;
	}
	/**
	 * 
	 * Creates a prospect in Pardot
	 * @param unknown_type $arr
	 * $arr contaisn an array of fields
	 * Requieres at least email=>test@test.com
	 * see for more : http://developer.pardot.com/kb/api-version-3/using-prospects
	 */
	public function createProspect($arr){
		$ret = $this->send('prospect','create',$arr);
		return $ret;
	}
	public function getProspectById($id){
		$ret = $this->send('prospect','read',array('id'=>$id));
		return $ret;
	}
	public function getProspectById($email){
		$ret = $this->send('prospect','read',array('email'=>$email));
		return $ret;
	}
	public function updateProspect($identifier,$arr){
		//is this an email or an id
		if (strpos($identifier,'@')){
			//this is an email address
		} // else it's an id
		$ret = $this->send('prospect','update',array('email'=>$email));
		
		
		
	}

	public function 
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
				//add a timeout here
			)	
		));
		$res = file_get_contents($url,false,$context);
		
		return simplexml_load_string($res);
	}
}?>
