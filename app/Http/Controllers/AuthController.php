<?php

namespace App\Http\Controllers;


use App\Http\Traits\Jwt;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function login()
    {

        try {

            $this->validate($this->request, [
                'email' => 'required|email',
                'password' => 'required'
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
            return response()->json(['error' => 'Email or password is wrong.'], 400);

        } catch (Exception $e) {
            //return error message
            return response()->json(['error' => 'Login Failed!'], 409);
        }

    }

}
