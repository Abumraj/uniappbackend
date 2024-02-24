<?php

namespace App\Http\Controllers\web;
use App\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewbController extends Controller
{
    public function index(Request $request)
    {
        $paginate_count = 10;
        if($request->has('search')){
            $search = $request->input('search');
            $programs = Review::where('name', 'LIKE', '%' . $search . '%')
                           ->paginate($paginate_count);
        }
        else {
            $programs = Review::paginate($paginate_count);
        }
        return view('adminLte.review.index', compact('programs'));
}
}