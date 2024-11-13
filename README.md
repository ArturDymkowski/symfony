Jest to aplikacja napisana w Symfony, która za pomocą komendy konsolowej pobiera listę postów z endpointu https://jsonplaceholder.typicode.com/posts i zapisuje je u nas w lokalnej bazie wraz z imieniem i nazwiskiem autora (pobrane w relacji z końcówki /users). Aplikacja ta posiada wbudowane moduły autoryzacyjne. Na podstronie /lista jest lista pobranych postów z możliwości ich usunięcia z lokalnej bazy danych. Aplikacja udostępnia końcówkę GET /posts zawierająca wszystkie posty z lokalnej bazy danych i została stworzona za pomocą ApiPlatform.

Polecenie do pobrania postów: php bin/console api:get-posts <br>
Rejestracja znajduje sie pod route /register, nie jest wymagane potwierdzenie maila <br>
Logowanie znajduje sie pod route /login
