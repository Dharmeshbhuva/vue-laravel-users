<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserTypesCollection;
use App\UserTypes;

class UserTypesController extends Controller
{
    public function index()
    {
		return new UserTypesCollection( UserTypes::all() );
    }

}
