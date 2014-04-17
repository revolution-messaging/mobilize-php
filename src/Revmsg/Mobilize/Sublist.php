<?php
namespace Revmsg\Mobilize;

class Sublist extends Object\PlatformObject
{
    protected $scheme    =    'Revmsg\Mobilize\Model\Sublist';
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
