<?php
use App\Movie;
use Illuminate\Database\Seeder;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Movie::truncate();
        $status = [
            'now_playing',
            'upcoming',
            'popular'
        ];

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 50; $i++) {
            Movie::create([
                'title' => $faker->sentence,
                'overview' => $faker->paragraph,
                'popularity' => $faker->randomFloat(3, 0, 300),
                'vote_count' => $faker->numberBetween(0, 1000),
                'video' => $faker->boolean,
                'poster_path' => $faker->md5.'.jpg',
                'id' => $faker->numberBetween(1000, 9000),
                'adult' =>$faker->boolean,
                'backdrop_path' => $faker->md5.'.jpg',
                'original_language' => $faker->languageCode,
                'original_title' => $faker->jobTitle,
                'vote_average' => $faker->randomFloat(1, 0, 10),
                'release_date' => $faker->date('Y-m-d'),
                'status' => $status[array_rand($status)],
            ]);
        }
    }
}
