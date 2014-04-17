<?php
namespace Revmsg\Mobilize;

class Authentication extends \Revmsg\Mobilize\Object\PlatformObject
{
    public $client = null;
    public $model = null;
    protected $impersonate = null;
    protected $urls = array(
        'v1' => array(
            'retrieve' => 'v1/authenticate/whoami',
            'create' => 'v1/authenticate',
            'delete' => 'v1/authenticate/logout'
            ),
        'v2' => array(
            'create' => '',
            'update' => ''
            )
        );
    public function create ($session = null, $version = 'v1')
    {
        $request = $this-> client-> post($this-> urls[$version]['create'], null, json_encode($this-> model-> getVariables()));
        $response = $request-> send();
    }
    public function retrieve ($objectId = null, $session = null, $version = 'v1')
    {
        if (!isset($this-> urls['v1']['retrieve'])) {
            throw new Exception('object cannot be retrieved');
        } else {
            $request = $this-> client-> get($this-> urls[$version]['retrieve']);
            $response = $request-> send();
            if ($response-> getStatusCode() < 400) {
                return json_decode($response-> getBody(), true);
            } else {
                throw new Exception($response-> getStatusCode().': '.$response-> getBody());
                return false;
            }
        }
    }
    /*public function update        ($signifier, $version='v1'){
        if (!isset($this->urls['v1']['update'])){
            throw new \Exception('object cannot be updated');
        }else{
            $request = $session()-> post($this-> urls['v1']['update'].'/'.$signifier);
            $response = $request-> send();
            if($response-> getStatusCode() < 400){
                $this-> model-> isDirty = false;
                return $this;
            }else{
                throw new Exception($response-> getStatusCode().': '.$response-> getBody());
                return false;
            }
        }
    }*/
    public function __invoke()
    {
        if (!isset($this-> client) || empty($this-> client)) {
            throw new Exception('No session active. Reauthenticate.');
        } else {
            return($this-> client);
        }
    }
    public function __construct($signifier = array())
    {
        $this-> model = new Model\Authentication;
        if (!empty($signifier) && isset($signifier)) {
            if (is_array($signifier)) {
                $this-> model-> setVariables($signifier);
                $this-> client = new \Guzzle\Http\Client(
                    'http://revolutionmsg.com/api',
                    array(
                    'request.options' => array(
                        'headers' => array(
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json'
                            )
                        )
                    )
                );
                $this-> client-> setUserAgent('Revmsg/Mobilize');
                $cookiePlugin = new \Guzzle\Plugin\Cookie\CookiePlugin(new \Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar());
                $this-> client-> addSubscriber($cookiePlugin);
            }
            if (is_string($signifier)) {
                    define('REVMSG_KEY', $signifier);
            }
        }
    }
}
