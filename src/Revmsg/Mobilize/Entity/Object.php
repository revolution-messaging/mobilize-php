<?php

namespace Revmsg\Mobilize\Entity;

class Object implements \Revmsg\Mobilize\Entity\ObjectInterface
{
    protected $model = null;
    protected $client = null;
    protected $map = array(
        'v1' => array(),
        'v2' => array()
        );
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
        $this->client = new \Guzzle\Http\Client('http://revolutionmsg.com/api');
        foreach ($this->map as $version => $operations) {
            if (empty($this->customMap[$version])) {
                unset($this->map[$version]);
            }
            if (!empty($operations)) {
                foreach ($operations as $operation => $parameter) {
                    if (!isset($this->customMap[$version][$operation])) {
                        unset($this->map[$version][$operation]);
                    }
                }
            }

            $this->map = array_replace_recursive($this->map, $this->customMap);

        }
        return $this;
    }
    public function operation($operation, $version = 'v1', $session = null)
    {
        if (!isset($this->map[$version]) && !isset($this->map[$version][$operation])) {
            throw new Exception(get_class($this)." objects do not have the $operation method in $version API");
        } else {
            if (!is_null($session)) {
               /* $request = $session()->create($this-> urls[$version]['create'].'/'.$this-> model->getVariable('name'));
                $response = $request-> send();*/
            } elseif (defined('REVMSG_MOBILIZE_KEY')) {
                if (empty($this->client)) {
                    $this->client = new \Guzzle\Http\Client(
                        array(
                            'base_url' => 'https://revolutionmsg.com/api/'
                            )
                    );
                }
                $request = $this->client->createRequest(
                    $this->map[$version][$operation]['method'],
                    $this->buildUrl($operation, $version),
                    array(
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'Authorization' => REVMSG_MOBILIZE_KEY
                        ),
                    $this->buildPayload($operation, $version),
                    array(
                        'exceptions' =>false,
                        'query' => $this->buildQuery($operation, $version)
                        )
                );
                try {
                    $response = $this->client->send($request);
                    if ($response->getStatusCode() > 300) {
                        $body =
                        'Response: '.$response->getBody().
                        ' URL: '.$request->getUrl().
                        ' Method: '.$request->getMethod();
                        throw new \Exception($body);
                    } elseif ($response->getBody()) {
                        $this->model->setVariables($response->json());
                    } else {

                    }
                    return $this;
                } catch (Exception $e) {
                    echo $e;
                }
                
            } else {
                throw new \Exception("API key or active session required.");
            }
        }
    }   
    protected function buildUrl ($operation, $version = 'v1')
    {
        $args = array(
            $this->map[$version][$operation]['url']
            );
        if (isset($this->map[$version][$operation]['payload']['url'])) {
            foreach ($this->map[$version][$operation]['payload']['url'] as $property) {
                if ($this->model->getVariable($property)) {
                    $args[] = $this->model->getVariable($property);
                } else {
                    throw new \Exception($operation.' ('.$version.') requires the \''.$property.'\'property to be set.');
                }
                
            }
        }
        return call_user_func_array('sprintf', $args);
    }
    protected function buildPayload ($operation, $version = 'v1')
    {
        if (in_array($this->map[$version][$operation]['method'], array('POST', 'PUT'))) {
            $payload = $this->model->getVariables();
            foreach ($this->map[$version][$operation]['payload']['required'] as $index => $property) {
                if (empty($payload[$property])) {
                    throw new \Exception($property." required");
                }
            }
            foreach ($this->map[$version][$operation]['payload']['ignored'] as $index => $property) {
                unset($payload[$property]);
            }
            return json_encode($payload);
        }
        return false;
    }
    protected function buildQuery ($operation, $version = 'v1')
    {
        $payload = array();
        if (isset($this->map[$version][$operation]['payload']['query'])) {
            foreach ($this->map[$version][$operation]['payload']['query'] as $index => $property) {
                if ($this->model->getVariable($property)) {
                    $payload[$property] = ($this->model->getVariable($property));
                }
            }
        }
        return $payload;
    }
    public function __toString()
    {
        return json_encode($this->model->getVariables());
    }
    public function __get($name)
    {
        $this-> model-> getVariable($name);
    }
    public function set($name, $val)
    {
        $this-> model-> setVariable($name, $val);
        return $this;
    }
    public function __set($name, $val)
    {
        $this-> model-> setVariable($name, $val);
    }
}
