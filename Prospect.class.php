<?php
/**
 * This is a pet project of making some classes that interact with the Pardot API.
 * @author stephenreid
 * @since 7/13/2011
 *
 *
 */
class Prospect
{
	private $connection = null;
	//this stores our auto getters and setters.
	private $data = array();
	//a cached version of wht we go back to only save what has changed.
	private $originalData = array();

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
	public function getConnection(){
		$connection = $this->connection;
		if ($connection===null){
			$connection = new PardotConnector();
			$connection->authenticate();
			$this->connection = $connection;
		}
		return $connection;
	}
	/**
	 * save()
	 * Uses the current $data arr
	 * and stores the object to pardot
	 */
	public function save(){
		//get our changeset
		$changes = array_diff_assoc($this->data,$this->originalData);
		if(!count($changes)){
			//if there are no changes then don't commit anything
			return;
		}
		if(count($this->originalData)){//use an identifier if it already existed
			$changes = array_merge(array('id'=>$this->originalData['id']),$changes);
		}
		//authenticate
		$connection = $this->getConnection();
		//upsert
		$connection->prospectUpsert($changes);
		//update what our original data is
		$this->originalData = $this->data;
	}
	/**
	 * fetchProspectByEmail
	 * @desc Fetches a prospect from the Pardot API and returns a Prospect object;
	 * @param Str $email
	 * Ie: $prospect = prospect::fetchProspectByEmail('testProspect@pardot.com');
	 * $prospect->company = 'New Company Value';
	 * $prospect->save();
	 */
	public function fetchProspectByEmail($email)
	{
			//authenticate
			$conn = $this->getConnection();
			//query
			$p = $conn->prospectGetByEmail($email);

			//localize
			$prospect = new Prospect();//initialize one of my own
			$data = json_decode(json_encode($p),true);//convert everything to std
			//store our previous cache
			$this->originalData = $data;
			//store our modifiable array;
			$this->data = $data;

			return $this;
	}
	/**
	*setAssignedUser
	*assigns this prospect to a user based on email or id
	*@param userIdentifier (an email address or an id)
	*/
	public function setAssignedUser($userIdentifier)
	{
		$connection = $this->getConnection();
		$connection->assignProspect($this->id,$userIdentifier);
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