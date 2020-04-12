<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Firebase\JWT\JWT;
use Validator;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

class Login extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }
        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }
        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    protected function jwt(User $user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function register()
    {
        // Find the user by email
        $prm = $this->request->input();
        $email = User::where('email', $this->request->input('email'))->first();
        if($email){
            return response()->json([
                'status' => 'true',
                'message' => 'Email Sudah Terdaftar'
            ], 200);
        }else{
            $data = new User();
            $data->email = $prm['email'];
            $data->password = password_hash($prm['password'], PASSWORD_BCRYPT);
            $data->name = $prm['name'];
            $data->phone = $prm['phone'];
            $data->sex = $prm['sex'];
            $data->brthdt = $prm['brthdt'];
            $data->save();

            return response(['data' => ['status' => 'true','message' => 'Selamat, Akun Berhasil Dibuat']]);
        }
    }
}