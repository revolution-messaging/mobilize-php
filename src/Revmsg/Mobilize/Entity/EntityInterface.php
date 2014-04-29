<?php

namespace Revmsg\Mobilize\Entity;

interface EntityInterface extends ObjectInterface
{
    public function isDirty();
    public function delete($version, $session);
    public function update($version, $session);
    public function create($version, $session);
    public function __set($name, $value);
    public function set($name, $value);
    public function __toString();
}
