<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\ResponseCode;

class ApiMovieTest extends TestCase
{
    private $urlApi = 'http://localhost:8000/api/movies/';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFactoy()
    {
        //given
        $urlsToTest = [
            'now_playing'   => $this->urlApi.'now_playing',
            'popular'       => $this->urlApi.'popular',
            'upcoming'      => $this->urlApi.'upcoming',
            'my'            => $this->urlApi.'my',
            'category'      => $this->urlApi.'category',
            'error'         => $this->urlApi.'error',
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
        $urlToTest = $this->urlApi.'create';
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
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNDA0MzE0ZTQ5YjNlYmZkOGM5ZjEwZGRlOGUzY2EwNDNmZDI3MWIzYTUwZjg0NzkyNTg1YWI3OGZhMjhkNmMwMmU4NGM1NmZkY2UwOTk5NDAiLCJpYXQiOjE1OTg3NjM3NzAsIm5iZiI6MTU5ODc2Mzc3MCwiZXhwIjoxNjMwMjk5NzcwLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.sQrZa4DbvUsCiMt3Qc8bICW0-VR_pFdFmr5s_1EVZ05lkO19DbCu_AxeFpvL4gloVLAHzIqRthQ2dw-fKMc2NRyASCM61z4rFNHVGyyzRmWj5Yy_dQRNDSe3kTI8BkZNQEcbOZFs5bL9zi4oq2zyTgZSVaHboSmpQmPPk0DrXS96LkPpWJJaLnZ8frDL8gA1Czfckq_rNtSqX2dqVf84MeUNklmLHnq1o_Vex4s9h7l1ZLM_Dra8zd6HqoxIyDRrBvSHuMp8wkjtbSFFz7EbQQM3-eGK_sRj642ikfJmxZVFj_eBAhCHH-PlYyH08XuFE77bBbJmap2Vv4dem-jqvvPBz8vIi8B-HK9Ft6rOKvO5rDA8z2UYa_TiXz8xeFZgEUxoLhK3xs3FgIzZzOX0Qn7db30XY-7oXafXGnzQNj0KhDMiXSBqoYSXjkJrG7CqqUDCUNkwcjEgc3OuZkXqaI6lhNcFPqzTqotk2bXGYaS6Cm4Kbov53WMsw_f9JD1BdPFvbDaToelc4EAZCuXSSuChO9HXgI7Y4plux9GSzgsFalLNt2ANdCmfMYY4-bo2uqF69hhiB-PB5uMohZ1V8fuX3KOU072TN2xV5KtuF3pynM_y7OMOH6YZSr7qNFVHxiNWE859P-wa4_TBS6MUbD8ZI6_pGu4EgUxJVHqttY8';
        $headers = [
            'AUTHORIZATION' => 'Bearer ' . $token
        ];
        
        return $headers;
    }
}
