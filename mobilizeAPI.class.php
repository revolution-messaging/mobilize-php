<?php
use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

interface model				{
	public function setVariable	($name, $val);
	public function getVariable	($name);
	public function getVariables	();
	public function	getOptions	($name);
}

interface object			{
	public function delete          ();
	public function retrieve        ($id);
	public function update          ();
	public function create          ();
}

class platformObject implements object	{
	protected $isDirty	=	true;
	public function delete 		(){
		$this->isDirty	=	false;
		if (!isset($this->urls['delete'])){
			//exception: object cannot be deleted;
		}else{
			//Guzzle request
		}
		//return statusCode & content
	}
	
	public function retrieve	($id){
		$this->isDirty	=	false;
		if (!isset($this->urls['retrieve'])){
			//exception: object cannot be deleted;
		}else{
			//Guzzle request
		}
		//return statusCode & content
	
	}
	
	public function update		(){
		$this->isDirty	=	false;
		if (!isset($this->urls['update'])){
			//exception: object cannot be deleted;
		}else{
			//Guzzle request
		}
		//return statusCode & content
	}
	
	public function create		(){
		$this->isDirty	=	false;
		if (!isset($this->urls['create'])){
			//exception: object cannot be deleted;
		}else{
			//Guzzle request
		}
		//return statusCode & content
	}
	
	public function isDirty		(){
		return $this->isDirty;
	}
}

class subscriber extends platformObject{
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'',
			'create'	=>	'',
			'update'	=>	'',
			'delete'	=>	''
			),
		'v2'		=>	array(
			)
		);
	public function __construct($model=array()){
		$this->$scheme		=	new scheme_subscriber;
		if(!empty($model)){
			foreach($model as $variable => $value){
				$this->scheme->setVariable($variable,$value);
			}
		}else{
			$this->isDirty = false;
		}
	}
}

class metadata extends platformObject	{
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'',
			'create'	=>	'',
			'update'	=>	'',
			'delete'	=>	''
			),
		'v2'		=>	array(
			)
		);
	public function __construct($model=array()){
		$this->$scheme		=	new scheme_metadata;
		if(!empty($model)){
			foreach($model as $variable => $value){
				$this->scheme->setVariable($variable,$value);
			}
		}else{
			$this->isDirty = false;
		}
	}
}

class filter extends platformObject	{  
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'',
			'create'	=>	'',
			'update'	=>	'',
			'delete'	=>	''
			),
		'v2'		=>	array(
			'create'	=>	'',
			'update'	=>	''
			)
		);
	public function __construct($model=array()){
		$this->$scheme		=	new scheme_filter;
		if(!empty($model)){
			foreach($model as $variable => $value){
				$this->scheme->setVariable($variable,$value);
			}
		}else{
			$this->isDirty = false;
		}
	}
}

class authentication extends platformObject	{
	public $client		=	null;
	public $scheme		=	null;
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'',
			'create'	=>	'v1/authenticate',
			'delete'	=>	''
			),
		'v2'		=>	array(
			'create'	=>	'',
			'update'	=>	''
			)
		);
	
	public function create		(){
		$request	=	$this->client->post($this->urls['v1']['create'],null,json_encode($this->scheme->getVariables()));
		$response	=	$request->send();
	}
	
	public function __construct($model=array()){
		$this->scheme		=	new scheme_authentication;
		if(!empty($model) && isset($model)){
			foreach($model as $variable => $value){
				$this->scheme->setVariable($variable,$value);
			}
		}else{
			$this->isDirty = false;
		}
		$this->client	=	new Client('http://revolutionmsg.com/api',array(
			'request.options'	=>	array(
				'headers'	=>	array(
					'Accept-Type'	=>	'application/json',
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

/*class authentication extends platformObject	{
	public $scheme			=	new scheme_authentication();
	public $client			=	null;
	public $status			=	null;
	public function __construct ($u=null,$p=null){
		$this->u = $u;
		$this->p = $p;
		if ($response = new client('/v1/authenticate',array(
			'headers' => array()))){
		$this->client = $response;
		$this->status = $response->getStatusCode();
			}
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
