<?php

interface model {
	public function setVariable	($name, $val);
	public function getVariable	($name);
	public function getVariables	();
	public function	getOptions	($name);
}

class platformObject implements model	{
	protected $isDirty	=	true;
	
	public function delete 		(){
		$this->isDirty	=	false;
	}
	
	public function retrieve	($id){
		$this->isDirty	=	false;
	}
	
	public function update		(){
		$this->isDirty	=	false;
	}
	
	public function create		(){
		$this->isDirty	=	false;
	}
	
	public function isDirty		(){
		return $this->isDirty;
	}
	
	public function setVariable	($name, $val){
		if(key_exists($name,$this->vars) && (!key_exists($name,$this->options) || in_array($val,$this->options[$name]))){
			if($name != 'id')
			$this->vars[$name] = $val;
			return true;
		}else{
			return false;
		}
	}
	
	public function getVariable	($name){
		if (key_exists($name,$this->vars)){
			return $this->vars[$name];
		}
	}
	
	public function getVariables	(){
		return $this->vars;
	}
	
	public function	getOptions	($name){
		if(key_exists($name,$this->options)){
			return $this->options[$name];
		}
	}
	
	public function __construct($model=array()){
		if(!empty($model)){
			foreach($model as $variable => $value){
				$this->setVariable($variable,$value);
			}
		}else{
			$this->isDirty = false;
		}
	}
}

class subscriber extends platformObject{
	protected $options	=	array();
	protected $vars		=	array();
}

class metadata extends platformObject	{
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

class filter extends platformObject	{  
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

class filterDetail implements model	{  
	protected $options	=	array(); //needs options
	protected $vars			=	array( 
		'value'			=>	null,
		'operator'		=>	null,
		'metadata'		=>	null
		);
	public function setVariable	($name, $val){
		if(key_exists($name,$this->vars) && (!key_exists($name,$this->options) || in_array($value,$this->options[$name]))){
			$this->vars[$name] = $val;
			return true;
		}else{
			return false;
		}
	}
	
	public function getVariable	($name){
		if (key_exists($name,$this->vars)){
			return $this->vars[$name];
		}
	}
	
	public function getVariables	(){
		return $this->vars;
	}
	
	public function	getOptions	($name){
		if(key_exists($name,$this->options)){
			return $this->options[$name];
		}
	}

}
/*
class authentication extends platformObject	{
	protected $u			=	null;
	protected $p			=	null;
	public function __construct ($u=null,$p=null){
		$this->u = $u;
		$this->p = $p;
		if ($response = parent::client('/v1/authenticate',array(
			'headers' => array(),
			
	}
}

class MobilizeAPI {
	protected $client	=	new Client('http://revolutionmsg.com');
	protected $subscriber	=	null;
	public function __construct	($u=null,$p=null){
		$auth = new authentication($u,$p);
	}
}
*/
?>
