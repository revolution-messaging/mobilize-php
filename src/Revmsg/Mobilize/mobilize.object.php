<?php
namespace Revmsg\Mobilize
use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Revmsg\Mobilize\Model;
interface object			{
	public function isDirty		();
	public function delete          ($version, $session);
	public function retrieve        ($id, $version, $session);
	public function update          ($version, $session);
	public function create          ($version, $session);
	public function __construct	($signifier);
	public function __set		($name,$value);
	public function set		($name,$value);
	public function __toString	();
}

class platformObject implements object	{
	protected $model		=	null;
	
	public function isDirty(){
		return $this->model->isDirty();
	}
	
	public function delete 		($version='v1', $session=null){
		if (!isset($this-> urls[$version]['delete'])){
			throw new Exception('object cannot be deleted');
		}else{
			if(!is_null($session)){
				$request	=	$session()-> delete($this-> urls[$version]['delete'].'/'.$this-> model->getVariable('id'));
				$response	=	$request-> send();
			}elseif(defined('REVMSG_KEY'){
				$response = GuzzleHttp\delete(
					$this-> urls[$version]['delete'].'/'.$this->model->getVariable('id'),
					'headers'	=>	array(
						'Accept'		=>	'application/json',
						'Content-Type'	=>	'application/json',
						'Authorization'	=>	REVMSG_KEY
						)
					);
			}else{
				throw new Exception("API key or active session required.");
			}
			if($response-> getStatusCode() < 400){
					$this-> model-> isDirty	=	true;
					return $this;
				}else{
					throw new Exception($response-> getStatusCode().': '.$response-> getBody());
					return false;
				}			
		}
	}
	
	public function retrieve	($id,$version='v1', $session=null){ //complete
		if (!isset($this-> urls[$version]['retrieve'])){
			throw new Exception('object cannot be retrieved');
		}else{
			if(!is_null($session)){
				$request	=	$session()-> retrieve($this-> urls[$version]['retrieve'].'/'.$this-> model->getVariable('id'));
				$response	=	$request-> send();
			}elseif(defined('REVMSG_KEY'){
				$response = GuzzleHttp\retrieve(
					$this-> urls[$version]['retrieve'].'/'.$this->model->getVariable('id'),
					'headers'	=>	array(
						'Accept'		=>	'application/json',
						'Content-Type'	=>	'application/json',
						'Authorization'	=>	REVMSG_KEY
						)
					);
			}else{
				throw new Exception("API key or active session required.");
			}
			if($response-> getStatusCode() < 400){
					$this-> model-> isDirty	=	true;
					return $this;
				}else{
					throw new Exception($response-> getStatusCode().': '.$response-> getBody());
					return false;
				}			
		}
	}
	
	public function update		($version='v1', $session=null){
		if (!isset($this-> urls[$version]['update'])){
			throw new Exception('object cannot be updated');
		}else{
			if(!is_null($session)){
				$request	=	$session()-> update($this-> urls[$version]['update'].'/'.$this-> model->getVariable('id'));
				$response	=	$request-> send();
			}elseif(defined('REVMSG_KEY'){
				$response = GuzzleHttp\update(
					$this-> urls[$version]['update'].'/'.$this->model->getVariable('id'),
					'headers'	=>	array(
						'Accept'		=>	'application/json',
						'Content-Type'	=>	'application/json',
						'Authorization'	=>	REVMSG_KEY
						)
					);
			}else{
				throw new Exception("API key or active session required.");
			}
			if($response-> getStatusCode() < 400){
					$this-> model-> isDirty	=	true;
					return $this;
				}else{
					throw new Exception($response-> getStatusCode().': '.$response-> getBody());
					return false;
				}			
		}
	}
	
	public function create		($version='v1', $session=null){
		$this-> isDirty	=	false;
		if (!isset($this-> urls[$version]['create'])){
			throw new Exception('object cannot be created');
		}else{
			if(!is_null($session)){
				$request	=	$session()-> create($this-> urls[$version]['create'].'/'.$this-> model->getVariable('id'));
				$response	=	$request-> send();
			}elseif(defined('REVMSG_KEY'){
				$response = GuzzleHttp\create(
					$this-> urls[$version]['create'].'/'.$this->model->getVariable('id'),
					'headers'	=>	array(
						'Accept'		=>	'application/json',
						'Content-Type'	=>	'application/json',
						'Authorization'	=>	REVMSG_KEY
						)
					);
			}else{
				throw new Exception("API key or active session required.");
			}
			if($response-> getStatusCode() < 400){
					$this-> model-> isDirty	=	true;
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

	public function __construct($signifier=array(),$session=null){
		$this->model		=	new $this->scheme;
		if(!empty($signifier)){
			if(is_array($data)){
				$this-> model-> setVariables($signifier);
			}elseif(is_string($signifier) && isset($session)){
				$this-> retrieve($signifier,$session);
			}
		}
		return $this;
	}
}

?>
