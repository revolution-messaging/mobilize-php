<?php

namespace Revmsg\Mobilize\Entity;

interface CollectionInterface extends ObjectInterface
{
    public function filter($property, $value, $operator);
    public function findArray($property, $value, $index);
}
