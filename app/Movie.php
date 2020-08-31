<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\MoviesGenres;
use App\Genres;

class Movie extends Model
{
    protected $fillable = [
        'popularity',
        'vote_count',
        'video',
        'poster_path',
        'id',
        'movies.id',
        'adult',
        'backdrop_path',
        'original_language',
        'original_title',
        'title',
        'vote_average',
        'overview',
        'release_date',
    ];

    public static function createFromRequest($request)
    {
        $genreArray = $request['genre_ids'];
        unset($request['genre_ids']);
        $movie = Movie::create($request);
        
        $genres = Genre::select('id', 'genre_id')->get()->keyBy('id');
        foreach($genreArray as $id){
            MoviesGenres::create([
                "movie_id" => $movie->id,
                "genre_id" => $genres[$id]->genre_id,
            ]);
        }
        
        $movieReturn = new \stdClass();
        $movieReturn->id = $movie->id;
        $movieReturn->created = true;
        
        return $movieReturn;
    }

    public static function read($movie_id){
        return DB::table('movies')
         ->select([
            'popularity',
            'vote_count',
            'video',
            'poster_path',
            'movies.id',
            'adult',
            'backdrop_path',
            'original_language',
            'original_title',
            'title',
            'vote_average',
            'overview',
            'release_date',
            DB::raw('GROUP_CONCAT(genres.id) as genre_ids')
        ])
        ->leftJoin('movies_genres', 'movies.movie_id', '=', 'movies_genres.movie_id')
        ->leftJoin('genres', 'movies_genres.genre_id', '=', 'genres.genre_id')
        ->groupBy('movies.movie_id')
        ->where('movies.movie_id', $movie_id)
        ->get();
    }

    public static function get($viewState = null)
    {
        $query = DB::table('movies')
        ->select([
            'popularity',
            'vote_count',
            'video',
            'poster_path',
            'movies.id',
            'adult',
            'backdrop_path',
            'original_language',
            'original_title',
            'title',
            'vote_average',
            'overview',
            'release_date',
            DB::raw('GROUP_CONCAT(genres.id) as genre_ids')
        ])
        ->join('movies_genres', 'movies.movie_id', '=', 'movies_genres.movie_id')
        ->join('genres', 'movies_genres.genre_id', '=', 'genres.genre_id')
        ->groupBy('movies.movie_id');
        
        if($viewState != null){
            $query->where('status', $viewState);
        }
        
        $movies = $query->get();
        
        foreach($movies as $movie) {
            $movie->genre_ids = array_map('intval', explode(',', $movie->genre_ids));
        }
        
        return $movies;
    }
}
