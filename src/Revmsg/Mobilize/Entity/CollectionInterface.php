<?php

namespace Revmsg\Mobilize\Entity;

interface CollectionInterface extends ObjectInterface
{
    public function next($inc);
    public function prev($inc);
    public function size($size);
    public function page($index);
    public function findOne($property, $value, $strict);
}
