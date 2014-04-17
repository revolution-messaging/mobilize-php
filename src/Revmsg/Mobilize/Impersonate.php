<?php
namespace Revmsg\Mobilize;

class Impersonate extends Object\PlatformObject
{
    protected $scheme    =    'Revmsg\Mobilize\Model\Impersonate';
    protected $urls        =    array(
        'v1'        =>    array(
            'create'    =>    'v1/impersonate',
            'delete'    =>    'v1/impersonate'
            )
        );
}
