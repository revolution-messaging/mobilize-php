<?php
namespace Revmsg\Mobilize\Platform;

class Subscriber extends PlatformObject
{
    public $scheme        =    'Revmsg\Mobilize\Model\subscriber';
    protected $urls        =    array(
        'v1'        =>    array(
            'retrieve'    =>    'v1/subscriber',
            ),
        'v2'        =>    array(
            )
        );
}
