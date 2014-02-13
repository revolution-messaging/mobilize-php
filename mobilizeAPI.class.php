<?php
use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

interface model				{
	public function isDirty		();
	public function setVariable	($name, $val);
	public function setVariables	($set);
	public function getVariable	($name);
	public function getVariables	();
	public function	getOptions	($name);
}

interface object			{
	public function delete          ($session,$version);
	public function retrieve        ($id, $session,$version);
	public function update          ($session,$version);
	public function create          ($session,$version);
}

class platformObject implements object	{
	
	public function delete 		($session,$version='v1'){
		if (!isset($this->urls['delete'])){
			throw new Exception('object cannot be deleted');
		}else{
			$request	=	$session()->delete($this->urls[$version]['create'].'/'.$id,null,json_encode($this->scheme->getVariables()));
			$response	=	$request->send();
			$this->scheme->isDirty	=	true;
		}
		//return statusCode & content
	}
	
	public function retrieve	($id,$session,$version='v1'){ //complete
		if (!isset($this->urls['v1']['retrieve'])){
			throw new Exception('object cannot be retrieved');
		}else{
			$request	=	$session()->get($this->urls[$version]['create'].'/'.$id);
			$response	=	$request->send();
			if($response->getStatusCode() < 400){
				$data = json_decode($response->getBody(),true);
				$this->scheme->setVariables($data);
				$this->scheme->isDirty	=	false;
				return $this;
			}else{
				throw new Exception($response->getStatusCode().': '.$response->getBody());
				return false;
			}
		}
	}
	
	public function update		($session,$version='v1'){
		$this->isDirty	=	false;
		if (!isset($this->urls['update'])){
			throw new Exception('object cannot be updated');
		}else{
			$request	=	$session()->put($this->urls[$version]['create'].'/'.$id,null,json_encode($this->scheme->getVariables()));
			$response	=	$request->send();
			$this->scheme->isDirty	=	false;
		}
		//return statusCode & content
	}
	
	public function create		($session,$version='v1'){
		$this->isDirty	=	false;
		if (!isset($this->urls['create'])){
			throw new Exception('object cannot be created');
		}else{
			$request	=	$session()->post($this->urls[$version]['create'],null,json_encode($this->scheme->getVariables()));
			$response	=	$request->send();
			$this->scheme->isDirty	=	false;
		}
		//return statusCode & content
	}
}

class subscriber extends platformObject{
	public $scheme		=	null;
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'v1/subscriber',
			),
		'v2'		=>	array(
			)
		);
	public function __construct($model=array()){
		$this->scheme		=	new scheme_subscriber;
		if(!empty($model)){
			foreach($model as $variable => $value){
				$this->scheme->setVariable($variable,$value);
			}
		}
	}
}

class metadata extends platformObject	{
	public $scheme		=	null;
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'v1/metadata',
			'create'	=>	'v1/metadata',
			'update'	=>	'v1/metadata',
			'delete'	=>	'v1/metadata'
			),
		'v2'		=>	array(
			)
		);
	public function __construct($model=array()){
		$this->scheme		=	new scheme_metadata;
		if(!empty($model)){
			foreach($model as $variable => $value){
				$this->scheme->setVariable($variable,$value);
			}
		}
	}
}

class filter extends platformObject	{  
	public $scheme		=	null;
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'v1/filter',
			'create'	=>	'v1/filter',
			'update'	=>	'v1/filter',
			'delete'	=>	'v1/filter'
			),
		'v2'		=>	array(
			'create'	=>	'',
			'update'	=>	''
			)
		);
	public function __construct($model=array()){
		$this->scheme		=	new scheme_filter;
		if(!empty($model)){
			foreach($model as $variable => $value){
				$this->scheme->setVariable($variable,$value);
			}
		}
	}
}

class authentication extends platformObject	{
	public $client		=	null;
	public $scheme		=	null;
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'v1/authenticate/whoami',
			'create'	=>	'v1/authenticate',
			'delete'	=>	'v1/authenticate/logout'
			),
		'v2'		=>	array(
			'create'	=>	'',
			'update'	=>	''
			)
		);
	
	public function create		($session=null,$version='v1'){
		$request	=	$this->client->post($this->urls[$version]['create'],null,json_encode($this->scheme->getVariables()));
		$response	=	$request->send();
	}
	
	public function retrieve	($id=null,$session=null,$version='v1'){ //complete
		if (!isset($this->urls['v1']['retrieve'])){
			throw new Exception('object cannot be retrieved');
		}else{
			$request	=	$this->client->get($this->urls[$version]['retrieve']);
			$response	=	$request->send();
			if($response->getStatusCode() < 400){
				return json_decode($response->getBody(),true);
			}else{
				throw new Exception($response->getStatusCode().': '.$response->getBody());
				return false;
			}
		}
	}
	
	public function __invoke(){
		if(!isset($this->client) || empty($this->client)){
			throw new Exception('No session active. Reauthenticate.');
		}else{
			return($this->client);
		}
	}
	
	public function __construct($model=array()){
		$this->scheme		=	new scheme_authentication;
		if(!empty($model) && isset($model)){
			$this->scheme->setVariables($model);
		}
		$this->client	=	new Client('http://revolutionmsg.com/api',array(
			'request.options'	=>	array(
				'headers'	=>	array(
					'Accept'	=>	'application/json',
					'Content-Type'	=>	'application/json'
					)
				)
			)
		);
		$this->client->setUserAgent('revolutionmessaging/mobilize-php');
		$cookiePlugin = new CookiePlugin(new ArrayCookieJar());
		$this->client->addSubscriber($cookiePlugin);
	}
	
}

//MODELS
class scheme implements model	{
	public  $isDirty		=	false;
	
	public function isDirty(){
		return $this->isDirty;
	}
	
	public function setVariable	($name, $val){
		if(key_exists($name,$this->vars) && (!key_exists($name,$this->options) || in_array($val,$this->options[$name]))){
			$this->vars[$name] = $val;
			$this->isDirty = true;
			return true;
		}else{
			return false;
		}
	}
	
	public function setVariables ($set){
		foreach($set as $name => $value){
			$this->setVariable($name,$value);
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
class	scheme_authentication	extends scheme{
	protected $options	=	array();
	protected $vars		=	array(
		'username'	=>	null,
		'password'	=>	null
		);
}
class	scheme_metadata extends scheme{
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
class	scheme_filter extends scheme{
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
class	scheme_subscriber extends scheme{
	protected $options	=	array();
	protected $vars		=	array();
}


class scheme_metadataValue extends scheme	{
	protected $options	=	array(
		
		);
	protected $vars		=	array(
		'id' 		=>	null,
		'name' 		=>	null,
		'value' 	=>	null
		);


}

class scheme_filterDetail extends scheme	{  
	protected $options	=	array(); //needs options
	protected $vars			=	array( 
		'value'			=>	null,
		'operator'		=>	null,
		'metadata'		=>	null
		);


}

class scheme_impersonate extends scheme		{
	protected $options	=	array();
	protected $vars		=	array(
		'user'		=>	null
		);
}
?>
