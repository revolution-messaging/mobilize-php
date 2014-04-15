<?php
namespace Revmsg\Mobilize\Platform;

class Metadata extends PlatformObject
{
    public $scheme        =    'Revmsg\Mobilize\Model\metadata';
    protected $urls        =    array(
        'v1'        =>    array(
            'retrieve'    =>    'v1/metadata',
            'create'    =>    'v1/metadata',
            'update'    =>    'v1/metadata',
            'delete'    =>    'v1/metadata'
            ),
        'v2'        =>    array(
            )
        );
}
