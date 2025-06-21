<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Field;

class PagesController extends Controller
{
    public function home()
    {
        $featured = Field::orderBy('created_at', 'desc')->take(3)->get();
        return view('home', compact('featured'));
    }

    public function products()
    {
        return view('products');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}
