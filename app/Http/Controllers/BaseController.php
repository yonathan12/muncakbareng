<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->test = 'Test';
        $this->uid = $request->get('uid');
    }

    public function curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $headers = [
            'Authorization : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImpyaXpreS5qb25hdGhhbkBnbWFpbC5jb20iLCJpYXQiOjE1ODY1MTk1MDV9.7pJbalz0pp6LE8oxFLhpp-gA2oQJpo-5OSbswkLePkk'
        ];
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($output, TRUE);
        return $res;
    }


}