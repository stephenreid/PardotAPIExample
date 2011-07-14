<?php 
/**
 * This is a pet project of making some classes that interact with the Pardot API.
 * @author stephenreid
 * @since 7/13/2011
 * 
 * I / Pardot make no guarantees as to the accuracy of this document.
 *  As you are free to use this, it also comes with no additional support.
 * If you do have questions for support, please email them the actual query (url + parameters)
 * that you are trying to make and why the results are not working for you
 * WE WILL NOT DEBUG YOUR CODE
 * WE CAN NOT MAKE ESTIMATES ON BUILDING ON THIS CODE
 * 
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
	 * Can be called $prospect = new Prospect(array('email'=>'test@test.com",'first_name'=>'Stephen')); to create a prospect
	 * @param unknown_type $arr
	 */
	public function __construct($arr = null)
	{
		if (is_array($arr)) { // they passed us all the variables for a prospect
			foreach ($arr as $key => $value){
				$this->$key=$value;
			}
		}
		//else we have a blank slate
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
	/**
	 * fetcProspectByEmail
	 * @desc Fetches a prospect from the Pardot API and returns a Prospect object;
	 * @param Str $email
	 * Ie: $prospect = prospect::fetchProspectByEmail('testProspect@pardot.com');
	 * $prospect->company = 'New Company Value';
	 * $prospect->save();
	 */
	public static function fetchProspectByEmail($email)
	{
			//authenticate
			$conn = new PardotConnector();
			$conn->authenticate($username, $password, $userKey);
			//query
			$p = $conn->getProspectByEmail($arr);
			
			//localize
			$prospect = new Prospect();//initialize one of my own
			foreach($p->children() as $val){
				$var = $val->getName();
				$prospect->$var=$val;
			}
			return $prospect;
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