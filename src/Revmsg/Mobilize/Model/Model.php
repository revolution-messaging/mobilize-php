<?php
namespace Revmsg\Mobilize\Model;

interface Model
{
    public function setVariable ($name, $val);
    public function setVariables ($set);
    public function getVariable ($name);
    public function getVariables ();
    public function getOptions ($name);
}
