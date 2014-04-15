<?php
namespace Revmsg\Mobilize\Platform;

class Impersonate extends PlatformObject
{
    protected $scheme    =    'Revmsg\Mobilize\Model\impersonate';
    protected $urls        =    array(
        'v1'        =>    array(
            'create'    =>    'v1/impersonate',
            'delete'    =>    'v1/impersonate'
            )
        );
}
