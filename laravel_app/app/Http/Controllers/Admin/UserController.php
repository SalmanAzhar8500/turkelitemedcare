    public function types()
    {
        return view('admin.users.types');
    }

    public function all()
    {
        return view('admin.users.all');
    }

    public function active()
    {
        return view('admin.users.active');
    }

    public function block()
    {
        return view('admin.users.block');
    }
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }
}
