<?php
use App\Movie;
use App\MoviesGenres;
use App\Genre;

use Illuminate\Database\Seeder;

class MoviesGenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MoviesGenres::truncate();
        $genreArray = [];
        $genres = Genre::->pluck('genre_id')->toArray();
        $movies = Movie::all();
        $faker = \Faker\Factory::create();
 
        foreach ($movies as $movie) {
            $numberOfGenres = $faker->numberBetween(2, 5);
            $genresArray = array_rand($genres, $numberOfGenres);
            for ($i = 1; $i <= $numberOfGenres; $i++) {
                $moviesGenres = new MoviesGenres();
                $moviesGenres->movie_id = $movie->movie_id;
                $moviesGenres->genre_id = $genresArray[$i - 1];
                $moviesGenres->save();
            }            
        }
    }
}
