<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class MuncakBareng extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index()
    {
        $tanggal = Carbon::now();
        $curl = $this->curl("https://note.yonathanrizky.com/api/notes");
        return $curl;

        // die(print_r([
        //     'extends basecontroller',
        //     $this->test,
        //     $this->uid
        // ], 1));
    }
}
