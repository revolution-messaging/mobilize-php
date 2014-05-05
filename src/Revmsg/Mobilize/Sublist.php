<?php
namespace Revmsg\Mobilize;

class Sublist extends Entity\Entity
{
    protected $scheme = 'Revmsg\Mobilize\Model\Sublist';
    protected $customMap = array(
        'v1' => array(
            'create' => array(
                'url' => 'v1/list',
                'payload' => array(
                    'required' => array(
                        'name'
                        ),
                    'ignored' => array(
                        'id'
                        )
                    )
                ),
                'retrieve' => array(
                    'url' => 'v1/list/%s'
                    ),
                'update' => array(
                    'url' => 'v1/list/%s'
                    ),
                'delete' => array(
                    'url' => 'v1/list/%s'
                    )
            )
    );
}
