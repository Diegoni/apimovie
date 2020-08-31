<?php

namespace App\Http\Helpers;

use App\Http\Helpers\ClientMoviesApi;
Use App\Movie;
use App\Http\ResponseCode;  
use Illuminate\Http\Request;
use App\ViewState;

// Clase creadora declara el método fábrica que devuelve nuevos objetos
class MovieMethod {
    public static function factoryMethod($method): MovieProductInterface {
        $movieFactory = new MovieFactory();
        return $movieFactory->createMethodMovie($method);
    }
}

// Clase Fabrica para crear los objetos correspondientes dependiendo de la categoria
class MovieFactory {
    public function createMethodMovie(string $method): MovieProductInterface {
        switch ($method) {
            case ViewState::NOW_PLAYING:
                return new nowPlayingMovies();
            case ViewState::POPULAR:
                return new popularMovies();
            case ViewState::UPCOMING:
                return new upcomingMovies();
             case ViewState::MY:
                return new myMovies();
            case ViewState::CATEGORY:
                return new categoryMovies();
            default:
                return new errorStatusMovies();
        }
    }
}

// Interfaz que las clases deberian implementar
interface MovieProductInterface {
    public function getMovies(string $viewState);
}

// Metodos comunes a las clases 
class helpersMovies
{
     public static function getMoviesDefault($viewState)
     {
        $moviesApi = ClientMoviesApi::getDataFromApi("movie/".$viewState);
        $moviesDB = Movie::get($viewState);
        $movies = helpersMovies::formatMovies($moviesDB, $moviesApi);
        return response()->json($movies, ResponseCode::HTTP_OK);
    }

    public static function formatMovies($moviesDB, $moviesApi = null)
    {
        $moviesDB = json_decode($moviesDB);
        $movieReturn = new \stdClass();
        $movieReturn->results = $moviesDB;
        
        if($moviesApi != null)
        {
            $moviesApi = json_decode($moviesApi);
            $movieReturn->results = array_merge($moviesApi->results, $movieReturn->results);
        } 
        return $movieReturn;
    }
}

class nowPlayingMovies implements MovieProductInterface
{
    public function getMovies(string $viewState)
    {
        return helpersMovies::getMoviesDefault($viewState);
    }
}

class popularMovies implements MovieProductInterface
{
    public function getMovies(string $viewState)
    {
        return helpersMovies::getMoviesDefault($viewState);
    }
}

class upcomingMovies implements MovieProductInterface
{
    public function getMovies(string $viewState)
    {
        return helpersMovies::getMoviesDefault($viewState);
    }
}

class myMovies implements MovieProductInterface
{
    public function getMovies(string $viewState)
    {
        $moviesDB = Movie::get();
        $movies = helpersMovies::formatMovies($moviesDB);
        return response()->json($movies, ResponseCode::HTTP_OK);
    }
}

class categoryMovies implements MovieProductInterface
{
    public function getMovies(string $viewState)
    {
        $view = "genre/movie/list";
        $genres = ClientMoviesApi::getDataFromApi($view);
        return response()->json(json_decode($genres), ResponseCode::HTTP_OK);
    }
}

class errorStatusMovies implements MovieProductInterface
{
    public function getMovies(string $viewState)
    {
        $returnJson = [
            "success" => false,
            "status_message" => "The resource you requested could not be found."
        ];
        return response()->json($returnJson, ResponseCode::HTTP_NOT_FOUND);
    }
}
