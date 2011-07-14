<?php 
/**
 * This is a pet project of making some classes that interact with the Pardot API.
 * @author stephenreid
 * @since 7/13/2011
 *
 */
class Prospect
{
	private $conn = null;
	private $data = array();
	
	/**
	 * Prospect()
	 * Constructs our prospect object
	 * This would typically be called $prospect = new Prospect();
	 * Can be called $prospect = new Prospect(array('email'=>'test@test.com",'first_name'=>Stephen)); to create a prospect
	 * Or can populate a prospect from Pardot by $prospect = new Prospect('test@test.com');
	 * @param unknown_type $arr
	 */
	public function __construct($arr = null)
	{
		if (strpos($arr,'@')){//assume it's an email and fetch the prospect
			//authenticate
			$conn = new PardotConnector();
			$conn->authenticate($username, $password, $userKey);
			
			$this->conn = $conn;
			//query
			$p = $conn->getProspectByEmail($arr);
			//localize
			foreach($p->children() as $val){
				$var = $val->getName();
				$this->$var=$val;
			}
		} elseif (is_array($arr)) { // they passed us all the variables for a prospect
			foreach ($arr as $key => $value){
				$this->$key=$value;
			}
		}
	}
	/**
	 * save()
	 * Uses the current $data arr 
	 * and stores the object to pardot
	 */
	public function save(){
		//authenticate
		$conn = $this->conn;
		if (!$conn){
			$conn = new PardotConnector();
			$conn->authenticate($username, $password, $userKey);
			$this->conn = $conn;
		}
		//upsert
		$conn->upsertProspect($data);
	}
	
	//These are all magic methods
	public function __set($name,$value)
	{
		$this->data[$name]=$value;
	}
	public function __get($name)
	{
		if (isset($this->data[$name])){
			return $this->data[$name];
		}
		return null;
	}
	public function __isset($name)
	{
		return isset($this->data[$name]);
	}
}
?>