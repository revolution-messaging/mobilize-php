<?php
namespace revmsg\mobilize\object;
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
	
	public function delete 		($session,$version='v1'){
		if (!isset($this-> urls[$version]['delete'])){
			throw new Exception('object cannot be deleted');
		}else{
			$request	=	$session()-> delete($this-> urls[$version]['delete'].'/'.$this->model->getVariable('id'));
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
	
	public function retrieve	($id,$session,$version='v1'){ //complete
		if (!isset($this-> urls[$version]['retrieve'])){
			throw new Exception('object cannot be retrieved');
		}else{
			$request	=	$session()-> get($this-> urls[$version]['retrieve'].'/'.$id);
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
	
	public function update		($session,$version='v1'){
		if (!isset($this->urls[$version]['update'])){
			throw new \Exception('object cannot be updated');
		}else{
			$request	=	$session()-> put($this-> urls[$version]['update'].'/'.$this->model->getVariable('id'),null,json_encode($this->model->getVariables()));
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
	
	public function create		($session,$version='v1'){
		$this-> isDirty	=	false;
		if (!isset($this-> urls[$version]['create'])){
			throw new Exception('object cannot be created');
		}else{
			$request	=	$session()-> post($this-> urls[$version]['create'],null,json_encode($this->model->getVariables()));
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
