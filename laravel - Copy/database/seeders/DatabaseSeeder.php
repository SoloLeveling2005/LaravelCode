<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Review;
use App\Models\Rating;
use App\Models\Gender;
use App\Models\Film;
use App\Models\Country;
use App\Models\Category;
use App\Models\Category_film;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $countries = ['Казахстан','Россия','Китай', 'США', "Франция"];
        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name'=>$country
            ]);
        };

        $films = [
            [
                'name'=>'Дин дома',
                'country_id'=>4,
                'duration'=>103,
                'year_of_issue'=>1990,
                'age'=>0,
                'link_img'=>'https://avatars.mds.yandex.net/get-kinopoisk-image/6201401/022a58e3-5b9b-411b-bfb3-09fedb700401/300x450',
                'link_kinopoisk'=>'https://www.kinopoisk.ru/film/8124/',
                'link_video'=>'https://www.youtube.com/embed/bBU_64CTNsw',
                'created_at'=>now(),
                'updated_at'=>now()
            ],
            [
                'name'=>'Операция "Фортуна"',
                'country_id'=>4,
                'duration'=>114,
                'year_of_issue'=>2023,
                'age'=>18,
                'link_img'=>'https://avatars.mds.yandex.net/get-kinopoisk-image/6201401/f9cf62b9-074d-4022-8e6b-8683c8f18318/300x450',
                'link_kinopoisk'=>'https://www.kinopoisk.ru/film/8124/https://www.kinopoisk.ru/film/1405508/',
                'link_video'=>'https://www.youtube.com/embed/oEHBI81JaQ8',
                'created_at'=>now(),
                'updated_at'=>now()
            ],
            [
                'name'=>'Король и шут',
                'country_id'=>2,
                'duration'=>50,
                'year_of_issue'=>2023,
                'age'=>18,
                'link_img'=>'https://avatars.mds.yandex.net/get-kinopoisk-image/6201401/1485ac9a-7796-470b-a3eb-85dc725d4ec0/300x450',
                'link_kinopoisk'=>'https://www.kinopoisk.ru/series/4647040/',
                'link_video'=>'https://www.youtube.com/embed/XZqRYNbdB0g',
                'created_at'=>now(),
                'updated_at'=>now()
            ]
        ];

        foreach ($films as $film) {
            DB::table('films')->insert([
                'name'=>$film['name'],
                'country_id'=>$film['country_id'],
                'duration'=>$film['duration'],
                'year_of_issue'=>$film['year_of_issue'],
                'age'=>$film['age'],
                'link_img'=>$film['link_img'],
                'link_kinopoisk'=>$film['link_kinopoisk'],
                'link_video'=>$film['link_video'],
                'created_at'=>$film['created_at'],
                'updated_at'=>$film['updated_at']
            ]);
        };

        $genders = ['man', 'woman'];
        foreach ($genders as $gender) {
            DB::table('genders')->insert([
                'name'=>$gender
            ]);
        };

        $users = [
            [
                'fio'=>"Uldanov Mansur",
                'birthday'=>'2005-05-09',
                'gender_id'=>1,
                'email'=>'Example1@mail.ru',
                'password'=>Hash::make("example1"),
                'created_at'=>now()
            ],
            [
                'fio'=>"Second People",
                'birthday'=>'2001-05-09',
                'gender_id'=>2,
                'email'=>'Example2@mail.ru',
                'password'=>Hash::make("example2"),
                'created_at'=>now()
            ],
            [
                'fio'=>"Third People",
                'birthday'=>'2007-05-09',
                'gender_id'=>2,
                'email'=>'Example3@mail.ru',
                'password'=>Hash::make("example3"),
                'created_at'=>now()
            ],
        ];
        foreach ($users as $user) {
            DB::table('users')->insert([
                'fio'=>$user['fio'],
                'birthday'=>$user['birthday'],
                'gender_id'=>$user['gender_id'],
                'email'=>$user['email'],
                'password'=>$user['password'],
                'created_at'=>$user['created_at'],
            ]);
        };

        $categories = [
            [
                'name'=>'Боевик',
            ],
            [
                'name'=>'Комедия',
            ],
            [
                'name'=>'Триллер',
            ]
        ];
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name'=>$category['name']
            ]);
        };
        $categories_films = [
            [
                'category_id'=>2,
                'film_id'=>1
            ],
            [
                'category_id'=>1,
                'film_id'=>2
            ],
            [
                'category_id'=>3,
                'film_id'=>3
            ],
        ];
        foreach ($categories_films as $category_film) {
            DB::table('category_films')->insert([
                'category_id'=>$category_film['category_id'],
                'film_id'=>$category_film['film_id']
            ]);
        };
        $reviews = [
            [
                'film_id'=>1,
                'user_id'=>1,
                'message'=>"Example text message review 1",
                'created_at'=>now(),
                'is_approved'=>random_int(3, 10)
            ],
            [
                'film_id'=>1,
                'user_id'=>2,
                'message'=>"Example text message review 2",
                'created_at'=>now(),
                'is_approved'=>random_int(3, 10)
            ],
            [
                'film_id'=>2,
                'user_id'=>1,
                'message'=>"Example text message review 3",
                'created_at'=>now(),
                'is_approved'=>random_int(3, 10)
            ],
            [
                'film_id'=>3,
                'user_id'=>2,
                'message'=>"Example text message review 4",
                'created_at'=>now(),
                'is_approved'=>random_int(3, 10)
            ],
        ];
        foreach ($reviews as $review) {
            DB::table('reviews')->insert([
                'film_id'=>$review['film_id'],
                'user_id'=>$review['user_id'],
                'message'=>$review['message'],
                'created_at'=>$review['created_at'],
                'is_approved'=>$review['is_approved'],
            ]);
        };
        $ratings = [
            [
                'film_id'=>1,
                'user_id'=>random_int(1,3),
                'ball'=>random_int(3, 10),
                'created_at'=>now(),
            ],
            [
                'film_id'=>2,
                'user_id'=>random_int(1,3),
                'ball'=>random_int(3, 10),
                'created_at'=>now(),
            ],
            [
                'film_id'=>3,
                'user_id'=>random_int(1,3),
                'ball'=>random_int(3, 10),
                'created_at'=>now(),
            ],
            [
                'film_id'=>1,
                'user_id'=>random_int(1,3),
                'ball'=>random_int(3, 10),
                'created_at'=>now(),
            ]
        ];
        foreach ($ratings as $rating) {
            DB::table('ratings')->insert([
                'film_id'=>$rating['film_id'],
                'user_id'=>$rating['user_id'],
                'ball'=>$rating['ball'],
                'created_at'=>$rating['created_at'],
            ]);
        };
    }
}
