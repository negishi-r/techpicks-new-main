<?php

namespace App\UseCases\User;

use App\Models\User;
use App\Services\UserService;

class UserUpdateAction
{
    public function __construct(
        private UserService $userService
    ){}

    /**
     * @param string $userId
     * @param string $name
     * @param string $email
     * @return array
     */
    public function __invoke(string $userId, string $name, string $email): User
    {
        $this->userService->update($name, $email);
        return $this->userService->find($userId);
    }

}
