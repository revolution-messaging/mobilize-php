<?php
namespace Revmsg\Mobilize\Platform
	class Impersonate extends PlatformObject {
	protected $scheme	=	'Revmsg\Mobilize\Model\impersonate';
	protected $urls		=	array(
		'v1'		=>	array(
			'create'	=>	'v1/impersonate',
			'delete'	=>	'v1/impersonate'
			)
		);
}
	
class Sublist extends PlatformObject{
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

class Subscriber extends PlatformObject{
	public $scheme		=	'Revmsg\Mobilize\Model\subscriber';
	protected $urls		=	array(
		'v1'		=>	array(
			'retrieve'	=>	'v1/subscriber',
			),
		'v2'		=>	array(
			)
		);
}

class Metadata extends PlatformObject	{
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

class filter extends PlatformObject	{  
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

class authentication extends PlatformObject	{
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
	
	public function create		($session=null,$version='v1'){
		$request	=	$this-> client-> post($this-> urls[$version]['create'],null,json_encode($this-> model-> getVariables()));
		$response	=	$request-> send();
	}
	
	public function retrieve	($id=null,$session=null,$version='v1'){
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
	
	/*public function update		($signifier, $version='v1'){
		if (!isset($this->urls['v1']['update'])){
			throw new \Exception('object cannot be updated');
		}else{
			$request	=	$session()-> post($this-> urls['v1']['update'].'/'.$signifier);
			$response	=	$request-> send();
			if($response-> getStatusCode() < 400){
				$this-> model-> isDirty	=	false;
				return $this;
			}else{
				throw new Exception($response-> getStatusCode().': '.$response-> getBody());
				return false;
			}
		}
	}*/
	
	public function __invoke(){
		if(!isset($this-> client) || empty($this-> client)){
			throw new Exception('No session active. Reauthenticate.');
		}else{
			return($this-> client);
		}
	}
	
	public function __construct($signifier''=array()''){
		$this-> model		=	new model\authentication;
		if(!empty($signifier) && isset($signifier)){
			if (is_array($signifier)){
				$this-> model-> setVariables($signifier);
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
			if (is_string($signifier))
				define('REVMSG_KEY', $signifier)
		}
	}	
}
?>