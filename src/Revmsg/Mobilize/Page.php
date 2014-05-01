<?php
namespace Revmsg\Mobilize;

class Page extends Entity\Object implements Entity\PageInterface
{
    protected $scheme = 'Revmsg\Mobilize\Model\Page';
    public $collection = null;
    protected $customMap = array(
        'v1' => array(
            'fetch' => array(
                'url' => '/api/v1/%s',
                'method' => 'GET',
                'payload' => array(
                    'url' => array(
                        'element'
                        ),
                    'query' => array(
                        'orderBy',
                        'size',
                        'page'
                        )
                    )
                )
            )
        );

    public function flip($inc = 1)
    {
        $this->model->setVariable('page', $inc+$this->model->getVariable('page'));
        $this->fetch();
        return $this;
    }
    public function size($size)
    {
        $this->size = $size;
        $this->fetch();

    }
    public function flipTo($index)
    {
        $this->page = $index;
        $this->fetch();
    }
    public function all()
    {
        $this->model->setVariable('size', $this->model->getVariable('total'));
        $this->model->setVariable('page', '1');
        $this->fetch();
        return $this;
    }
    // public function __toString()
    // {
    //     return json_encode(
    //         $output = $this->model->getVariables();
    //         );
    // }
    
    public function fetch()
    {
        $this->operation('fetch');
        $this->collection = new Collection(
            array(
                'collection' => $this->model->getVariable(
                    'collection'
                )
            )
        );
        return $this;
    }
}
