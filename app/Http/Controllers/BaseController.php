<?php

namespace App\Http\Controllers;

use App\ModelTodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->test = 'Test';
    }


}