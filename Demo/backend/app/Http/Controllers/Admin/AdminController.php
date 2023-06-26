<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use function PHPUnit\Framework\callback;

class AdminController extends Controller
{
    // Return admins page
    public function admins (Request $request) {
        $admins = Admin::all();

        return view('admins', [
            'admins'=>$admins
        ]);
    }

    public function users(Request $request) {
        $users = User::with(['is_ban'])->get();
        return view('users', [
            'users'=>$users
        ]);
    }

    public function games (Request $request) {
        $games = Game::all();
        return view('games', [
            'games'=>$games
        ]);
    }

    public function user(Request $request, $username) {
        $user = User::with(['is_ban'])->where(['username'=>$username])->first();
        if ($user->is_ban) {
            return view(404);
        }
        return view(404);
        return view('user', [
            'user'=>$user
        ]);
    }

    public function game (Request $request, $slug) {
        $game = Game::with(['game_versions'])->where(['slug'=>$slug])->first();
        return view('game', [
            'game'=>$game
        ]);
    }

    public function refresh_game(Request $request) {


    }

    public function delete_game(Request $request) {


    }


}
