<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class Profile extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index()
    {
        $profile = User::find($this->uid);
        $result = [
            'status' => true,
            'data' => $profile
        ];
        return $result;
    }

    public function store(Request $request){
        $id = $this->uid;
        $prm = $request->input();
        if (isset($prm['methods'])) {
            $methods = $prm['methods'];
            return $this->$methods($request, $id);
        }

        $resorce = $request->file('img');
        $this->validate($request, [
            'email'     => 'required|email',
            'name'  => 'required',
            'phone'  => 'required',
            'sex'  => 'required',
            'brthdt'  => 'required'
        ]);
        $data = [
            'email' => $prm['email'],
            'name' => $prm['name'],
            'phone' => $prm['phone'],
            'sex' => $prm['sex'],
            'brthdt' => $prm['brthdt']
        ];

        if($resorce){
            $filename = $resorce->getClientOriginalName();
            $filename = explode(".", $filename);
            $filename = time().'.'.$filename[1];
            
            $resorce->move(\base_path() ."/public/image", $filename);
            $data = array_merge($data, ['img' => $filename]);
        }else{
            $data = $data;
        }

        try {
            User::where('id', $id)->update($data);
            $result = ['status' => 'true', 'message' => 'Berhasil Memperbarui Profile'];
            return response($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update_password($request, $id)
    {
        $prm = $request->input();
        try {
            $data = User::where('id', $id)->first();
            $data->password = password_hash($prm['password'], PASSWORD_BCRYPT);
            $data->save();
            $result = ['status' => 'true', 'message' => 'Berhasil Memperbarui Password'];
            return response($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}