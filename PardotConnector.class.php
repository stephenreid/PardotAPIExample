<?php
/**
 * I / Pardot make no guarantees as to the accuracy of this document. 
 * As you are free to use this, it also comes with no additional support.
 * If you do have questions for support, please email them the actual query (url + parameters)
 * that you are trying to make and why the results are not working for you
 * WE WILL NOT DEBUG YOUR CODE
 * WE CAN NOT MAKE ESTIMATES ON BUILDING ON THIS CODE
 * 
 * @author stephenreid
 * @since 7/14/2011
 * @desc A connecting class to the pardot api
 */
class PardotConnector
{
	//A flag for echoing debug output
	private $debug 		= false;
	private $userKey	= null;
	private $apiKey		= null;

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
	 * Requires at least email=>test@test.com
	 * see for more : http://developer.pardot.com/kb/api-version-3/using-prospects
	 */
	public function createProspect($arr){
		$ret = $this->send('prospect','create',$arr);
		return $ret;
	}
	/**
	 * upsertProspect
	 * insert a prospect if not exist, otherwise update
	 * @param unknown_type $arr
	 * Requires at least an email=>test@test.com <field>=<fieldVale>
	 */
	public function upsertProspect($arr){
		$ret = $this->send('prospect','upsert',$arr);
		return $ret;
	}
	public function getProspectById($id){
		$ret = $this->send('prospect','read',array('id'=>$id));
		return $ret;
	}
	public function getProspectByEmail($email){
		$ret = $this->send('prospect','read',array('email'=>$email));
		return $ret;
	}
	public function updateProspect($identifier,$arr){
		//is this an email or an id
		$login = array('id'=>$identifier);//poorly structured else
		if (strpos($identifier,'@')){
			//this is an email address
			$login = array('email'=>$identifier);
		}
		$ret = $this->send('prospect','update',array_merge($login,$arr));
		return $ret;
	}
	/**
	 * Send
	 * @desc Sends a web request to the api
	 * @param
	 */
	private function send($module = 'prospect',$action = 'query',$parameters = array()){
		$baseurl = 'https://pi.pardot.com/api/';
		$version = 'version/3/';
		
		if ($this->apiKey && $this->userKey){
			$login = array('api_key'=>$this->apiKey,'user_key'=>$this->userKey);
			$parameters = array_merge($login,$parameters);
			$version.='do/';
		}
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
