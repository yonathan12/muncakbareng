<?php

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', 'Login@index');
$router->post('/register', 'Login@register');

## Route Group
$router->group(
    ['middleware' => 'jwt.auth'],
    function () use ($router) {

        ## Route get all data
        $router->get('/api/{controller}', function ($controller) {
            $controller = ucwords($controller);
            return app('App\Http\Controllers\\' . $controller)->index();
        });

        ## Route get data by Id
        $router->get('/api/{controller}/{id}', function (Request $request, $controller, $id) {
            $controller = ucwords($controller);
            return app('App\Http\Controllers\\' . $controller)->show($request, $id);
        });

        ## Route put data by id
        $router->put('/api/{controller}/{id}', function (Request $request, $controller, $id) {
            $controller = ucwords($controller);
            foreach ($request->input() as $key => $value) {
                if (preg_match("/[']+$/i", $value)) {
                    $error = [
                        'status' => '401',
                        'error' => 'Disallowed Key Characters.'
                    ];       
                    return response($error, 401);
                }
            }
            return app('App\Http\Controllers\\' . $controller)->update($request, $id);
        });

        ## Route delete data by id
        $router->delete('/api/{controller}/{id}', function ($controller, $id) {
            $controller = ucwords($controller);
            return app('App\Http\Controllers\\' . $controller)->destroy($id);
        });

        ## Route post data (create)
        $router->post('/api/{controller}', function (Request $request, $controller) {
            $controller = ucwords($controller);
            foreach ($request->input() as $key => $value) {
                if (preg_match("/[']+$/i", $value)) {
                    $error = [
                        'status' => '401',
                        'error' => 'Disallowed Key Characters.'
                    ];
                    return response($error, 401);
                }
            }
            return app('App\Http\Controllers\\' . $controller)->store($request);
        });
    }
);
