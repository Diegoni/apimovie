<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\ResponseCode;
use App\ViewState;

class ApiMovieTest extends TestCase
{
    private $urlApi = 'http://localhost:8000/api/movies/';
    private $urlProd = 'https://apimovielitebox.herokuapp.com/api/movies/';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFactoy()
    {
        //given
        $url = (env('APP_URL') == "http://localhost") ? $this->urlApi : $this->urlProd;
        $urlsToTest = [
            'now_playing'   => $url.ViewState::NOW_PLAYING,
            'popular'       => $url.ViewState::POPULAR,
            'upcoming'      => $url.ViewState::UPCOMING,
            'my'            => $url.ViewState::MY,
            'category'      => $url.ViewState::CATEGORY,
            'error'         => $url.ViewState::ERROR,
        ];
        $headers = $this->getHeaders();

        //when
        $responseNowPlaying = $this->get($urlsToTest['now_playing'], $headers);
        $responsePopular    = $this->get($urlsToTest['popular'], $headers);
        $responseUpcoming   = $this->get($urlsToTest['upcoming'], $headers);
        $responseMy         = $this->get($urlsToTest['my'], $headers);
        $responseCategory   = $this->get($urlsToTest['category'], $headers);
        $responseError      = $this->get($urlsToTest['error'], $headers);
        
        //then
        $responseNowPlaying->assertStatus(ResponseCode::HTTP_OK);
        $responsePopular->assertStatus(ResponseCode::HTTP_OK);
        $responseUpcoming->assertStatus(ResponseCode::HTTP_OK);
        $responseMy->assertStatus(ResponseCode::HTTP_OK);
        $responseCategory->assertStatus(ResponseCode::HTTP_OK);
        $responseError->assertStatus(ResponseCode::HTTP_NOT_FOUND);
    }
    
    public function testCreate()
    {
        //given
        $url = (env('APP_URL') == "http://localhost") ? $this->urlApi : $this->urlProd;
        $urlToTest = $url.'create';
        $headers = $this->getHeaders();
        $movieData = [
            "popularity"    => 59.20,
            "vote_count"    => 950,
            "video"         => false,
            "poster_path"   => "387374d5f22033327cfff1ba1430adfc.jpg",
            "id"            => 8009,
            "adult"         => true,
            "backdrop_path" => "d07401fa82c073300627b86dfec9108d.jpg",
            "original_language" => "mh",
            "original_title" => "Court Reporter",
            "title"         => "Natus et ut deleniti harum.",
            "vote_average"  => 6.40,
            "overview"      => "Provident incidunt eaque est id provident doloribu...",
            "status"        => "now_playing",
            "release_date"  => "1984-09-02",
            "genre_ids"     => [16,18,27]
        ];
        $movieNoFields = $movieData;
        unset($movieNoFields["popularity"]);
        $movieFieldEmpty = $movieData;
        $movieFieldEmpty["poster_path"] = '';
        $movieChageTypes = $movieData;
        $movieChageTypes["popularity"] = 'text';
        $movieMaxSize = $movieData;
        $movieMaxSize['poster_path'] = '387374d5f22033327cfff1ba1430adfc.jpg387374d5f22033327cfff1ba1430adfc.jpg387374d5f22033327cfff1ba1430adfc.jpg387374d5f22033327cfff1ba1430adfc.jpg';
        $movieBadDate = $movieData;
        $movieBadDate['release_date'] = "1984-13-02";
        $movieListError = $movieData;
        $movieListError['genre_ids'] =  [999999,8888888];
        $movieBool = $movieData;
        $movieBool['adult'] =  "trueString";

        //when
        $response = $this->postJson($urlToTest, $movieData, $headers);
        $responseEmpty = $this->postJson($urlToTest, [], $headers);
        $responseNoFields = $this->postJson($urlToTest, $movieNoFields, $headers);
        $responseFieldEmpty = $this->postJson($urlToTest, $movieFieldEmpty, $headers);
        $responseChageTypes = $this->postJson($urlToTest, $movieChageTypes, $headers);
        $responseMaxSize = $this->postJson($urlToTest, $movieMaxSize, $headers);
        $responseBadDate = $this->postJson($urlToTest, $movieBadDate, $headers);
        $responseListError = $this->postJson($urlToTest, $movieListError, $headers);
        $responseBool = $this->postJson($urlToTest, $movieBool, $headers);
        
        //then
        $response->assertStatus(ResponseCode::HTTP_CREATED)->assertJson([ 'created' => true, ]);
        $responseEmpty->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $responseNoFields->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $responseFieldEmpty->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $responseChageTypes->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $responseMaxSize->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $responseBadDate->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $responseListError->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $responseBool->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
    }
    
