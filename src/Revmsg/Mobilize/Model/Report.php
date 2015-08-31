<?php

namespace Revmsg\Mobilize\Model;

class Report extends Scheme
{
    protected $vars = array(
        'collection'        => array(),
        'beginOn'           => null,
        'endOn'             => null,
        'bucketSize'        => null,
        'summaryType'       => null,
        'timeframe'         => null,
        'summaryObjectType' => null,
        'summaryObjectId' => null,
        'page'              => 1,
        'size'              => 15,
        'total'             => 0,
        'element'           => '',
        'type'              => ''
        );
    public function collect()
    {
        return new \Revmsg\Mobilize\Collection(
            array(
                'collection' => $this->getVariable('collection')
                )
        );
    }
}
