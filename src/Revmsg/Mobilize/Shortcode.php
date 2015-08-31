<?php
namespace Revmsg\Mobilize;

class Shortcode extends Entity\Entity
{
    protected $scheme = 'Revmsg\Mobilize\Model\Shortcode';
    protected $customMap = array(
        'v1' => array(
                'retrieve' => array(
                    'url' => 'v1/shortcode/%s'
                    ),
                'session' => array(
                    'url' => 'v1/shortcode/sessionshortcode/%s',
                    'payload' => array(
                        'url' => array(
                            'id'
                            )
                        ),
                    'method' => 'POST'
                    )
            )
    );
}
