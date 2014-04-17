<?php
namespace Revmsg\Mobilize\Model;

class Metadata extends scheme
{
    protected $options  =   array(
        'status'    =>  array(
            'ACTIVE',
            'INACTIVE'
            ),
        'multiValue'    =>  array(
            true,
            false
            )
        );
    protected $vars     =   array(
        'id'        =>  null,
        'validValues'   =>  array(),
        'scope'     =>  'GROUP',
        'status'    =>  'ACTIVE',
        'name'      =>  null,
        'eventUrl'  =>  null,
        'account'   =>  null,
        'multiValue'    =>  false,
        'format'    =>  null,
        'group'     =>  null
    );
}
