<?php
namespace Revmsg\Mobilize\Model;

class   Subscriber extends scheme{
    protected $options  =   array();
    protected $vars     =   array(
        'id'            =>  null,
        'blacklist'     =>  array(),
        'mobilePhoneNo'     =>  null,
        'subscriberMetaData'    =>  array(),
        'listDetails'       =>  array()
        );
}