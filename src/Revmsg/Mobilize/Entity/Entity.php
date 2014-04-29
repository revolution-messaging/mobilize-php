<?php
namespace Revmsg\Mobilize\Entity;

// use Guzzle\Http\Client;
// use Guzzle\Plugin\Cookie\CookiePlugin;
// use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
// use Revmsg\Mobilize\Model;

class Entity extends Object implements \Revmsg\Mobilize\Entity\EntityInterface
{
    protected $model        =    null;
    protected $client = null;
    protected $map = array(
        'v1' => array(
            'create' => array(
                'url' => '',
                'method' => 'POST',
                'dirty' => false,
                'payload' => array(
                    'required' => array(
                        ),
                    'ignored' => array(
                        )
                    )
                ),
            'retrieve' => array(
                'url' => '',
                'method' => 'GET',
                'dirty' => false,
                'payload' => array(
                    'required' => array(
                        ),
                    'ignored' => array(
                        ),
                    'url' => array(
                        'id'
                        )
                    )
                ),
            'update' => array(
                'url' => '',
                'method' => 'PUT',
                'dirty' => false,
                'payload' => array(
                    'required' => array(
                        ),
                    'ignored' => array(
                        ),
                    'url' => array(
                        'id'
                        )
                    )
                ),
            'delete' => array(
                'url' => '',
                'method' => 'DELETE',
                'dirty' => true,
                'payload' => array(
                    'required' => array(
                        ),
                    'ignored' => array(
                        ),
                    'url' => array(
                        'id'
                        )
                    )
                ),
            ),
        'v2' => array(
            'create' => array(
                'url' => '',
                'method' => 'PUT',
                'dirty' => false,
                'payload' => array(
                    'required' => array(
                        ),
                    'ignored' => array(
                        ),
                    'url' => array(
                        'id'
                        )
                    )
                ),
            'retrieve' => array(
                'url' => '',
                'method' => 'GET',
                'dirty' => false,
                'payload' => array(
                    'required' => array(
                        ),
                    'ignored' => array(
                        ),
                    'url' => array(
                        'id'
                        )
                    )
                ),
            'update' => array(
                'url' => '',
                'method' => 'POST',
                'dirty' => false,
                'payload' => array(
                    'required' => array(
                        ),
                    'ignored' => array(
                        ),
                    'url' => array(
                        'id'
                        )
                    )
                ),
            'delete' => array(
                'url' => '',
                'method' => 'DELETE',
                'dirty' => true,
                'payload' => array(
                    'required' => array(
                        ),
                    'ignored' => array(
                        ),
                    'url' => array(
                        'id'
                        )
                    )
                ),
            )
        );
    public function isDirty()
    {
        return $this->model->isDirty();
    }
    public function create ($version = 'v1', $session = null)
    {
        return $this-> operation('create', $version, $session);
    }
    public function update        ($version = 'v1', $session = null)
    {
        return $this-> operation('update', $version, $session);
    }
    public function delete ($version = 'v1', $session = null)
    {
        return $this-> operation('delete', $version, $session);
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
