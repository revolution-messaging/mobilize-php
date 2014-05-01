<?php

namespace Revmsg\Mobilize\Model;

class Page extends Scheme
{
    protected $vars = array(
        'collection' => array(),
        'page' => 1,
        'size' => 15,
        'total' => 0,
        'orderBy' => '',
        'element' => ''
        );
}
