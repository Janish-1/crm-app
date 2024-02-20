<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show()
    {
        return " All users are shown here";
    }

    public function update(Request $request, $id)
    {
        return " All updates on users are done here";
    }
}
