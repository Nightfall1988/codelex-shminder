<?php
namespace App\Collections;
use App\Models\Profile;

class UserCollection
{
    private array $userList = [];
    
    public function add(Profile $profile)
    {
        $this->userList[] = $profile;
    }

    public function getUserList(): array
    {
        return $this->userList;
    }
}