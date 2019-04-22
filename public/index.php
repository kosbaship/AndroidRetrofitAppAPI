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

//(11)(C) 4- Delete the check of the connection 
//    Create database operation object
require '../includes/DbOperations.php';

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
//  and aso we should pass the response object as a second parameter
if(!haveEmptyParameters(array('email', 'password', 'name', 'school'), $response)){
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
    
    // 3- incrept the password by using php predefined function
    // first parameter will be the password and thte second will be the algurathim
    // and aslo stire the increpted password in another variable ($hash_password)
    $hash_password = password_hash($password, PASSWORD_DEFAULT);


    // 5- Create an objet of DbOperations
    $db = new DbOperations;

    // 6- time to call $db->createUsear();
    // and pass all the values email, hash_password, name, school
    $reasult = $db->createUser($email, $hash_password, $name, $school);

    // 7- check if $reasult cases (all the user creation cases)
    // and put the apropriate response acordding to the result
    if($reasult == USER_CREATED){
        // create the message response that will appear to the user
        $message = array();
        $message['error'] = false;
        $message['message'] = 'User Created Successfully';

        // put this method in JSON format and to do this we will use the 
        //[ Response $response ] with is the second parameter of the function in the 
        // POST request in this doc
        $response->write(json_encode($message));

        // return the response 
        // withStatus(201); this is a HTTP status code that mean the resource is created
        return $response
                    ->withHeader('content_type', 'application/json')
                    ->withStatus(201);

    }else if($reasult == USER_FAILURE){
        // create the message response that will appear to the user
        $message = array();
        $message['error'] = true;
        $message['message'] = 'Some Error Occerred';

        // put this method in JSON format and to do this we will use the 
        //[ Response $response ] with is the second parameter of the function in the 
        // POST request in this doc
        $response->write(json_encode($message));

        // return the response 
        // withStatus(422); this is a HTTP status code that mean the resource is created
        return $response
                ->withHeader('content_type', 'application/json')
                ->withStatus(422);
    }else if($reasult == USER_EXIST){
        // create the message response that will appear to the user
        $message = array();
        $message['error'] = true;
        $message['message'] = 'User Already Existe';

        // put this method in JSON format and to do this we will use the 
        //[ Response $response ] with is the second parameter of the function in the 
        // POST request in this doc
        $response->write(json_encode($message));

        // return the response 
        // withStatus(422); this is a HTTP status code that mean the resource is created
        return $response
                ->withHeader('content_type', 'application/json')
                ->withStatus(422);
    }

}
    //7- check if $reasult cases (all the user creation cases)
    // and put the apropriate response acordding to the result
    // and if the parameter are missing there is also an error
    // this step is duplicated as the above we use when the user existed
        // return the response 
        // withStatus(422); this is a HTTP status code that mean the resource is created
        return $response
                ->withHeader('content_type', 'application/json')
                ->withStatus(422);
});
    // (11) (B) Verify that all the requierd parameters (email, password, name, school) are avilable
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
        foreach($required_params as $params){
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