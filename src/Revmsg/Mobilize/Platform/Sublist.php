<?php
namespace Revmsg\Mobilize\Platform;

class Sublist extends PlatformObject
{
    protected $scheme    =    'Revmsg\Mobilize\Model\sublist';
    protected $urls        =    array(
        'v1'        =>    array(
            'create'    =>    'v1/list',
            'update'    =>    'v1/list',
            'retrieve'    =>    'v1/list',
            'delete'    =>    'v1/list'
            ),
        'v2'        =>    array()
        );
}
