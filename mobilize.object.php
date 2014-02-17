<?php
namespace Revmsg\Mobilize\Object;
use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Revmsg\Mobilize\Model;
interface object			{
	public function isDirty		();
	public function delete          ($session,$version);
	public function retrieve        ($id, $session,$version);
	public function update          ($session,$version);
	public function create          ($session,$version);
	public function __construct	($session,$signifier);
	public function __set		($name,$value);
	public function set		($name,$value);
	public function __toString	();
}

class platformObject implements object	{
	protected $model		=	null;
	protected $client		=	null;
	public function isDirty(){
		return $this->model->isDirty();
	}
	
	public function delete 		($version='v1',$session=null){
		if (!is_null($session)){
			$this->client  = $session;
		}
		if (!isset($this->client) || is_null($this->client))
			throw new Exception('no active session. Reauthenticate.');
		if (!isset($this-> urls[$version]['delete'])){
			throw new Exception('object cannot be deleted');
		}else{
			$request	=	$this->client()-> delete($this-> urls[$version]['delete'].'/'.$this->model->getVariable('id'));
			$response	=	$request-> send();
			if($response-> getStatusCode() < 400){
				$this-> model-> isDirty	=	true;
				return $this;
			}else{
				throw new Exception($response-> getStatusCode().': '.$response-> getBody());
				return false;
			}
		}
	}
	
	public function retrieve	($id,$version='v1',$session=null){ //complete
		if (!isset($this-> urls[$version]['retrieve'])){
			throw new Exception('object cannot be retrieved');
		}else{
			$request	=	$this->client()-> get($this-> urls[$version]['retrieve'].'/'.$id);
			$response	=	$request-> send();
			if($response-> getStatusCode() < 400){
				$data = json_decode($response-> getBody(),true);
				$this-> model-> setVariables($data);
				$this-> model-> isDirty	=	false;
				return $this;
			}else{
				throw new Exception($response-> getStatusCode().': '.$response-> getBody());
				return false;
			}
		}
	}
	
	public function update		($version='v1',$session=null){
		if (!isset($this->urls[$version]['update'])){
			throw new \Exception('object cannot be updated');
		}else{
			$request	=	$this->client()-> put($this-> urls[$version]['update'].'/'.$this->model->getVariable('id'),null,json_encode($this->model->getVariables()));
			$response	=	$request-> send();
			if($response-> getStatusCode() < 400){
				$this-> model-> isDirty	=	false;
				return $this;
			}else{
				throw new Exception($response-> getStatusCode().': '.$response-> getBody());
				return false;
			}
		}
	}
	
	public function create		($version='v1',$session=null){
		$this-> isDirty	=	false;
		if (!isset($this-> urls[$version]['create'])){
			throw new Exception('object cannot be created');
		}else{
			$request	=	$this->client()-> post($this-> urls[$version]['create'],null,json_encode($this->model->getVariables()));
			$response	=	$request-> send();
			if($response-> getStatusCode() < 400){
				$this-> model-> isDirty	=	false;
				return $this;
			}else{
				throw new Exception($response-> getStatusCode().': '.$response-> getBody());
				return false;
			}
		}
	}
	
	public function set($name,$val){
		$this-> model-> setVariable($name,$val);
		return $this;
	}
	
	public function __toString(){
		return json_encode($this->model->getVariables());
	}
	
	public function __set($name,$val){
		$this-> model-> setVariable($name,$val);
	}
	
	public function __construct($session=null,$signifier=array()){
		$this->model		=	new $this->scheme;
		$this->client		=	$session();
		if(!empty($signifier)){
				$this-> model-> setVariables($signifier);
			}elseif(is_string($signifier) && isset($session)){
				$this-> retrieve($signifier,$session);
			}
		return $this;
	}
}

class impersonate extends platformObject {
	protected $scheme	=	'Revmsg\Mobilize\Model\impersonate';
	protected $urls		=	array(
		'v1'		=>	array(
			'create'	=>	'v1/impersonate',
			'delete'	=>	'v1/impersonate'
			)
		);
}
	
class sublist extends platformObject{
	protected $scheme	=	'Revmsg\Mobilize\Model\sublist';
	protected $urls		=	array(
		'v1'		=>	array(
			'create'	=>	'v1/list',
			'update'	=>	'v1/list',
			'retrieve'	=>	'v1/list',
			'delete'	=>	'v1/list'
			),
		'v2'		=>	array()
		);
	
}

class subscriber extends platformObject{
	public $scheme		=	'Revmsg\Mobilize\Model\subscriber';
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'v1/subscriber',
			),
		'v2'		=>	array(
			)
		);
}

class metadata extends platformObject	{
	public $scheme		=	'Revmsg\Mobilize\Model\metadata';
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
	
	
}

class filter extends platformObject	{  
	public $scheme		=	'Revmsg\Mobilize\Model\filter';
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
}

class authentication extends platformObject	{
	public $client		=	null;
	public $model		=	null;
	protected $impersonate	=	null;
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
	
	public function create		($version='v1',$session=null){
		$request	=	$this-> client-> post($this-> urls[$version]['create'],null,json_encode($this-> model-> getVariables()));
		$response	=	$request-> send();
	}
	
	public function retrieve	($id=null,$version='v1',$session=null){
		if (!isset($this-> urls['v1']['retrieve'])){
			throw new Exception('object cannot be retrieved');
		}else{
			$request	=	$this-> client-> get($this-> urls[$version]['retrieve']);
			$response	=	$request-> send();
			if($response-> getStatusCode() < 400){
				return json_decode($response-> getBody(),true);
			}else{
				throw new Exception($response-> getStatusCode().': '.$response-> getBody());
				return false;
			}
		}
	}
	
	public function __invoke(){
		if(!isset($this-> client) || empty($this-> client)){
			throw new Exception('No session active. Reauthenticate.');
		}else{
			return($this-> client);
		}
	}
	
	public function __construct($session=null,$signifier=array()){
		$this-> model		=	new model\authentication;
		if(!empty($signifier) && isset($signifier)){
			$this-> model-> setVariables($signifier);
		}
		if(isset($session) && is_null($session)){
			$this->client	=	$session;
		}else{
			$this-> client	=	new Client('http://revolutionmsg.com/api',array(
				'request.options'	=>	array(
					'headers'	=>	array(
						'Accept'	=>	'application/json',
						'Content-Type'	=>	'application/json'
						)
					)
				)
			);
			$this-> client-> setUserAgent('Revmsg/Mobilize');
			$cookiePlugin = new CookiePlugin(new ArrayCookieJar());
			$this-> client-> addSubscriber($cookiePlugin);
		}
	}
	
}
?>
