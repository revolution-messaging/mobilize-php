<?php

class MobilizeHook {
	protected $endSession = true;
	protected $response = null;
	protected $format = null;
	protected $method = null;
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
		switch ($method) {
			case 'get':
				$this->method = $method;
				break;
			case 'post':
			default:
				$this->format = 'post';
		}
	}
	
	public function getFormat() {
		return $this->format;
	}
	
	public function setFormat($format='xml') {
		$format = trim(strtolower($format));
		switch ($format) {
			case 'json':
				$this->format = $format;
				break;
			case 'xml':
			default:
				$this->format = 'xml';
		}
	}
	
	public function getEnd() {
		return $this->endSession;
	}
	
	public function setEnd($end=true) {
		$this->endSession = false;
		if($end)
			$this->endSession = true;
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
		$this->stripText();
	}
	
	public function getInputs() {
		return $this->inputs;
	}
	
	public function setInputs($arr) {
		foreach($arr as $var => $val) {
			$this->setInput($var, $val);
		}
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
?>
