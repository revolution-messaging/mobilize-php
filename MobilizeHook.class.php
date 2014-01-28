<?php

class MobilizeHook {
	protected $endSession = true;
	protected $response = null;
	protected $format = null;
	protected $method = null;
	protected $responseLength = 160;
	protected $inputs = array(
		'cleanText'		=> null,
		'msisdn'		=> null,
		'mobileText'	=> null,
		'keywordName'	=> null,
		'keywordId'		=> null,
		'shortCode'		=> null,
		'subscriberId'	=> null,                  
		'metadataId'	=> null,
		'oldValue'		=> array(),
		'newValue'		=> null
	);
	
	public function __construct($format='xml', $method='post', $retrieve=true) {
		$this->setFormat($format);
		$this->setMethod($method);
		if($retrieve)
			$this->retrieveInputs($this->method);
	}
	
	private function textTruncate($text, $numb) {
		if(strlen($text) > $numb) {
			$text = substr($text, 0, $numb);
			$text = substr($text, 0, strrpos($text, " "));
			$etc = null;
			$text = $text.$etc;
		}
		return $text; 
	}
	
	public function cleanMobileText($keyword, $mobileText) {
		return trim(preg_replace('/^('.$keyword.'\s)/i','',$mobileText));
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
	
	public function setResponse($message, $force=false) {
		if(strlen($message)>$this->responseLength && !$force)
			return false
		else
			$this->response = $message;
	}
	
	public function retrieveInputs($method='get'){
		$this->setMethod($method);
		switch ($this->getMethod()) {
			case 'get':
				if(isset($_GET) && !empty($_GET))
					return $this->setInputs($_GET);
				break;
			case 'post':
			default:
				$d = file_get_contents('php://input');
				if($d) {
					switch ($this->format) {
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
				return $this->setInputs($d);
		}
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
		if(array_key_exists($var, $this->inputs) && (gettype($val) == gettype($this->inputs[$var]))) {
			$this->inputs[$var] = $val;
			return true;
		} else {
			return false;
		}
	}
	
	public function output() {
		switch($this->format) {
			case 'xml':
				return "<dynamiccontent><endSession>".$this->getEnd(true)."</endSession><response>".htmlspecialchars($this->textTruncate($this->response, $this->responseLength))."</response></dynamiccontent>";
			case 'json':
				return json_encode(array(
					'endSession'=>$this->getEnd(),
					'response'=>$this->textTruncate($this->response, $this->responseLength)
				));
		}
	}
}
