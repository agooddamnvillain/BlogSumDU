<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function add($post)
    {
        $user = Auth::user();
        $isFavorite = $user->favorite_posts()->where('post_id', $post)->exists();

        $user->favorite_posts()->toggle($post);

        $message = $isFavorite ? 'removed' : 'added';
        Toastr::success("Post successfully $message from your favorite list :)", 'Success');

        return redirect()->back();
    }
}
