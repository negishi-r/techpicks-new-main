<?php

namespace App\Http\Controllers;

use App\UseCases\Dashboard\DashboardAction;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;


class DashboardController extends Controller
{
    /**
     * @param DashboardAction $action
     * @return ViewFactory|View
     */
    public function __invoke(DashboardAction $action): ViewFactory|View
    {
        $articles = $action();

        return view('dashboard', compact('articles'));
    }
}
