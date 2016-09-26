<?php

namespace Kubikvest\Model;

interface UserInterface
{
    public function start();
    public function finish();
    public function getTask($uuid);
    public function getNextTask();
}
