<?php
namespace Revmsg\Mobilize\Model;

class Scheme implements Model
{
    protected $isDirty = true;
    
    public function isDirty()
    {
        return $this->isDirty;
    }
    
    public function setVariable ($name, $val)
    {
        if (key_exists($name, $this-> vars) && (!key_exists($name, $this-> options) || in_array($val, $this-> options[$name]))) {
            $this-> vars[$name] = $val;
            $this-> isDirty = true;
            return true;
        } else {
            return false;
        }
    }
    
    public function setVariables ($set)
    {
        foreach ($set as $name => $value) {
            $this-> setVariable($name, $value);
        }
    }
                                                                     
    public function getVariable ($name)
    {
        if (key_exists($name, $this-> vars)) {
            return $this-> vars[$name];
        } else {
            return false;
        }
    }
    
    public function getVariables    ()
    {
        return $this-> vars;
    }
        
    public function getOptions  ($name)
    {
        if (key_exists($name, $this-> options)) {
            return $this-> options[$name];
        }
    }

    public function dirtify ($dirty = true)
    {
        $this->isDirty = $dirty;
    }
}
