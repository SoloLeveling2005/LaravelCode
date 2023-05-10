<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Rating;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Film;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    //    todo не сделан
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
        $film = Film::with(['ratings','categories','country','reviews'])->where(['id'=>$id])->first();
        if (!$film) {
            return response()->json([
                'status'=>'error',
                'message'=>'Film not found'
            ], 404);
        }
        $categories = collect($film->categories)->map(function ($category) {
            return [
                'id'=>$category->id,
                'name'=>$category->name
            ];
        });
        $reviewCount = count($film->reviews);
        $ratingAvg_array = [];
        foreach ($film->ratings as $rating) {
            $ratingAvg_array[] = $rating->ball;
        }
        $sum = array_sum($ratingAvg_array);
        $count = count($ratingAvg_array);
        $ratingAvg = $sum / $count;

        return response()->json([
            "id"=> $film->id,
            "name"=> $film->name,
            "duration"=> $film->duration,
            "year_of_issue"=> $film->year_of_issue,
            "age"=> $film->age,
            "link_img"=> $film->link_img,
            "link_kinopoisk"=> $film->link_kinopoisk,
            "link_video"=> $film->link_video,
            "created_at"=> $film->created_at,
            "country"=> [
                "id"=> $film->country->id,
                "name"=> $film->country->name
            ],
            "categories"=> $categories,
            "ratingAvg"=> $ratingAvg,
            "reviewCount"=> $reviewCount
        ],200);
    }
    public function categories(Request $request) {
        $reviews = collect(Review::with(['user','films'])->get())->map(function ($review) {
            $filmCount = 0;
            foreach ($review->films as $films) {
                $filmCount += $films->count(); // Подсчет количества фильмов и сохранение в массиве по имени категории
            }
            return [
                "id"=> $review,
                "name"=> $review->name,
                "parentCategory"=> Review::where(['parent_id'=>$review->parent_id])->first(),
                "filmCount"=> $filmCount
            ];
        });
        return response()->json([
            "reviews"=> $reviews
        ], 200);
    }
    public function countries(Request $request) {
        $countries = Country::all();
        $countries = collect($countries)->map(function ($country) {
            return [
                "id"=> $country->id,
                "name"=> $country->name,
                "filmCount"=> count(Film::where(['country_id'=>$country->id])->get())
            ];
        });
        return response()->json([
            'countries'=>$countries
        ], 200);
    }
    public function genders(Request $request) {
        $genders = Gender::all();
        $genders = collect($genders)->map(function ($gender) {
            return response()->json([
                "id"=> $gender->id,
                "name"=> $gender->name
            ]);
        });
        return response()->json([
            'genders'=>$genders
        ]);
    }

    public function film_reviews(Request $request, $film_id) {
        $film = Film::with(['categories','reviews'])->where(['id'=>$film_id])->first();

        if ($film) {
            return response()->json([
                "message"=> "Film not found"
            ], 404);
        }
        $reviews = collect($film->reviews)->map(function ($review) use ($film) {
            return [
                "id"=> $review->id,
                "user"=> [
                    "id"=> $review->user()->id,
                    "fio"=> $review->user()->fio
                ],
                "message"=> $review->message,
                "created_at"=> $review->created_at,
            ];
        });
        return response()->json([
            "reviews"=> $reviews
        ], 200);

    }

    public function user(Request $request, $id) {
        if ($request->method() == "GET") {
            $user = User::with(['gender','reviewCount','ratingCount'])->where(['id'=>$id])->first();
            if ($user) {
                $gender = (object) $user->gender;
                $reviewCount = $user->reviewCount;
                $ratingCount = $user->ratingCount;

                return response()->json([
                    "id"=> $user->id,
                    "fio"=> $user->fio,
                    "email"=> $user->email,
                    "birthday"=> $user->birthday,
                    "gender"=> [
                        "id"=> $gender->id,
                        "name"=> $gender
                    ],
                    "reviewCount"=> $reviewCount,
                    "ratingCount"=> $ratingCount,
                ], 200);
            }
            return response()->json([
                'status'=>'error',
                'message'=>'User not found'
            ], 404);
        }
        return response()->json([
            'status'=>'error',
            'message'=>'Method not allowed'
        ], 405);

    }
    public function users(Request $request) {
        if ($request->method() == "PUT") {
            $valid_data = $request->validate([
                'fio'=>['required','min:2','max:150'],
                'email'=>['required','email','unique','min:4','max:50'],
                'birthday'=>['required','data'],
                'gender_id'=>['required','exists:genders,id'],
            ]);
            $user = Auth::user();
            $user = User::where(['id'=>$user->getAuthIdentifier()])->first();
            $user->fio = $valid_data['fio'];
            $user->email = $valid_data['email'];
            $user->birthday = $valid_data['birthday'];
            $user->gender_id = $valid_data['gender_id'];
            $user->save();

            return response()->json([
                'status'=>'success'
            ],200);
        } elseif ($request->method() == "DELETE") {
            User::where(['id'=>Auth::user()->getAuthIdentifier()])->delete();
            return response()->json([], 204);
        }
        return response()->json([
            'status'=>'error',
            'message'=>'Method not allowed'
        ], 405);
    }

    public function user_reviews(Request $request, $user_id) {
        if ($request->method() == "GET") {
            $user = User::where(['id'=>$user_id])->first();
            if ($user) {
                $reviews = Review::where(['user_id'=>$user_id])->get();
                $data = collect($reviews)->map(function ($review){
                    $film = $review->film();
                    return [
                        "id"=> $review->id,
                        "film"=> [
                            "id"=> $film->id,
                            "name"=> $film->name
                        ],
                        "message"=> $review->message,
                        "is_approved"=> $review->is_approved,
                        "created_at"=> $review->created_at
                    ];
                });
                return response()->json([
                    "reviews"=> $data
                ], 200);
            }
            return response()->json([
                'status'=>'error',
                'message'=>'User not found'
            ], 404);
        } elseif ($request->method() == "POST") {
            $valid_data = $request->validate([
                'film_id'=>['required', 'exists:reviews,id'],
                'message'=>['required', 'min:4','max:1024']
            ]);
            $film = Film::where(['id'=>$valid_data['film_id']])->first();
            $review = Review::create([
                'film_id'=>$valid_data['film_id'],
                'user_id'=>$user_id,
                'message'=>$valid_data['message']
            ]);
            if ($film) {
                return response()->json([
                    'id'=>$review->id,
                    "film"=> [
                        "id"=> $film->id,
                        "name"=> $film->name
                    ],
                    "message"=> $review->message,
                    "is_approved"=> 0,
                    "created_at"=> $review->created_at
                ],201);
            }
            return response()->json([
                'status'=>'error',
                'message'=>'Film not found'
            ], 404);
        }
        return response()->json([
            'status'=>'error',
            'message'=>'Method not allowed'
        ], 405);
    }
    public function user_review(Request $request, $user_id, $id) {
        if ($request->method()=="DELETE") {
            $user = User::where(['id'=>$user_id])->first();
            if (!$user) {
                return response()->json(["message"=> "User not found"], 404);
            }
            $review = Review::where(['id'=>$id])->fisrt();
            if (!$review) {
                return response()->json(["message"=> "Review not found"], 404);
            }
            return response()->json([], 204);
        }
        return response()->json([
            'status'=>'error',
            'message'=>'Method not allowed'
        ], 405);
    }

    public function user_ratings(Request $request, $user_id) {
        $user = User::where(['id'=>$user_id])->first();
        if (!$user) {
            return response()->json(["message"=> "User not found"], 404);
        }
        if ($request->method() == "GET") {
            $ratings = Rating::where(['user_id'=>$user_id])->get();
            $data = collect($ratings)->map(function ($rating){
                $film = $rating->film();
                return [
                    "id"=> $rating->id,
                    "film"=> [
                        "id"=> $film->id,
                        "name"=> $film->name
                    ],
                    "score"=> $rating->ball,
                    "created_at"=> $rating->created_at
                ];
            });
            return response()->json([
                "ratings"=> $data
            ], 200);
        } elseif ($request->method() == "POST") {
            $valid_data = $request->validate([
                'film_id'=>['required', 'exists:ratings,id'],
                'ball'=>['required','min:1','max:5']
            ]);

            if (Rating::where(['user_id'=>$user->getAuthIdentifier(), 'film_id'=>$valid_data['film_id']])->first()->ball) {
                return response()->json([
                    "status"=> "invalid",
                    "message"=> "Score exist"
                ]);
            }
            $rating = Rating::create([
                'film_id'=>$valid_data['film_id'],
                'user_id'=>$user->getAuthIdentifier(),
                'ball'=>$valid_data['ball']
            ]);

            return response()->json([
                "id"=> $rating->id,
                "film"=> [
                    "id"=> $rating->film()->id,
                    "name"=> $rating->film()->name
                ],
                "score"=> $rating->ball,
                "created_at"=> $rating->created_at
            ], 201);
        }
        return response()->json([
            'status'=>'error',
            'message'=>'Method not allowed'
        ], 405);

    }
    public function user_rating(Request $request, $user_id, $id) {
        if ($request->method() == "DELETE") {
            $user = User::where(['id'=>$user_id])->first();
            if (!$user) {
                return response()->json(["message"=> "User not found"], 404);
            }
            $rating = Rating::where(['id'=>$id, 'user_id'=>$user_id])->fisrt();
            if ($rating) {
                $rating->delete();
                return response()->json([], 204);
            }
            return response()->json([
                "message"=> "Rating not found"
            ], 404);
        }
        return response()->json([
            'status'=>'error',
            'message'=>'Method not allowed'
        ], 405);
    }
}
