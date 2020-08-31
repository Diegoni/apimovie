<p>Api en Laravel para poder obtener Movies de themoviedb.org y cargar las propias</p>
<p>Deberias tener postman para poder probarlo, lo puedes descargar desde <a href="https://www.postman.com/" target="_blank">aqui</a></p>

## SIGNUP

Lo primero que debes hacer es crear un usuario para poder hacer las consultas, para hacerlo 

<p>URL: https://apimovielitebox.herokuapp.com/api/auth/signup</p>
<p>METHOD: POST</p>
<p>JSON</p>

- {
-	"name" : "Nieto",
-	"email" : "diego@nieto.com",
-	"password" : "123456",
-	"password_confirmation" : "123456"
- }

Esto creara el usuario en DB.

## LOGIN

Ahora vamos a loguear nuestro usario para obtener los token para hacer las consultas

<p>URL: https://apimovielitebox.herokuapp.com/api/auth/login</p>
<p>METHOD: POST</p>
<p>JSON</p>

- {
-	"email" : "diego@nieto.com",
-	"password" : "123456",
-	"remember_me" : true
- }

Esto nos devolvera dos valores "token_type" y "access_token", con estos valores vamos a armar nuestra "Authorization" para cargarla en los headers en las proximas consultas.
Deberia quedar algo como 
Authorization : "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZWQwMDNjNGE4NTI2MWY3MTU1NGY4MTk2NWNmMzUyNjJiY2FjZWUzMDY3OWYyMjM2OGVhMGM2MGE5NTRkMDRmOWRjNTk4ZDY1NDdlNjAzZWYiLCJpYXQiOjE1OTg4MjQ1MTcsIm5iZiI6MTU5ODgyNDUxNywiZXhwIjoxNjMwMzYwNTE3LCJzdWIiOiIyMSIsInNjb3BlcyI6W119.P_tK7SX99o1JZn05iYVwtKeC6OlXK02uLGrzdJECC3deaFRYA9mdeKCF9Qpw7IBN6m9XV1VaK09yzczVvUd291BzjMJxXGabql-4Ofk_uWlDWc8KHrcJVThP2Dw9gVfgStqUr9rJPry-uUE6fq5wQmdGuHSGWkjNHhiYEcIRz9OZ7QrJpqf8Ub8ytFQ6LiUOz3jlCaXv01TEaYXa1afIRJTDAkC1gw6xwG6NzuB6NtZRobfNFucdLrnJkzKiklDzmqLEfAebhQRBpDHlmzdiXEDilzNd5W_7p-W0qh8tArV9z7mbu6oJc1NmRBDWHfj7oxG8UQd8zYKW2qh-fqdJ0Icl1tNYMnfWUYfqAUJXWxWVw4OOac7_UHSsGjuUX2ZwAYZKTh2v-z3zlWJU3BnK5djm-qLo5haQTudFzydq5Prl20BP76nFg2l-KS38Hu-AMqNl5uIr7d5QH0MeqBltwiE13Q99IzY1sWaoZoFdw-Howdbazw4P8IrAEOy1qQ9gi4W1DBaj7OT43oTssaPjfNy6DP-O6gCEtf683y8ZTehwMDXwEzeDp853NvzMHPeATdkfQfx58GJw9k-moQ5P5t4hMLiEQWmilSCJj0WltaWGx6jgx-71eiR_EISMlkr4W6it7GJ72it5jTOc6KqojrX1jRMBTruv3BBe2EFkhIs"

## MOVIES

Ahora vamos a poder consultar las movies, en la siguiente url

<p>URL: https://apimovielitebox.herokuapp.com/api/movies/{state}</p>
<p>METHOD: GET</p>

Los posibles states que tenemos para consultar son los siguiente

- now_playing
- upcoming
- popular
- my 

El ultimo state solo obtendra mis movies
Para consultar las categorias de las movies lo hacemos con la siguiente 

<p>URL: https://apimovielitebox.herokuapp.com/api/movies/category</p>
<p>METHOD: GET</p>

### LOAD MOVIE

<p>URL: https://apimovielitebox.herokuapp.com/api/movies/create</p>
<p>METHOD: POST</p>
<p>JSON</p>

    {
    "popularity" : 59.00,
    "vote_count" : 950,
    "video" : false,
    "poster_path" : "387374d5f22033327cfff1ba1430adfc.jpg",
    "id" : 8009,
    "adult" : true,
    "backdrop_path" : "d07401fa82c073300627b86dfec9108d.jpg",
    "original_language" : "mh",
    "original_title" : "Court Reporter",
    "title" : "Natus et ut deleniti harum.",
    "vote_average" : 6.40,
    "overview" : "Provident incidunt eaque est id provident doloribu...",
    "status" : "now_playing",
    "release_date" :  "1984-09-02",
    "genre_ids" : [16,18,27]
    }
