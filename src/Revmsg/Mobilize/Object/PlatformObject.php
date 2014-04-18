<?php
namespace Revmsg\Mobilize\Object;

// use Guzzle\Http\Client;
// use Guzzle\Plugin\Cookie\CookiePlugin;
// use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
// use Revmsg\Mobilize\Model;

class PlatformObject implements Object
{
    protected $model        =    null;
    public function isDirty()
    {
        return $this->model->isDirty();
    }
    public function create ($version = 'v1', $session = null)
    {
        $this-> isDirty    =    false;
        if (!isset($this-> urls[$version]['create'])) {
            throw new Exception('object cannot be created');
        } else {
            if (!is_null($session)) {
                $request = $session()->create($this-> urls[$version]['create'].'/'.$this-> model->getVariable('name'));
                $response = $request-> send();
            } elseif (defined('REVMSG_MOBILIZE_KEY')) {
                $client = new \Guzzle\Http\Client('http://revolutionmsg.com/api');
                $request = $client->post(
                    $this-> urls[$version]['create'].'/'.$this->model->getVariable('id'),
                    array(
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => REVMSG_MOBILIZE_KEY
                        ),
                    json_encode(
                        $this->model->buildPayload()
                    )
                );
                $response = $request->send();
            } else {
                throw new Exception("API key or active session required.");
            }
            if ($response->getStatusCode() < 400) {
                    $this->model->dirtify(false);
                    $this->model->setVariables(json_decode($response->getBody(), true));
                    return $this;
            } else {
                    throw new Exception($response-> getStatusCode().': '.$response-> getBody());
                    return false;
            }
        }
    }
    public function retrieve    ($objectId = null, $version = 'v1', $session = null)
    {
        if (!is_null($objectId) && is_string($objectId)) {
            $this->id = $objectId;
        }
        if (!isset($this-> urls[$version]['retrieve'])) {
            throw new \Exception('object cannot be retrieved');
        } else {
            if (!is_null($session)) {
                $request    =    $session()-> retrieve($this-> urls[$version]['retrieve'].'/'.$this-> model->getVariable('id'));
                $response    =    $request-> send();
            } elseif (defined('REVMSG_MOBILIZE_KEY')) {
                $client = new \Guzzle\Http\Client('http://revolutionmsg.com/api');
                $request = $client->get(
                    $this-> urls[$version]['retrieve'].'/'.$this->model->getVariable('id'),
                    array(
                        'Accept'        =>    'application/json',
                        'Content-Type'    =>    'application/json',
                        'Authorization'    =>    REVMSG_MOBILIZE_KEY
                        )
                );
                $response = $request->send();
            } else {
                throw new Exception("API key or active session required.");
            }
            if ($response->getStatusCode() < 400) {
                    $this->model->dirtify();
                    $this->model->setVariables(json_decode($response->getBody(), true));
                    return $this;
            } else {
                    throw new Exception($response-> getStatusCode().': '.$response-> getBody());
                    return false;
            }
        }
    }
    public function update        ($version = 'v1', $session = null)
    {
        if (!isset($this-> urls[$version]['update'])) {
            throw new Exception('object cannot be updated');
        } else {
            if (!is_null($session)) {
                $request = $session()->create($this-> urls[$version]['update'].'/'.$this-> model->getVariable('name'));
                $response = $request-> send();
            } elseif (defined('REVMSG_MOBILIZE_KEY')) {
                $client = new \Guzzle\Http\Client('http://revolutionmsg.com/api');
                $request = $client->put(
                    $this-> urls[$version]['update'].'/'.$this->model->getVariable('id'),
                    array(
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => REVMSG_MOBILIZE_KEY
                        ),
                    json_encode(
                        $this->model->buildPayload()
                    )
                );
                $response = $request->send();
            } else {
                throw new Exception("API key or active session required.");
            }
            if ($response-> getStatusCode() < 400) {
                    $this->model->dirtify(false);
                    return $this;
            } else {
                throw new Exception($response-> getStatusCode().': '.$response-> getBody());
                return false;
            }
        }
    }
    public function delete ($version = 'v1', $session = null)
    {
        if (!isset($this-> urls[$version]['delete'])) {
            throw new Exception('object cannot be deleted');
        } else {
            if (!is_null($session)) {
                $request = $session()->create($this-> urls[$version]['delete'].'/'.$this-> model->getVariable('name'));
                $response = $request-> send();
            } elseif (defined('REVMSG_MOBILIZE_KEY')) {
                $client = new \Guzzle\Http\Client('http://revolutionmsg.com/api');
                $request = $client->delete(
                    $this-> urls[$version]['delete'].'/'.$this->model->getVariable('id'),
                    array(
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => REVMSG_MOBILIZE_KEY
                        )
                );
                $response = $request->send();
            } else {
                throw new Exception("API key or active session required.");
            }
            if ($response-> getStatusCode() < 400) {
                $this->model->dirtify(true);
                return $this;
            } else {
                throw new Exception($response-> getStatusCode().': '.$response-> getBody());
                return false;
            }
        }
    }
    public function set($name, $val)
    {
        $this-> model-> setVariable($name, $val);
        return $this;
    }
    public function __toString()
    {
        return json_encode($this->model->getVariables());
    }
    public function __set($name, $val)
    {
        $this-> model-> setVariable($name, $val);
    }
    public function __get($name)
    {
        $this-> model-> getVariable($name);
    }
    public function __construct($signifier = null)
    {
        $this->model = new $this->scheme;
        if (!empty($signifier)) {
            if (is_array($signifier)) {
                $this-> model-> setVariables($signifier);
            } elseif (is_string($signifier)) {
                $this-> retrieve($signifier);
            }
        }
        return $this;
    }
}
