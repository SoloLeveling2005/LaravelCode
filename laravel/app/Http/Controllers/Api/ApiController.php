<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;

class ApiController extends Controller
{
    // Доделать
    public function films(Request $request) {
        $page = $request->get('page', 1);
        $size = $request->get('size', 10);
        $sortBy = $request->get('sortBy', 'name');  // name, year, raiting
        $sortDir = $request->get('sortDir', 'asc');  // asc, desc
        $country = $request->get('country', 0);
        $category = $request->get('category', 0);
        $films = Film::with('rating')->get();
        
        if ($sortDir == 'asc') {
            if ($sortBy == 'name') {
                $films->sortBy("$sortBy");
            } elseif ($sortBy == 'year') {
                $films->sortBy('year_of_issue');
            } elseif ($sortBy == 'raiting') {
                $films->sortBy('year_of_issue');
            }
        } else {
            $films->sortByDesc("$sortBy");
        }
        

        // name -	Название фильма
        // year -	Год производства фильма
        // rating -	Считает среднее арифметическое значение рейтинга по каждому фильму и сортирует по этому значению.
        // $paginator = new LengthAwarePaginator($items->items(), $items->total(), $items->perPage());
        $data = [
            'page'=>$page,
            'size'=>$size,
            'sortBy'=>$sortBy,
            'sortDir'=>$sortDir,
            'country'=>$country,
            'category'=>$category,
            'films'=>$films
        ];
        dd($data);
        return response()->json($data, 200);
    }
    public function film(Request $request, $id) {
        $film = Film::where(['id'=>$id])->get()->first();
    }
    public function categories(Request $request) {

    }
    public function countries(Request $request) {
        
    }
    public function genders(Request $request) {
        
    }
    public function film_reviews(Request $request, $film_id) {
        
    }

    public function user(Request $request, $id) {
        
    }
    public function users(Request $request) {
        
    }

    public function user_reviews(Request $request, $user_id) {
        
    }
    public function user_review(Request $request, $user_id, $id) {
        
    }

    public function user_ratings(Request $request) {
        
    }
    public function user_rating(Request $request) {
        
    } 
}
