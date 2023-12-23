<?php

namespace App\Http\Controllers;

use App\Subscriber;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers',
        ]);

        $subscriber = Subscriber::create([
            'email' => $request->email,
        ]);

        Toastr::success('You have been successfully added to our subscriber list :)', 'Success');

        return redirect()->back();
    }
}

