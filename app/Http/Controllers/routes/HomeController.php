<?php

namespace App\Http\Controllers\routes;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CategoriasModel;

class HomeController extends Controller
{
    public static function index()
    {
        return function(){

            return view('Pages.MainPage', [
                'Categorias' => CategoriasModel::getList(),
            ]);
        };
    }
}
