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

// (11) frist we will delete all the $app->get()
// and then we will CREATE the API Call
/*
    endpoint: createuser
    parameters: email, password, name, school
    we will use post because this call will create a new record in the database
    method: POST
*/
// (A) to create the POST call
// FIRST write $app->post('/'),in the first parameter define the url or endpoint with the slash 
// SECOND define the second parameter that take a function which take request and response as the arguments 
$app->post('/createuser', function(Request $request, Response $response){
// (C) we will get use of the function that validate our requied parameters
// this if statment condition means if we do NOT have any empty parameters
// that are requierd or in other word if the condtion is true make a recall 
if(!haveEmptyParameters(array('email', 'password', 'name', 'school'))){
    // 1- we will get parameter from the request
    // and too do this we will call a method from the request object
    // $rquest_data will have all the data we recevied from this request 
    $rquest_data = $request->getParsedBody();

    // 2- we will get all the values from the request_data
    // values is email, password, name, school

    $email = $rquest_data['email'];
    $password = $rquest_data['password'];
    $name = $rquest_data['name'];
    $school = $rquest_data['school'];
    

}

});
    // (B) Verify that all the requierd parameters (email, password, name, school) are avilable
    // this will be a separeted method for the validations
    // haveEmptyParameters() this check if the requierd parameter are empty
    function haveEmptyParameters($required_params, $response){
        // 1- initially we will assume the error is false
        // and that mean we have all the parameters and no parameters are empty
        $error = false;
        // 2- we will create one more variable to store all the error parameters
        $error_params = '';

        // 3- we will get all the request parameters that we have in our request
        $request_params = $_REQUEST;

        // 4- NOW Tme to loop through all the request parameters
        //  know that we have all the parameters inside this $params value
        foreach($request_params as $params){
            // we will check if the parameter is empty or have zero length
            // !isset($request_params[$params]) this mean we DO NOT have a parameter
            // in the request
            // and we also check if the length is zero by using
            // strlen()
            if(!isset($request_params[$params]) || strlen($request_params[$params]) <= 0){
                // all the above mean if the coming parameter is empty or hase zero lenght
                // now we have an error so we will set the error to be true because the parameter
                // is missing or empty
                $error = true;
                // then we will concatenate the missing parameter in this error parameters to
                // this variable and concatenate the comma in case we have multi missing parameters
                $error_params .= $params . ', ';
            }
        }

        // 5- after this loop we will check if is an error we wil create an error details array
        if ($error){
            $error_detail = array();
            // we put it equal to true that means we have an error
            $error_detail ['error'] = true;
            // we will put requied parameters in the message and I can find the 
            // other parameter that missing inside the  $error_params = ''; but as 
            // we concatenate a comma at the end we need to remove the last comma
            // we will do that by using substr("the string u wanna subtract from",
            // "index of the starting", "numbers of charachter you want to subtract") 
            // -2 means from the end remove two charchters
            $error_detail["message"] = 'Requierd Parameters ' . substr($error_params, 0, -2) . ' are missing or empty';
            // we will use responce object to write the response
            // and as a parameter inside write() we will encode error_detail method
            // to JSON formate
            $response->write(json_encode($error_detail));
        }

        // finally we will return the error
        return $error;
    } 


// this is very important we need to run the app
$app->run();