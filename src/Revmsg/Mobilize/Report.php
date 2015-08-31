<?php
namespace Revmsg\Mobilize;

class Report extends Entity\Object implements Entity\PageInterface
{
    protected $scheme = 'Revmsg\Mobilize\Model\Report';
    protected $customMap = array(
        'v1' => array(
            'fetch' => array(
                'url' => '/api/v1/reporting/%s/%s',
                'method' => 'GET',
                'payload' => array(
                    'url' => array(
                        'element',
                        'summaryObjectType'
                        ),
                    'query' => array(
                        'orderBy',
                        'size',
                        'page',
                        'type',
                        'beginOn',
                        'endOn',
                        'bucketSize',
                        'summaryType',
                        'timeframe',
                        'summaryObjectId'
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
        return $this;
    }
    public function flipTo($index)
    {
        $this->page = $index;
        $this->fetch();
        return $this;
    }
    public function all()
    {
        if ($this->getVariable('size') != $this->getVariable('total')) {
            $this->setVariable('size', $this->getVariable('total'));
            $this->setVariable('page', '1');
            $this->fetch();
        }
        return $this;
    }
    public function fetch()
    {
        $this->operation('fetch');
        return $this;
    }
    public function filter($name, $value, $operator = null)
    {
        if (empty($this->collection)) {
            $this->fetch();
        }
        return $this->all()->collect()->filter($name, $value, $operator);
    }
    public function findArray($property, $value, $index = 0)
    {
        $output = $this->filter($property, $value, 'eq')->findArray($property, $value, $index);
        return $output;
    }
}
