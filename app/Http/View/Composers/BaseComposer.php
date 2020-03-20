<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class BaseComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('pageName', Route::currentRouteName());
    }
}