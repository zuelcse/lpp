<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    View::composer(['layouts.sections.menu.verticalMenu'], function ($view) {
      $menuPath = base_path('resources/menu/verticalMenu.json');
      $cacheKey = 'vertical_menu_data_' . @filemtime($menuPath);
      $verticalMenuData = Cache::remember($cacheKey, 86400, function () use ($menuPath) {
        return json_decode(file_get_contents($menuPath));
      });

      $view->with('menuData', [$verticalMenuData]);
    });
  }
}
