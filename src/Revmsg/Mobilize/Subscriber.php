<?php

namespace Revmsg\Mobilize;

class Subscriber extends Entity\Entity
{
    protected $scheme = '\Revmsg\Mobilize\Model\Subscriber';
    protected $customMap = array(
        'v1' => array(
            'retrieve' => array(
                'url' => '/api/v1/subscriber/%s'
                    ),
            'addMetadata' => array(
                'url' => '/api/v1/subscriber/addMetadata/%s',
                'method' => 'PUT',
                'payload' => array(
                    'url' => array(
                        'id'
                        ),
                    'model' => 'MetadataAPIModel'
                    )
                ),
            'unsubscribe' => array(
                'url' => '/api/v1/subscriber/unsubscribe',
                'method' => 'POST',
                'payload' => array(
                    'model' => 'SubscribersAndList'
                    )
                ),
            'removeMetadata' => array(
                'url' => '/api/v1/subscriber/removeMetadata/%s',
                'method' => 'PUT',
                'payload' => array(
                    'url' => array(
                        'id'
                        ),
                    'model' => 'MetadataAPIModel'
                    )
                )
            ),
        'v2' => array(
            'addToList' => array(
                'url' => '/api/v2/subscriber/%s/list/%s',
                'method' => 'PUT',
                'payload' => array(
                    'url' => array(
                        'id',
                        'listId'
                        )
                    )
                )
            )
        );
    public function addMetadata($signifier = array())
    {
        $this->setModel('MetadataAPIModel', $signifier);
        $this->operation('addMetadata');
        return $this;
    }
    public function removeMetadata($signifier = array())
    {
        $this->setModel('MetadataAPIModel', $signifier);
        $this->operation('removeMetadata');
        return $this;
    }
    public function unsubscribe ($list)
    {
        $model = array(
            'subscribers' => array(
                $this->getVariable('id')
                )
            );
        if (is_object($list) && strpos(get_class($list), 'Sublist')) {
            $model['list'] = $list->getVariable('id');
        } elseif (is_string($list)) {
            $model['list'] = $list;
        }
        $this->setModel('SubscribersAndList', $model);
        $this->operation('unsubscribe');
    }
    public function addToList ($list)
    {
        if (is_object($list) && strpos(get_class($list), 'Sublist')) {
            $list = $list->getVariable('id');
        }
        $this->setVariable('listId', $list);
        $this->operation('addToList', 'v2');
    }
}
