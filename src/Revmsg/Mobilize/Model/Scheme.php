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
        }
    }
    
    public function getVariables    ()
    {
        return $this-> vars;
    }

    public function buildPayload    ()
    {
        $payload = array();
        foreach ($this->vars as $name => $val) {
            if (!empty($val)) {
                $payload[$name] = $val;
            }
        }
        if (!empty($payload)) {
            return($payload);
        } else {
            return false;
        }
    }

    // NEEDS A METHOD TO UNSET ALL FIELDS IN THE RESPONSE MODEL THAT ARE NOT IN THE REQUEST MODEL
    
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
