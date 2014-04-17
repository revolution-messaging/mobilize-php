<?php
namespace Revmsg\Mobilize\Model;

class   Filter extends scheme{
    protected $options  =   array(); //needs options
    protected $vars     =   array(
        'id'            =>  null,
        'queryFilterDetails'    =>  null,
        'shortCode'     =>  null,
        'name'          =>  null,
        'account'       =>  null,
        'group'         =>  null,
        'lists'         =>  null
        );
}