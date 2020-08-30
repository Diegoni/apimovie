<?php
use App\Genre;
use Illuminate\Database\Seeder;
use App\Http\Helpers\ClientMoviesApi;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Genre::truncate();

        
        $genres = ClientMoviesApi::getDataFromApi('category');
        $genres = json_decode($genres);

        // And now, let's create a few articles in our database:
        foreach($genres->genres as $genre){
            
            Genre::create([
                'genre' => $genre->name,
                'id' => $genre->id,
            ]);
        }
    }
}
