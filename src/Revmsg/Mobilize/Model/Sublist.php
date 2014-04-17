<?php
namespace Revmsg\Mobilize\Model;

class   Sublist extends scheme  {
    protected $options  =   array();
    protected $vars     =   array(
        'id'            =>  null,
        'shortCode'     =>  null,
        'createdBy'     =>  null,
        'status'        =>  null,
        'name'          =>  null,
        'account'       =>  null,
        'group'         =>  null,
        'noOfSubscribers'   =>  null
        );
    // NEEDS TO SPECIFY WHICH FIELDS IN $vars ARE IN RESPONSE MODEL BUT NOT IN REQUEST MODEL
}