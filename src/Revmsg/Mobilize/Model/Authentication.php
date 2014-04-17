<?php
namespace Revmsg\Mobilize\Model;

class Authentication extends Scheme
{
    protected $options  =   array();
    protected $vars     =   array(
        'username'  =>  null,
        'password'  =>  null
        );
}
