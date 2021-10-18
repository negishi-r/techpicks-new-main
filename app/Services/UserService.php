<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

final class UserService
{

    /**
     * @param User $user
     */
    public function __construct(
        private User $user
    ) {}

    /**
     * 一件取得
     * @param int $id
     */
    public function find(int $id)
    {
        return $this->user->find($id);
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
