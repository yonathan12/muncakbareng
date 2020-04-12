<?php

namespace App\Http\Controllers;

class MuncakBareng extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        
    // }

    public function index()
    {

        die(print_r(['extends basecontroller', $this->test],1));
    }
}