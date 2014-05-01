<?php

namespace Revmsg\Mobilize\Entity;

interface PageInterface extends ObjectInterface
{
    public function flip($inc);
    public function size($size);
    public function flipTo($index);
    public function all();
    public function fetch();
}
