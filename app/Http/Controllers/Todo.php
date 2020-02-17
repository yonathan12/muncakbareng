<?php

namespace App\Http\Controllers;

use App\ModelTodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Todo extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function index()
    {
        //$data = ModelTodo::all(); // get data all table menggunakan Eloquent ORM Laravel
        $data = DB::select("SELECT id, activity, description from todo"); ## get data menggunakan Raw Query
        if($data){
            $data = 
            ['data' => [
                'status' => 'true',
                'data' => $data
            ]];
        }else{
            $data = 
            ['data' => [
                'status' => 'true',
                'data' => 'Data Tidak Ada'
            ]];
        }
        return response($data);
    }

    public function show(Request $request, $id)
    {
        $method = strtolower($request->method);
        if($method){
            return $this->$method($id);
        }else{
            $data = DB::select("SELECT id, activity, description, filename from todo where id = '$id'");
            if($data){
                $data = 
                ['data' => [
                    'status' => 'true',
                    'data' => $data
                ]];
            }else{
                $data = 
                ['data' => [
                    'status' => 'true',
                    'data' => 'Data Tidak Ada'
                ]];
            }
            return response($data);
        }
    }


    public function store(Request $request)
    {
        $resorce = $request->file('file');
        if($resorce){
            $name = rand().$resorce->getClientOriginalName();
            $resorce->move(\base_path() ."/public/file", $name);
        }else{
            $name = Null;
        }
        

        $data = new ModelTodo();
        $data->activity = $request->input('activity');
        $data->description = $request->input('description');
        $data->filename = $name;
        $data->save();

        return response(['data' => ['status' => 'true','msg' => 'Berhasil Menambah Data']]);
    }

    public function update(Request $request, $id){
        $data = ModelTodo::where('id',$id)->first();
        $data->activity = $request->input('activity');
        $data->description = $request->input('description');
        $data->save();
    
        return response(['data' => ['status' => 'true','msg' => 'Berhasil Merubah Data']]);
    }

    public function destroy($id){
        $data = ModelTodo::where('id',$id)->first();
        $data->delete();
    
        return response(['data' => ['status' => 'true','msg' => 'Berhasil Menghapus Data']]);
    }

    public function download($id)
    {
        $data = DB::select("SELECT filename from todo where id = '$id'");
        $response = response()->download(\base_path() ."/public/file/".$data[0]->filename);
        return $response;
    }
}
