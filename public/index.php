<?php
/*

            THIS file contains all our API Calls

*/
// (1) Frist Go to this website http://www.slimframework.com/ and copy the code to here
// this is how we GET a server request 
use Psr\Http\Message\ServerRequestInterface as Request;
// this is how we GET a server response 
use Psr\Http\Message\ResponseInterface as Response;

//(2)
//(third step) will be in the Constance.php inside Include folder
// be aware of this file Path
require '../vendor/autoload.php';
use \Slim\App;

//(5) here we check if the connection is working successfully or not
require '../includes/DbConnect.php';

// this is a new slim app
$app = new \Slim\App();


// this is a GET Request CONTAINS THE URLfor the get request 
// the URL by defult will be 'KOSBAAPI/public/hello and then you can pass any name
// {name} this is a parameter    
// Second Parameter we pass a Funcition that takes Request, Respoonse objects and arguments that pass
// by using the parameter {name}
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {

    // here we getting the passed parameter from the array
    $name = $args['name'];
    //then assigning the name we get in the response body 
    $response->getBody()->write("Hello, $name");

    // (6) Define an object from the class
    $db = new DbConnect;

    // (7) check for the method connect() inside DbConnect instance 
    // is not null (has content) 
    if($db->connect()  != null){
        echo ' Connction is successful';
    }

    return $response;
});

// this is very important we need to run the app
$app->run();