<?php

namespace Revmsg\Mobilize\Model;

class Messaging extends Scheme
{
    protected $vars = array();
    protected $APIModels = array(
        'MessagingAPIModel' => array(
            'campaign' => '',
            'filteredLists' => array(),
            'frequency' => '',
            'id' => '',
            'message' => '',
            'metadata' => '',
            'mobileFlow' => '',
            'modules' => array(),
            'msisdns' => array(),
            'programName' => '',
            'schedule' => '',
            'segments' => array(),
            'shortCode' => '',
            'subscribers' => array(),
            'tags' => array(),
            )
        );
}
