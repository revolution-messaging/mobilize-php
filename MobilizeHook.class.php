<?php

class MobilizeHook {
	protected $endSession = false;
	protected $response = null;
	protected $format = 'xml';
	protected $method = 'post';
	protected $inputs = array(
		'strippedText' 	=> null,
		'msisdn'       	=> null,
		'mobileText'  	=> null,
		'keywordName' 	=> null,
		'keywordId'   	=> null,
		'shortCode'   	=> null,
		'subscriberId' => null,                  
		'metadataId'   => null,
		'oldValue'     => array(),
		'newValue'     => null
	);
	
	private function textTruncate($text, $numb) {
		if(strlen($text) > $numb) {
			$text = substr($text, 0, $numb);
			$text = substr($text, 0, strrpos($text, " "));
			$etc = null;
			$text = $text.$etc;
		}
		return $text; 
	}
	
	public function __construct($format='xml', $method='post') {
		$this->setFormat($format);
		$this->setMethod($method);
		$this->retrieveInputs($this->method);
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function setMethod($method='post') {
		$method = trim(strtolower($method));
		if($method=='get' || $method=='post') {
			$this->method = $method;
			return true;
		} else
			return false;
	}
	
	public function getFormat() {
		return $this->format;
	}
	
	public function setFormat($format='xml') {
		$format = trim(strtolower($format));
		if($format=='xml' || $format=='json') {
			$this->format = $format;
			return true;
		} else
			return false;
	}
	
	public function getEnd() {
		return $this->endSession;
	}
	
	public function setEnd($end=true) {
		if($end)
			$this->endSession = true;
		else
			$this->endSession = false;
	}
	
	public function getResponse() {
		return $this->response;
	}
	
	public function setResponse($message) {
		$this->response = $message;
	}
	
	public function stripText() {
		if($this->inputs['keywordName'] && $this->inputs['mobileText']) {
			$this->inputs['strippedText'] = preg_replace('/^('.$this->inputs['keywordName'].'\s+)/i',null,$this->inputs['mobileText']);
			return true;
		} else
			return false;
	}
	
	public function retrieveInputs($method='get'){
		$this->setMethod($method);
		switch ($this->getMethod()) {
			case 'get':
				$d = $_GET;
				break;
			case 'post':
			default:
				file_get_contents('php://input');
				switch ($this->format)
					case 'json':
						try {
							$d = json_decode($d, true);
						} catch(Exception $e) {
							throw new Exception($e->getMessage());
						}
						break;
					case 'xml':
					default:
						try {
							$d = new SimpleXMLElement($d);
						} catch(Exception $e) {
							throw new Exception($e->getMessage());
						}
				}
		}
		$this->setInputs($d);
		return $this->stripText();
	}
	
	public function getInputs() {
		return $this->inputs;
	}
	
	public function setInputs($arr) {
		if(is_array($arr)) {
			foreach($arr as $var => $val) {
				$this->setInput($var, $val);
			}
			return true;
		} else
			return false;
	}
	
	public function setInput($var, $val) {
		if(in_array($var, array_keys($this->inputs)) && (gettype($val) == gettype($this->inputs[$var]))) {
			$this->inputs[$var] = $val;
			return true;
		} else {
			return false;
		}
	}
	
	public function output() {
		switch($this->format){
		case 'xml':
			return "<dynamiccontent><endSession>".$this->getEnd(true)."</endSession><response>".htmlspecialchars($this->textTruncate($this->response, 160))."</response></dynamiccontent>";
		case 'json':
			return json_encode(array(
				'endSession'=>$this->getEnd(),
				'response'=>ttruncat($this->response,160)
			));
		}
	}
}
