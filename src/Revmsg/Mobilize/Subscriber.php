<?php
namespace Revmsg\Mobilize;

class Subscriber extends Entity\Entity
{
    protected $scheme = '\Revmsg\Mobilize\Model\Subscriber';
    protected $customMap = array(
        'v1' => array(
            'retrieve' => array(
                'url' => '/api/v1/subscriber/%s'
                    )
            )
        );
}
