<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UsersCollection;
use App\Users;

class UsersController extends Controller
{
    public function index()
    {
		return response()->json(Users::fetchAllUsers());
    }

    public function update( Request $request )
    {
		$user= Users::find( ( int ) $request->get('user_id'));
		
		$user->user_type_id = ( int ) $request->get('user_type_id');

		$user->save();

		return response()->json('successfully updated');
    }

}
