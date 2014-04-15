<?php
namespace Revmsg\Mobilize;

interface Object
{
    public function isDirty();
    public function delete($version, $session);
    public function retrieve($ObjectId, $version, $session);
    public function update($version, $session);
    public function create($version, $session);
    public function __construct($signifier);
    public function __set($name, $value);
    public function set($name, $value);
    public function __toString();
}
