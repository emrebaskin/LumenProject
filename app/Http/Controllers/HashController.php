<?php

namespace App\Http\Controllers;

use App\Http\Traits\Jwt;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use RandomHash\RandomHash;

class HashController extends Controller
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

    /**
     * Generate random 101 chars hash
     *
     * @return JsonResponse
     */
    public function generate()
    {

        try {

            //$hash = RandomHash::generate(32);
            $randomHash = new RandomHash();
            $hash = $randomHash->generate(101);
            Log::info("Create new hash: ".$hash);
            //return successful response
            return response()->json(['hash' => $hash], 200);

        } catch (Exception $e) {
            //return error message
            return response()->json(['message' => "An error while creating hash"], 503);
        }

    }

}
