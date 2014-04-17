<?php
namespace Revmsg\Mobilize;

class Subscriber extends Object\PlatformObject
{
    public $scheme        =    '\Revmsg\Mobilize\Model\Subscriber';
    protected $urls        =    array(
        'v1'        =>    array(
            'retrieve'    =>    'v1/subscriber',
            ),
        'v2'        =>    array(
            )
        );
}
