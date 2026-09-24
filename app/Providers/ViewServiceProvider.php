<?php

namespace App\Providers;

use App\Helpers\RouteHelper;
use App\View\Composers\MenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        // নির্দিষ্ট ভিউর জন্য আলাদা কম্পোজার
        View::composer('theme::partials.header', function ($view) {
            $view->with('cartCount', session()->get('cart_count', 0));
        });

          // হেডার পার্শিয়ালের জন্য মেনু কম্পোজার
        View::composer('theme::partials.header-main', MenuComposer::class);

        view()->composer('admin.sliders.*', function ($view) {
            $view->with('routes', RouteHelper::getDropdownOptions());
        });
    }


    public function register(): void
    {
        //
    }
}
