<?php

namespace Revmsg\Mobilize;

class Messaging extends Entity\Entity
{
    protected $scheme = 'Revmsg\Mobilize\Model\Messaging';
    protected $customMap = array(
        'v1' => array(
            'sendContent' => array(
                'url' => '/api/v1/messaging/sendContent',
                'method' => 'POST',
                'payload' => array(
                    'model' => 'MessagingAPIModel',
                    )
                ),
            'sendBroadcast' => array(
                )
            ),
        'v2' => array(
            'send' => array(
                ),
            'sendmulti' => array(
                ),
            'tracking' => array(
                )
            )
        );
    public function sendContent ($model)
    {
        $this->setModel('MessagingAPIModel', $model);
        $this->operation('sendContent');
    }
}
