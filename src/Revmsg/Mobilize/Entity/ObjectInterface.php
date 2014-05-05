<?php
namespace Revmsg\Mobilize\Entity;

interface ObjectInterface
{
    
    public function operation($operation, $version, $session);
    public function __construct($signifier);
}
