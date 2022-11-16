<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use TCG\Voyager\Models\Page;

class PageApiController extends Controller
{
    public function show($id) {
        
        $page = Page::where('id',$id)->orWhere('slug',$id)->first();
        $res['data'] = $page;

        return response($res, 201);
    }
}
