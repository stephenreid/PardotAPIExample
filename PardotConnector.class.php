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
	private $outputMode	= 'full'; // choose between 'simple','full','mobile'

	private $email = '';
	private $password = '';
	private $userKey = '';


	/**
	 * __construct PardotConnector()
	 * Dummy Constructor, Run authenticate() to be able to do anything
	 */
	public function __construct()
	{
	}

	public function authenticate($username=null,$password=null,$userKey=null){
		//gets a user api key back
		if($username!=null){
			$params = array('email'=>$username,'password'=>$password,'user_key'=>$userKey);
			
			//store the username and password so that we can catch api expired exceptions in the future
			$this->email = $username;
			$this->password = $password;

			//have to store this for future requests
			$this->userKey = $userkey;
		} else {
			$params = array('email'=>$this->email,'password'=>$this->password,'user_key'=>$this->userKey);
		}
		$ret = $this->send('login','',$params);

		$this->apiKey=$ret->api_key;//add error handling to this later
		return $ret;
	}
	/**
	 * accountRead
	 * Reads the properties of an account
	 * @param unknown_type $identifier Null, accounts are one per login
	 * @return SimpleXMLElement Pardot Account
	 */
	public function accountRead($identifier=null)
	{
		$ret = $this->send('account','read',array());
		return $ret;
	}
	/**
	 * campaignCreate
	 * Create a campaign
	 * @param array $params array('name','cost')
	 */
	public function campaignCreate($params)
	{
		$ret = $this->send('campaign','create',$params);
		return $ret;
	}
	/**
	 * campaigQuery
	 * Get a list of campaigns
	 * @param array $params
	 */
	public function campaignQuery($params=array())
	{
		$ret = $this->send('campaign','query',$params);
		return $ret;
	}
	/**
	 * campaignRead
	 * Read the details of a campaign
	 * @param unknown_type $identifier
	 */
	public function campaignRead($identifier)
	{
		$ret = $this->send('campaign','read',array('id'=>$identifier));
		return $ret;
	}
	/**
	 * campaignUpdate
	 * Update the cost or name of a campaign, based on the id.
	 * @param unknown_type $identifier
	 * @param unknown_type $params Campaign Name and Cost
	 * @return SimpleXMLElement Pardot Campaign
	 */
	public function campaignUpdate($identifier,$params)
	{
		$params = array_merge(array('id'=>$identifier),$params);
		$ret = $this->send('campaign','update',$params);
		return $ret;
	}
	public function customFieldQuery($params)
	{
		$ret = $this->send('customField','query',$params);
		return $ret;
	}
	public function customFieldRead($identifier)
	{
		$ret = $this->send('customField','create',array('id'=>$identifier));
		return $ret;
	}
	public function customRedirectQuery($queryParameters=array())
	{
		$ret = $this->send('customRedirect','query',$queryParameters);
		return $ret;
	}
	public function customRedirectRead($identifier)
	{
		$ret = $this->send('customRedirect','read',array('id'=>$identifier));
		return $ret;
	}
	/**
	 * formQuery
	 * Query form forms in this account
	 * @param unknown_type $parameters
	 */
	public function formQuery($parameters=array())
	{
		$ret = $this->send('form','query',$parameters);
		return $ret;
	}
	/**
	 * formRead
	 * Read the details of a Pardot Form
	 * Especially its embed code
	 * @param unknown_type $identifier
	 */
	public function formRead($identifier)
	{
		$ret = $this->send('form','read',array('id'=>$identifier));
		return $ret;
	}
	public function listCreate($parameters)
	{
		$ret = $this->send('list','create',$params);
		return $ret;
	}
	public function listQuery($queryParameters=array())
	{
		$ret = $this->send('list','create',$queryParameters);
		return $ret;
	}
	public function listRead($identifier)
	{
		$ret = $this->send('campaign','create',array('id'=>$identifier));
		return $ret;
	}
	public function listUpdate($identifier,$parameters)
	{
		$params = array_merge(array('id'=>$identifier),$parameters);
		$ret = $this->send('campaign','create',$params);
		return $ret;
	}
	public function opportunityCreate($params)
	{
		$ret = $this->send('opportunity','create',$params);
		return $ret;
	}
	public function opportunityQuery($queryParameters=array())
	{
		$ret = $this->send('opportunity','query',$queryParameters);
		return $ret;
	}
	public function opportunityRead($identifier)
	{
		$params = array('id'=>$identifier);
		$ret = $this->send('opportunity','read',$params);
		return $ret;
	}
	public function opportunityUpdate($identifier,$params)
	{
		$params = array_merge(array('id'=>$identifier),$params);
		$ret = $this->send('campaign','create',$params);
		return $ret;
	}

	/**
	 * prospectAssign
	 * Enter description here ...
	 * @param unknown_type $prospectIdentifier
	 * @param unknown_type $userIdentifier
	 */
	public function prospectAssign($prospectIdentifier,$userIdentifier)
	{
		$params = array();
		if (strpos($prospectIdentifier,'@')){//not available via assign
			//this is an email address
			$params['email']=$prospectIdentifier;
		} else {
			$params['id']=$prospectIdentifier;
		}
		if (strpos($userIdentifier,'@')){//not available via assign
			//this is an email address
			$params['user_email']=$prospectIdentifier;
		} else {
			$params['user_id']=$userIdentifier;
		}
		$ret = $this->send('prospect','assign',$params);
		return $ret;

	}
	/**
	 * prospectCreate
	 * Creates a prospect in Pardot
	 * @param array $arr an array of key=>values
	 * @return SimpleXMLElement Pardot Prospect
	 * Requires at least email=>test@test.com
	 * see for more : http://developer.pardot.com/kb/api-version-3/using-prospects
	 */
	public function prospectCreate($arr){
		$ret = $this->send('prospect','create',$arr);
		return $ret;
	}
	/**
	 * prospectGetByEmail
	 * Fetches a prospect by email
	 * @param str $email
	 * @return SimpleXMLElement A Pardot Prospect
	 */
	public function prospectGetByEmail($email){
		$ret = $this->send('prospect','read',array('email'=>$email));
		return $ret;
	}
	/**
	 * prospectGetById
	 * Fetches a prospect by id
	 * @param int $id
	 * @return SimpleXMLElement A Pardot prospect
	 */
	public function prospectGetById($id){
		$ret = $this->send('prospect','read',array('id'=>$id));
		return $ret;
	}
	/**
	 * prospectQuery
	 * Enter description here ...
	 * @param array $params Key Value Store ie Updated_After, Created_After
	 * @return SimpleXMLElement A List of Prospects
	 */
	public function prospectQuery($params = array())
	{
		$ret = $this->send('prospect','query',$params);
	}
	/**
	 * prospectUpdate
	 * Updtes a prospect
	 * @param str $identifier An email or integer to refer to the prospect
	 * @param array $arr array of keys and values to update the prospect
	 * @return SimpleXMLElement The Updated Prospect Record in Pardot
	 */
	public function prospectUpdate($identifier,$arr){
		//is this an email or an id
		$by = array('id'=>$identifier);//poorly structured else
		if (strpos($identifier,'@')){
			//this is an email address
			$by = array('email'=>$identifier);
		}
		$ret = $this->send('prospect','update',array_merge($by,$arr));
		return $ret;
	}
	/**
	 * prospectUpsert
	 * insert a prospect if not exist, otherwise update
	 * @param array $arr
	 * @return SimpleXMLElement Pardot Prospect
	 * Requires at least an email=>test@test.com <field>=<fieldVale>
	 */
	public function prospectUpsert($arr){
		$ret = $this->send('prospect','upsert',$arr);
		return $ret;
	}
	public function userQuery($queryParameters)
	{
		$ret = $this->send('user','query',$queryParameters);
		return $ret;
	}
	/**
	 * userRead
	 * Read the details about a user based on an identifier
	 * @param unknown_type $identifier Pardot User Id or email address
	 */
	public function userRead($identifier)
	{
		if(strpos($identifier,'@')){
			$params = array('email'=>$identifier);
		} else {
			$params = array('id'=>$identifier);
		}
		$ret = $this->send('user','read',$params);
		return $ret;
	}
	public function visitorActivityQuery($queryParameters=array()){
		$ret = $this->send('visitorActivity','query',$queryParameters);
		return $ret;
	}
	public function visitorActivityRead($identifier){
		$ret = $this->send('visitorActivity','read',array('id'=>$identifier));
		return $ret;
	}
	/**
	 * visitorAssign
	 * Assigns a visitor to a prospect record
	 * @param unknown_type $prospectIdentifier Email or Prospect Id
	 * @return SimpleXMLElement Pardot Visitors
	 */
	public function visitorAssign($prospectIdentifier){
		if(strpos($prospectIdentifier,'@')){
			$params = array('prospect_email'=>$prospectIdentifier);
		} else {
			$params = array('prospect_id'=>$prospectIdentifier);
		}
		$ret = $this->send('visitor','assign',$params);
		return $ret;
	}
	public function visitorQuery($queryParameters){
		$ret = $this->send('visitor','query',$queryParameters);
		return $ret;
	}
	/**
	 * visitorRead
	 * Reads the details about the visitors
	 * @param unknown_type $identifier Pardot Visitor Id
	 */
	public function visitorRead($identifier)
	{
		$ret = $this->send('visitor','read',array('id'=>$identifier));
		return $ret;
	}
	public function visitQuery($queryParameters=array()){
		$ret = $this->send('visit','query',$queryParameters);
		return $ret;
	}
	/**
	 * visitRead
	 * Tell the details about a visit. Which pages were views, etc.
	 * @param unknown_type $identifier Pardot Visit Id
	 */
	public function visitRead($identifier){
		$ret = $this->send('visit','read',array('id'=>$identifier));
		return $ret;
	}
	/**
	 * Send
	 * @desc Sends a web request to the api
	 * @param string $module
	 * @param string $action
	 * @param array $parameters A Key Value Store of Parameters
	 * @return SimpleXMLElement Response from Server
	 * @throws Exception
	 */
	private function send($module = 'prospect',$action = 'query',$parameters = array()){
		$baseurl = 'https://pi.pardot.com/api/';
		$version = 'version/3/';

		if ($this->apiKey && $this->userKey){
			$login = array('api_key'=>$this->apiKey,'user_key'=>$this->userKey);
			$parameters = array_merge($login,$parameters);
			$version.='do/';
		}
		if($this->outputMode){
			$output = array('output'=>$this->outputMode);
			$parameters = array_merge($output,$parameters);
		}

		$url = $baseurl.$module.'/'.$version.$action.'?'.
		$context = stream_context_create(array(
			'http'	=> array(
				'method'	=> 'POST',//never want to send credentials over GET
				'header'	=> 'Content-type: application/x-www-form-urlencoded',
				'content'	=> http_build_query($parameters),
				'timeout'	=> 30.0, //in seconds
				'user_agent'=> 'PardotPHPClient',
				//'proxy'		=> '',
				//'ignore_errors'	=> false,
			)
		));
		$res = file_get_contents($url,false,$context);
		$ret = simplexml_load_string($res);
		if ($ret->err){
			throw new PardotConnectorException($ret->err.' '.$url.' '.http_build_query($parameters), '1');
		}
		if ($ret->result){//This is for all of our queries
			return $ret->result;
		} else if ($ret->$module){//This is for all of our CRUD functions
			return $ret->$module;
		}
		return $ret;
	}
}
class PardotConnectorException extends Exception{

	public function __construct($message='',$code=1){
		parent::__construct($message, $code);
	}
}?>
