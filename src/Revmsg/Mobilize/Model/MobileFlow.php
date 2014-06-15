<?php

namespace Revmsg\Mobilize\Model;

class MobileFlow extends Scheme
{
    protected $vars = array(
        'account' => '',
        'campaign' => '',
        'frequency' => '',
        'group' => '',
        'headNodes' => '',
        'id' => '',
        'name' => '',
        'programName' => '',
        'shortCode' => '',
        'status' => '',
        'modules' => array()
        );
    protected $APIModels = array(
        'mobileFlowAPIModel' => array(
            'account' => '',
            'campaign' => '',
            'frequency' => '',
            'group' => '',
            'headNodes' => '',
            'id' => '',
            'name' => '',
            'programName' => '',
            'shortCode' => '',
            'status' => ''
            ),
        'editMobileFlowModel' => array(
            'campaign' => array(),
            'id' => '',
            'keywords' => '',
            'mobileFlow' => array(),
            'modules' => array()
            )
        );
}
