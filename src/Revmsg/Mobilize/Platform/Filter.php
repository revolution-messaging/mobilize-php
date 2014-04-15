<?php
namespace Revmsg\Mobilize\Platform;

class Filter extends PlatformObject
{
    public $scheme        =    'Revmsg\Mobilize\Model\filter';
    protected $urls        =    array(
        'v1'        =>    array(
            'retrieve'    =>    'v1/filter',
            'create'    =>    'v1/filter',
            'update'    =>    'v1/filter',
            'delete'    =>    'v1/filter'
            ),
        'v2'        =>    array(
            'create'    =>    '',
            'update'    =>    ''
            )
        );
}
