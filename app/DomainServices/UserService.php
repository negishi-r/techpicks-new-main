<?php

declare(strict_types=1);

namespace App\DomainServices;

use App\Models\User;

final class UserService
{
    /** @var User */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(
        User $user
    ) {
        $this->$user = $user;
    }

    /**
     * 一件取得
     * @param int $id
     */
    public function find(int $id) {
        $this->user->find($id);
    }

    /**
     * @param string $name
     * @param string $email
     */
    public function update(string $name, string $email): Void
    {
        $this->user->update([
            'name' => $name,
            'email' => $email,
        ]);
    }

}
