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
                    'model' => 'metadataAPIModel'
                    )
                ),
            'removeMetadata' => array(
                'url' => '/api/v1/subscriber/removeMetadata/%s',
                'method' => 'PUT',
                'payload' => array(
                    'url' => array(
                        'id'
                        ),
                    'model' => 'metadataAPIModel'
                    )
                )
            )
        );
    public function addMetadata($signifier = array())
    {
        $this->setVariable('metadataAPIModel', $signifier);
        $this->operation('addMetadata');
        return $this;
    }
    public function removeMetadata($signifier = array())
    {
        $this->setVariable('metadataAPIModel', $signifier);
        $this->operation('removeMetadata');
        return $this;
    }
}
