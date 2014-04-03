<?php

/**
 * @file
 * Library for interacting with Revere Messaging
 *
 * Created by Revolution Messaging, LLC on 2014-04-03.
 */

namespace Revmsg\Mobilize;

class Hook
{
    protected $endSession = false;
    protected $response = null;
    protected $format = 'xml';
    protected $method = 'post';
    protected $responseLength = 160;

    protected $inputs = array(
        'msisdn' => '',
        'mobileText' => '',
        'keywordName' => '',
        'keywordId' => '',
        'shortCode' => '',
        'subscriberId' => '',
        'metadataId' => '',
        'oldValue' => array(),
        'newValue' => ''
    ); 

    public function __construct($format='xml', $method='post', $retrieve=true, $responseLength=160) {
        $this->setFormat($format);
        $this->setMethod($method);
        $this->setResponseLength($responseLength);
        if($retrieve)
            $this->retrieveInputs();
        // return $this;
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

    public function cleanMobileText($keyword=null,$mobileText=null) {
        if (is_null($keyword))
            $keyword = $this->inputs['keywordName'];
        if (is_null($mobileText))
            $mobileText = $this->inputs['mobileText'];
        return trim(preg_replace('/^('.$keyword.'\s)/i','',$mobileText));
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
            throw new Exception('You must specify "get" or "post".');
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
            throw new Exception('You must specify "xml" or "json".');
    }

    public function getEnd() {
        return $this->endSession;
    }

    public function setEnd($end=true) {
        if($end) {
            $this->endSession = true;
        } else {
            $this->endSession = false;
        }
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse($message, $force=false) {
        if(strlen($message)>$this->responseLength && !$force) {
            return false;
        } else {
            $this->response = $message;
        }
    }

    public function retrieveInputs($method=null) {
        if($method) $this->setMethod($method);
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
        } else {
            return false;
        }
    }

    public function setInput($var, $val) {
        if(array_key_exists($var, $this->inputs) && (gettype($val) == gettype($this->inputs[$var]))) {
            $this->inputs[$var] = $val;
            return true;
        } else {
            return false;
        }
    }

    public function setResponseLength($val=160) {
        if (is_int($val)) {
            $this->responseLength = $val;
        } else {
            return false;
        }
    }

    public function output() {
        switch($this->format) {
            case 'json':
                return json_encode(array(
                    'endSession'=>$this->getEnd(),
                    'response'=>$this->textTruncate($this->response, $this->responseLength)
                ));
            case 'xml':
            default:
                return "<dynamiccontent><endSession>".$this->getEnd(true)."</endSession><response>".htmlspecialchars($this->textTruncate($this->response, $this->responseLength))."</response></dynamiccontent>";
        }
    }
}
