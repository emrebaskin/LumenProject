<?php

namespace App\Http\Controllers;


use App\Http\Traits\Jwt;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{

    use Jwt;

    /**
     * The request instance.
     *
     * @var Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function register()
    {

        try {

            //validate incoming request
            $this->validate($this->request, [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]);

            $user = new User;
            $user->name = $this->request->input('name');
            $user->email = $this->request->input('email');
            $password = $this->request->input('password');
            $user->password = app('hash')->make($password);
            $user->save();

            //return successful response
            return response()->json(['token' => $this->jwt($user)], 201);

        } catch (Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }

}
