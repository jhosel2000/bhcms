<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the home page
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Display the about page
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the services page
     */
    public function services()
    {
        return view('services');
    }

    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('contact');
    }
}
