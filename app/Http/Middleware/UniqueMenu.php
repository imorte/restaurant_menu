<?php

namespace App\Http\Middleware;

use App\Menu;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UniqueMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!preg_match('/(PUT|PATCH)/', $request->method())) {
            // Создание
            $menus = Menu::all();
            $this->checkEntrance($menus, $request);

            return $next($request);
        } else {
            // Обновление
            $menuId = $request->route('menu');
            $menus = Menu::all()->except($menuId);
            $this->checkEntrance($menus, $request);

            return $next($request);
        }

    }

    /**
     * @param Collection $menus
     * @param Request $request
     */
    private function checkEntrance(Collection $menus, Request $request)
    {
        foreach ($menus as $menu) {
            $this->periodIntersect(ts($request->enabledFrom), ts($request->enabledUntil),
                ts($menu->enabledFrom), ts($menu->enabledUntil));
        }
    }

    /**
     * @param $start_one
     * @param $end_one
     * @param $start_two
     * @param $end_two
     */
    private function periodIntersect($start_one, $end_one, $start_two, $end_two)
    {
        if($start_one <= $end_two && $end_one >= $start_two) {
            abort(400);
        }
    }
}
