<?php

namespace App\UseCases\User;

use App\Services\UserService;

class UserShowAction
{
    public function __construct(
        private UserService $userService
    ){}

    /**
     * @param string $userId
     * @param string $loggedInUserId
     * @return array
     */
    public function __invoke(string $userId, string $loggedInUserId): array
    {
        $loggedInUser = $this->userService->find($loggedInUserId);
        $user = $this->userService->find($userId);
        $canUpdate = $loggedInUser->can('update', $user);
        $headerMessage = $canUpdate ? 'マイページ' : $user->name . 'さんのページ';

        return compact('user', 'canUpdate', 'headerMessage');
    }

}
