<?php
/**
 * This is a basic class for connecting to the Pardot API
 * The important parts here are the authenticate, send, and various prospect functions
 * Check out Prospect.class.php as a way to manipulate prospects.
 * @author stephenreid
 * @since 7/14/2011
 * @desc A connecting class to the pardot api
 */
class PardotConnector
{
	//A flag for echoing debug output
	private $debug 		= false;
	private $apiKey		= null;
	
	private $email = '####';
	private $password = '####';
	private $userKey = '####';
	

	public function __construct()
	{
	}
	public function authenticate($username=null,$password=null,$userKey=null){
		//gets a user api key back
		if(false && $username){
			$params = array('email'=>$username,'password'=>$password,'user_key'=>$userKey);
		} else {
			$params = array('email'=>$this->email,'password'=>$this->password,'user_key'=>$this->userKey);
		}
		$ret = $this->send('login','',$params);
		
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
	public function assignProspect($prospectIdentifier,$userIdentifier){
		$params = array();
		if (false && strpos($prospectIdentifier,'@')){//not available via assign
			//this is an email address
			$params['email']=$prospectIdentifier;
		} else {
			$params['id']=$prospectIdentifier;
		}
		if (false && strpos($userIdentifier,'@')){//not available via assign
			//this is an email address
			$params['user_email']=$prospectIdentifier;
		} else {
			$params['user_id']=$userIdentifier;
		}
		$ret = $this->send('prospect','assign',$params);
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
		$ret = simplexml_load_string($res);
		if ($ret->err){
			throw new Exception($ret->err.' '.$url.' '.http_build_query($parameters), '1');
		}
		return $ret;
	}
}?>
