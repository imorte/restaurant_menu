<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response|static[]
     */
    public function index()
    {
        $products = Product::with('menu')->get();

        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $menu = Menu::find($input['menu_id']);

        if($menu) {
            try {
                Product::create($input);
            } catch (QueryException $e) {
                if($e->errorInfo[1] == 1062) {
                    abort(409);
                }
            }
        } else {
            abort(400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response|static[]
     */
    public function show($id)
    {
        $product = Product::with('menu')->where('id', $id)->get();

        if($product->isEmpty()) abort(404);

        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $menuId = Menu::find($input['menu_id']);

        if(!$menuId) abort(422);

        $product = Product::find($id);

        if($product) {
            try {
                $product->update($input);
            } catch (QueryException $e) {
                if($e->errorInfo[1] == 1062) {
                    abort(409);
                }
            }
        } else {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Product::destroy($id)) return abort(404);
    }
}
