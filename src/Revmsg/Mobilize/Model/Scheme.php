<?php
namespace Revmsg\Mobilize\Model;

class Scheme implements ModelInterface
{
    protected $isDirty = true;
    
    public function isDirty()
    {
        return $this->isDirty;
    }
    
    public function setVariable ($name, $val)
    {
        if (key_exists($name, $this-> vars)) {
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
    
    public function getVariables ()
    {
        return $this->vars;
    }

    public function getModel ($model)
    {
        return $this->APIModels[$model];
    }

    public function setModel ($model, $args)
    {
        $this->APIModels[$model] = $args;
    }
        
    public function dirtify ($dirty = true)
    {
        $this->isDirty = $dirty;
    }
}
