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
        $movie = MovieMethod::factoryMethod($viewState);
        return $movie->getMovies($viewState);
    }

    public function create(Request $request)
    {
        $errorData = ExternalDataControl::findError($request, 'movies');
        if($errorData){
            return response()->json($errorData, ResponseCode::HTTP_BAD_REQUEST);
        } else {
            $movieResponse = Movie::createFromRequest($request->all());
            return response()->json($movieResponse, ResponseCode::HTTP_CREATED);
        }
    }

    public function read(string $movie)
    {
        $movieResponse = Movie::read($movie);
        return response()->json($movieResponse, ResponseCode::HTTP_OK);
    }
}
