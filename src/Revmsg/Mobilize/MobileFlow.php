<?php

namespace Revmsg\Mobilize;

class MobileFlow extends Entity\Entity
{
    protected $scheme = 'Revmsg\Mobilize\Model\MobileFlow';
    protected $customMap = array(
        'v1' => array(
                'retrieve' => array(
                    'url' => 'v1/mobileflow/%s'
                    ),
                'retrieveForEdit' => array(
                    'url' => 'v1/mobileflow/editmobileflow/%s',
                    'method' => 'GET',
                    'payload' => array(
                        'url' => array(
                            'id'
                            )
                        )
                    )
            )
        );
}
