<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TenanciesController;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.refresh');
    }
    public function refreshAll()
    {
    	app('App\Http\Controllers\TenanciesController')->refresh();
    	app('App\Http\Controllers\GoCardLessController')->refresh();
        app('App\Http\Controllers\PropertiesController')->refresh();
    	//app('App\Http\Controllers\YodleeTransactionsController')->refresh();
        return view('admin.refresh');
    }
}
