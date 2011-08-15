<?php 
class SugarCrmConnector{
	
	private $sessionId 	= 0;
	private $debug 		= false;
	
	public function __construct(){
		
	}
	public function login($username='test',$password='test'){
		$login = $this->send('login',array(
			'user_auth'=>array(
				'username'=>$username,
				'password='.md5($password)
			),
			'application'=>'PardotApiExample'
			)
		);
		$this->sessionId = $login->id;
		return $login->id;
		return true;//this needs to check based on the value returned
		
	}
	public function getLeadByEmail($email='test@test.com'){
		$entryIds = $this->getEntryList('lead','email="'.$email.'"');
		$entryId = $entryIds['0'];
		$entries = $this->getEntriesByIds('leads',$entryId);
		$entry = $entries['0'];
	}
	/**
	 * getEntriesByIds
	 * @param $module
	 * @param $ids
	 * Using the batch method allows for more scaling at little cost
	 */
	private function getEntriesByIds($module='leads',$ids=array()){
		$data = array(
			'module'					=> $module,
			'ids'						=> $ids,
			//'select_fields'			=>,
			'link_name_to_fields_array'	=> array(
				array(
					'name' => 'email_addresses',
					'value' => array(
						'id',
						'email_address',
						'opt_out',
						'primary_address'
					)
				)
			)
		);
		$this->send('get_entries',$data);
	}
	private function getEntryList($module,$query){
		$query = array(
			'module_name'	=> $module,
			'query'			=> $query,
		);
		$entryList = $this->send('get_entry_list',$querys);
		$entryList->entry_list;
	}
	/**
	 * Send
	 * @desc Sends a web request to the api
	 * @param
	 */
	private function send($method = 'login',$data=array()){
		$baseurl = 'https://example.org/';
		$version = 'services/v2/rest.php';
		$url = $baseurl.$version;
		
		if ($this->sessionId!=0){
			$data['session']=$this->sessionId;
		}
		
		$parameters = array(
			'method'			=>$method,
			'response_type'		=>'json',
			'input_type'		=>'json',
			'rest_data'			=>json_encode($data),
		);
		$context = stream_context_create(array(
			'http'	=> array(
				'method'	=> 'POST',
				'header'	=> 'Content-type: application/x-www-form-urlencoded',
				'content'	=> http_build_query($parameters),
			)	
		));
		$res = file_get_contents($url,false,$context);
		return json_decode($res,false);//false to return an object
		
}
?>