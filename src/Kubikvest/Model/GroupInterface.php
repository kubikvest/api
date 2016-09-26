<?php

namespace Kubikvest\Model;

interface GroupInterface
{
    public function addUser(User $user);
    public function getTask($uuid);
    public function getNextTask();
}