    public function getHeaders()
    {
        $tokenApi = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjg0ZjlkM2Y5YjdmMDZkOGQ5MGNjMDE1YTdlMTU4OTc4ZDAyNjA0NjRlNWUxNWNjNjRkNmEzMTQ4N2FiMWYxOTFhN2IxZDY1NGExOGZkMWMiLCJpYXQiOjE1OTg4NzI1NTEsIm5iZiI6MTU5ODg3MjU1MSwiZXhwIjoxNjMwNDA4NTUwLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.HNkuF7_ZlIYydwHZELEN90F6Hs8d3SNAG9FlhMe_Rfb0smBUKPOx4Oo46VuO2L7AEqN5pWTX54ZM1XNQpwwUKYuBbWY2x6Us2pZMV2BDt8v4blBK6Ivt6JPQ_D5xvPq2WepSl7wriNtN13WRosVHjRryiV0dJ0Ezr2pshnblV35xj9tuNqyXECF137FKpi21BEDo5XiX0RjvsmaEgBzTZ-YKtct9LQZtLv9ANUVhGm2dTsz4uv1lVwGn6w5ozR2NkVpQ0Vbpl-thW-Kxe4pVmzqX8TMaTSLStj24jRBLPC0sudqbGuJQ4yqq8ICeiGbeAR7_wuff8TT7YhuAVFSoLbfIp6MRHpIyZodFZBP4G-ZREsFRmmCrv87TQiuEMV2QSCRqYLs8Wvxbx_SI86lGjmUQi_mF02Gagcwb-HPGipVvnogy6EeOgNapCbOWNbOiGcvThiFx1c1GMX8Mf9CTkziIHESBU9YDPuVML4dF5LU8twN1zp7BGdsAJ772pSay030sfE3yS7pjhNIF9at-OxXhjbbAnHG3e6FhydqRYr-Xnqk2dlkKipKYY6IqnmxzeZx1WisSKEilpTC20lUG341wxkvI1yF2HI6IG9_HAZAEdpOzE76Xk-eUqc-r9s65DNnd-0MQX1K79-MX_aT-0gn4o389H1zlQorpbK7AL6A';
        $tokenProd = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZWQwMDNjNGE4NTI2MWY3MTU1NGY4MTk2NWNmMzUyNjJiY2FjZWUzMDY3OWYyMjM2OGVhMGM2MGE5NTRkMDRmOWRjNTk4ZDY1NDdlNjAzZWYiLCJpYXQiOjE1OTg4MjQ1MTcsIm5iZiI6MTU5ODgyNDUxNywiZXhwIjoxNjMwMzYwNTE3LCJzdWIiOiIyMSIsInNjb3BlcyI6W119.P_tK7SX99o1JZn05iYVwtKeC6OlXK02uLGrzdJECC3deaFRYA9mdeKCF9Qpw7IBN6m9XV1VaK09yzczVvUd291BzjMJxXGabql-4Ofk_uWlDWc8KHrcJVThP2Dw9gVfgStqUr9rJPry-uUE6fq5wQmdGuHSGWkjNHhiYEcIRz9OZ7QrJpqf8Ub8ytFQ6LiUOz3jlCaXv01TEaYXa1afIRJTDAkC1gw6xwG6NzuB6NtZRobfNFucdLrnJkzKiklDzmqLEfAebhQRBpDHlmzdiXEDilzNd5W_7p-W0qh8tArV9z7mbu6oJc1NmRBDWHfj7oxG8UQd8zYKW2qh-fqdJ0Icl1tNYMnfWUYfqAUJXWxWVw4OOac7_UHSsGjuUX2ZwAYZKTh2v-z3zlWJU3BnK5djm-qLo5haQTudFzydq5Prl20BP76nFg2l-KS38Hu-AMqNl5uIr7d5QH0MeqBltwiE13Q99IzY1sWaoZoFdw-Howdbazw4P8IrAEOy1qQ9gi4W1DBaj7OT43oTssaPjfNy6DP-O6gCEtf683y8ZTehwMDXwEzeDp853NvzMHPeATdkfQfx58GJw9k-moQ5P5t4hMLiEQWmilSCJj0WltaWGx6jgx-71eiR_EISMlkr4W6it7GJ72it5jTOc6KqojrX1jRMBTruv3BBe2EFkhIs';
        $token = (env('APP_URL') == "http://localhost") ? $tokenApi : $tokenProd;
        $headers = [
            'AUTHORIZATION' => 'Bearer ' . $token
        ];
                
        return $headers;
    }
}
