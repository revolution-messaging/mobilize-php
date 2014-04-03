<?php
namespace revmsg\mobilize\model;
interface model				{
	public function setVariable	($name, $val);
	public function setVariables	($set);
	public function getVariable	($name);
	public function getVariables	();
	public function	getOptions	($name);
}

//MODELS
class scheme implements model	{
	protected  $isDirty		=	false;
	
	public function isDirty(){
		return $this->isDirty;
	}
	
	public function setVariable	($name, $val){
		if(key_exists($name,$this-> vars) && (!key_exists($name,$this-> options) || in_array($val,$this-> options[$name]))){
			$this-> vars[$name] = $val;
			$this-> isDirty = true;
			return true;
		}else{
			return false;
		}
	}
	
	public function setVariables ($set){
		foreach($set as $name => $value){
			$this-> setVariable($name,$value);
		}
	}
	                                                                 
	public function getVariable	($name){
		if (key_exists($name,$this-> vars)){
			return $this-> vars[$name];
		}
	}
	
	public function getVariables	(){
		return $this-> vars;
	}
	
	public function	getOptions	($name){
		if(key_exists($name,$this-> options)){
			return $this-> options[$name];
		}
	}
}

class	impersonate extends scheme {
	protected $options	=	array();
	protected $vars		=	array(
		'id'			=>	null,
		'name'			=>	null
		);
}

class	sublist extends scheme	{
	protected $options	=	array();
	protected $vars		=	array(
		'id'			=>	null,
		'shortCode'		=>	null,
		'createdBy'		=>	null,
		'status'		=>	null,
		'name'			=>	null,
		'account'		=>	null,
		'group'			=>	null,
		'noOfSubscribers'	=>	null
		);
}

class	authentication extends scheme{
	protected $options	=	array();
	protected $vars		=	array(
		'username'	=>	null,
		'password'	=>	null
		);
}
class	metadata extends scheme{
	protected $options	=	array(
		'status'	=>	array(
			'ACTIVE',
			'INACTIVE'
			),
		'multiValue'	=>	array(
			true,
			false
			)
		);
	protected $vars		=	array(
		'id'		=>	null,
		'validValues'	=>	array(),
		'scope'		=>	'group',
		'status'	=>	'ACTIVE',
		'name'		=>	null,
		'eventUrl'	=>	null,
		'account'	=>	null,
		'multiValue'	=>	false,
		'format'	=>	null,
		'group'		=>	null  
	);
}
class	filter extends scheme{
	protected $options	=	array(); //needs options
	protected $vars		=	array(
		'id'			=>	null,
		'queryFilterDetails'	=>	null,
		'shortCode'		=>	null,
		'name'			=>	null,
		'account'		=>	null,
		'group'			=>	null,
		'lists'			=>	null
		);
}
class	subscriber extends scheme{
	protected $options	=	array();
	protected $vars		=	array(
		'id'			=>	null,
		'blacklist'		=>	array(),
		'mobilePhoneNo'		=>	null,
		'subscriberMetaData'	=>	array(),
		'listDetails'		=>	array()
		);
}


class metadataValue extends scheme	{
	protected $options	=	array(
		
		);
	protected $vars		=	array(
		'id' 		=>	null,
		'name' 		=>	null,
		'value' 	=>	null
		);


}

class filterDetail extends scheme	{  
	protected $options	=	array(); //needs options
	protected $vars			=	array( 
		'value'			=>	null,
		'operator'		=>	null,
		'metadata'		=>	null
		);


}

?>
