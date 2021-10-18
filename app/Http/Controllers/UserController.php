<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\UseCases\User\UserShowAction;
use App\UseCases\User\UserUpdateAction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @param string $userId
     * @param UserShowAction $action
     * @return ViewFactory|View
     */
    public function show(string $userId, UserShowAction $action)
    {
        $data = $action($userId, Auth::id());
        return view('user.show', $data);
    }

    /**
     * @param User $user
     * @return ViewFactory|View
     * @throws AuthorizationException
     */
    public function edit(User $user)
    {
        // 更新可能なユーザーかどうかの認証を行う(権限のない場合は403を返す)
        $this->authorize('update', $user);

        return view('user.edit', compact('user'));
    }

    /**
     * @param UpdateRequest $request
     * @param string $userId
     * @param UserService $userService
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, string $userId, UserUpdateAction $action): RedirectResponse
    {
        $user = $action($userId, $request->input('name'), $request->input('email'));

        $request->session()->flash('status', 'プロフィールを更新しました');

        return redirect()->route('user.edit', ['user' => $user]);
    }
}
