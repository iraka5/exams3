<?php
require __DIR__ . '/vendor/autoload.php';

Flight::set('flight.base_url', '/exams3-main/exams3');

Flight::route('/', function(){
    echo "Route / fonctionne<br>";
    echo "<a href='/exams3-main/exams3/test'>Test</a>";
});

Flight::route('/test', function(){
    echo "Route /test fonctionne";
});

Flight::start();
?>