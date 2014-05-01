<?php
namespace Revmsg\Mobilize\Model;

interface ModelInterface
{
    public function setVariable($name, $val);
    public function setVariables($set);
    public function getVariable($name);
    public function getVariables();
}
