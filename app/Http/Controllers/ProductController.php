<?php

namespace App\Http\Controllers;

class ProductController extends Controller
{
    /**
     * Display the sample products page.
     */
    public function index()
    {
        return view('products.index');
    }
}