<?php
namespace Revmsg\Mobilize;

class Metadata extends Entity\Entity
{
    protected $scheme = 'Revmsg\Mobilize\Model\Metadata';
    protected $customMap = array(
        'v1' => array(
            'create' => array(
                'url' => 'v1/metadata',
                'payload' => array(
                    'required' => array(
                        'name',
                        'format',
                        'multiValue'
                        ),
                    'ignored' => array(
                        'id',
                        'status'
                        )
                    )
                ),
                'retrieve' => array(
                    'url' => 'v1/metadata/%s'
                    ),
                'update' => array(
                    'url' => 'v1/metadata/%s'
                    ),
                'delete' => array(
                    'url' => 'v1/metadata/%s'
                    )
            )
    );
}
