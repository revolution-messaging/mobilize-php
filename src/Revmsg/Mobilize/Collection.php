<?php

namespace Revmsg\Mobilize;

class Collection extends Entity\Object implements Entity\CollectionInterface
{
    protected $scheme = 'Revmsg\Mobilize\Model\Collection';
    protected $customMap = array();
    public function filter($property, $value, $operator = 'eq')
    {
        $output = array();
        if (!is_array($value)) {
                $value = array($value);
        }
        switch ($operator)
        {
            case 'eq':
                foreach ($this->model->getVariable('collection') as $item => $contents) {
                    if ($contents[$property] == $value[0]) {
                        $output[] = $contents;
                    }
                }
                break;
            case 'ne':
                foreach ($this->model->getVariable('collection') as $item => $contents) {
                    if ($contents[$property] != $value[0]) {
                        $output[] = $contents;
                    }
                }
                break;
            case 'gt':
                foreach ($this->model->getVariable('collection') as $item => $contents) {
                    if ($contents[$property] >= $value[0]) {
                        $output[] = $contents;
                    }
                }
                break;
            case 'ge':
                foreach ($this->model->getVariable('collection') as $item => $contents) {
                    if ($contents[$property] <= $value[0]) {
                        $output[] = $contents;
                    }
                }
                break;
            case 'lt':
                foreach ($this->model->getVariable('collection') as $item => $contents) {
                    if ($contents[$property] < $value[0]) {
                        $output[] = $contents;
                    }
                }
                break;
            case 'le':
                foreach ($this->model->getVariable('collection') as $item => $contents) {
                    if ($contents[$property] > $value[0]) {
                        $output[] = $contents;
                    }
                }
                break;
            case 'in':
                foreach ($this->model->getVariable('collection') as $item => $contents) {
                    if (in_array($contents[$property], $value)) {
                        $output[] = $contents;
                    }
                }
                break;
            case 'nin':
                foreach ($this->model->getVariable('collection') as $item => $contents) {
                    if (!in_array($contents[$property], $value)) {
                        $output[] = $contents;
                    }
                }
                break;
            default:
                throw new \Exception('Invalid operator.');
        }
        $this->model->setVariable('collection', $output);
        return $this;
    }

    public function toArray($index = null)
    {
        $output = $this->getVariable('collection');
        if (!empty($output)) {
            if ($index === null) {
                return $output;
            } else {
                return $output[$index];
            }
        } else {
            return false;
        }
    }

    public function findArray($property, $value, $index = 0)
    {
        $this->filter($property, $value, 'eq');
        return $this->toArray($index);
    }
}
