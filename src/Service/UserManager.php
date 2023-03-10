<?php

namespace App\Service;

use App\Entity\User;

class UserManager
{
    public function getAge(User $user) : int
    {
        $date = new \DateTime();
        return $date->diff($user->getDateOfBirth())->y;
    }
}