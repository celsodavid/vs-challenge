<?php


namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function show()
    {
        $users = User::all();
        return response()->json($users);
    }
}
