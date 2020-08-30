<?php

namespace App\Http\Controllers;

Use App\Movie;
use App\Http\ResponseCode;
use Illuminate\Http\Request;
use App\Http\Helpers\MovieMethod;
use App\Http\Helpers\ExternalDataControl;

class MovieController extends Controller
{
    public function index(string $viewState = 'error')
    {
        $Movie = MovieMethod::factoryMethod($viewState);
        return $Movie->getMovies($viewState);
    }

    public function create(Request $request)
    {
        $errorData = ExternalDataControl::findError($request, 'movies');
        if($errorData){
            return response()->json($errorData, ResponseCode::HTTP_BAD_REQUEST);
        } else {
            $movie = Movie::createFromRequest($request->all());
            return response()->json($movie, ResponseCode::HTTP_CREATED);
        }
    }

    public function read(string $movie)
    {
        return $movie;
    }

    public function update(Request $request, Movie $movie)
    {
        $movie->update($request->all());

        return response()->json($movie, ResponseCode::HTTP_OK);
    }

    public function delete(Movie $movie)
    {
        $movie->delete();

        return response()->json(null, ResponseCode::HTTP_NO_CONTENT);
    }
}
