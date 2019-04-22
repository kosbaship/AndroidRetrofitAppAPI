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
// the code inside let slim to represent the error for us
$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails'=>true
    ]
]);
//=================================================================================
//                             Retrofit Authentication
//     first run this in the code editor termina
//  composer require tuupola/slim-basic-auth
//          SECOND paste this code here
//          deine the username and password for our users table
//          when I tries to send a request the status is 401
//          in POSTMAN choose basic Auth and type ur name and password
// $app->add(new Tuupola\Middleware\HttpBasicAuthentication([
//     // this authentication work by default with https
//     // and in our ccase we do not have a secure connection 
//     "secure"=>false,
//     "users" => [
//         // this is a user name and a password
//         "kosbaship" => "123456"
//     ]
// ]));

// =================================================================================
// ---------------endpoint: /createuser-----------------------------------
// (11) frist we will delete all the $app->get()
// and then we will CREATE the API Call
// (Step Twelve) is the start of create read operation so go to Constants.php
/*
    endpoint: /createuser
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
if(!haveEmptyParameters(array('email', 'password', 'name', 'school'), $request, $response)){
    // 1- we will get parameter from the request 
    // that come from the user
    // and to do this we will call a method from the request object
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
                    ->withHeader('Content-type', 'application/json')
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
                ->withHeader('Content-type', 'application/json')
                ->withStatus(422);
    }else if($reasult == USER_EXIST){
        // create the message response that will appear to the user
        $message = array();
        $message['error'] = true;
        $message['message'] = 'User Already Exist';

        // put this method in JSON format and to do this we will use the 
        //[ Response $response ] with is the second parameter of the function in the 
        // POST request in this doc
        $response->write(json_encode($message));

        // return the response 
        // withStatus(422); this is a HTTP status code that mean the resource is created
        return $response
                ->withHeader('Content-type', 'application/json')
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
                ->withHeader('Content-type', 'application/json')
                ->withStatus(422);
});
// ---------------endpoint: /uselogin-----------------------------------
// (14) we will create another API call for the login
// the endpint: /uselogin
// thre request method : POST because we will send a data with our request 
$app->post('/userlogin', function(Request $request, Response $response){
    // (14 - A) for user login we need email and password
    // we pass to the array email, passord because they shouldn't be empty in the request
    // we use ! operator to confirm we do not want to have email and password empty in the request
    if(!haveEmptyParameters(array('email', 'password'), $request, $response)){
        // (14 - A - One)now we will get the email and password from the request
        // that come from the user
        $rquest_data = $request->getParsedBody();
         // (14 - A - Two)
        // we will get all the email, password from the request_data
        $email = $rquest_data['email'];
        $password = $rquest_data['password'];
        // (14 - A - Three) we will call the function user login
        // do do this we need to creat an instanse from the DbOperations
        $db = new DbOperations;
        $result = $db->userLogin($email, $password);
         
        // (14 - A - Four) check for this email and password is authenticated or
        // not found or even thre password do not matsh
        if ($result == USER_AUTHENTICATED){

            // in this case get the user from the DB
            $user = $db->getUserByEmail($email);
            // this is the formate of the response
            $response_data  = array();
            $response_data ['error'] = false;
            $response_data ['message'] = 'Login Successful';
            $response_data['user']= $user;

            // we will use the $response object to write the output in json format
            $response->write(json_encode($response_data));

            // last thing is to return the response
            // code 200 means OK
            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);

        } else if ($result == USER_NOT_FOUND){
            // this is the formate of the response
            $response_data  = array();
            $response_data ['error'] = true;
            $response_data ['message'] = 'User is Not Exist';

            // we will use the $response object to write the output in json format
            $response->write(json_encode($response_data));

            // last thing is to return the response
            // code 200 means OK
            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);

        } else if ($result == USER_PASSWORD_DO_NOT_MATCH){
            // this is the formate of the response
            $response_data  = array();
            $response_data ['error'] = true;
            $response_data ['message'] = 'The password doesn\'t match ';

            // we will use the $response object to write the output in json format
            $response->write(json_encode($response_data));

            // last thing is to return the response
            // code 200 means OK
            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);

        }

    }
    // (14 - B ) we return the response of the error code
        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(422);
});
// ---------------endpoint: /allusers -----------------------------------
// (16 ) we will create another API call for Fetching all the users
// (Seventeenth Step) will be in DbOperations.php
// the endpint: /allusers
// thre request method : GET because we will send a data with our request 
$app->get('/allusers', function(Request $request, Response $response){
        // we will call the function getAllUsers
        // do do this we need to creat an instanse from the DbOperations
        $db = new DbOperations;
        $users = $db->getAllUsers();
        // the response content 
        $response_data  = array();
        $response_data ['error'] = false;
        // the response will be users as we fitsh from the database
        $response_data ['users'] = $users;
        // we will use the $response object to write the output in json format
        $response->write(json_encode($response_data));
        // last thing is to return the response
        // code 200 means OK
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
});
// ---------------endpoint: //updateuser/{id} -----------------------------------
// (18) create the API call to update existing user
// (Ninteenth step) will be a modification inside haveEmptyParameters() Method
// the endpint: /updateuser/{id} --> the id is for the user that updated
// the http request : PUT 
// as we know the first parameter is the end piont but this time the second will be
// function of three parameters the third param is an array that we will get the ID from 
$app->put('/updateuser/{id}', function(Request $request, Response $response, array $args){
    //(18 - A)first we will get the id
    $id = $args['id'];
    //(18 - B - 1)
    // if every thing is correct we will do the update
    if(!haveEmptyParameters(array('email', 'name', 'school'), $request, $response)){
        //(18 - C - 1)
        // we will call a method from the request object getParsedBody() and store in
        // $rquest_data the data we recevied from this request 
        $rquest_data = $request->getParsedBody();
        //(18 - C - 2) we will get all the values from the request_data
        // values is email, name, school, id
        $email = $rquest_data['email'];
        $name = $rquest_data['name'];
        $school = $rquest_data['school'];
        // $id = $rquest_data['id'];
        //(18 - C - 3)Create an objet of DbOperations
        $db = new DbOperations;
        if ($db->updateUser($email, $name, $school, $id)){
            // the update is successful so we will create a response data
            // this is the formate of the response
            $response_data  = array();
            $response_data ['error'] = false;
            $response_data ['message'] = 'User Updated Successfully';
            // we also fetsh the new user information by using getUserByEmail
            $user = $db->getUserByEmail($email);
            // add the info to the response data that we will send back to the user
            $response_data['user']= $user;
                    // we will use the $response object to write the output in json format
                    $response->write(json_encode($response_data));

                    // last thing is to return the response
                    // code 200 means OK
                    return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
        }else{
            // the update is successful so we will create a response data
            // this is the formate of the response
            $response_data  = array();
            $response_data ['error'] = true;
            $response_data ['message'] = 'Please Try again Later';
            // we also fetsh the new user information by using getUserByEmail
            $user = $db->getUserByEmail($email);
            // add the info to the response data that we will send back to the user
            $response_data['user']= $user;
            // we will use the $response object to write the output in json format
            $response->write(json_encode($response_data));

            // last thing is to return the response
            // code 200 means OK
            return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(200);

        }
    }
    //(18 - B - 2)
    //else we will return the response like this
    return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);
});

// ---------------endpoint: //updatepassword -----------------------------------
// (21) create the API call to update existing user
// (Twintyonce step) will be creating delete Method in DbOperations.php
// the endpint: /updatepassword --> the id is for the user that updated
// the http request : PUT 
// as we know the first parameter is the end piont but this time the second will be
// function of three parameters the third param is an array that we will get the ID from 
$app->put('/updatepassword', function(Request $request, Response $response){
    // (21 - A - 1) Check the requierd Parameters
    // if every thing is correct we will do the update
    if(!haveEmptyParameters(array('currentpassword', 'newpassword', 'email'), $request, $response)){
        // to change the password we need to get the parameters
        // this is the content comming from the user
        $request_data = $request->getParsedBody();

        //get current password
        $currentpassword = $request_data['currentpassword'];
        $newpassword = $request_data['newpassword'];
        $email = $request_data["email"];

        // we will call the function user login
        // do do this we need to creat an instanse from the DbOperations
        $db = new DbOperations;
        $result = $db->updatePassword($currentpassword, $newpassword, $email);

        if($result == PASSWORD_CHANGED){
            // this is the formate of the response
            $response_data  = array();
            $response_data ['error'] = false;
            $response_data ['message'] = 'Password Changed';

            // we will use the $response object to write the output in json format
            $response->write(json_encode($response_data));

            // last thing is to return the response
            // code 200 means OK
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
        } else if($result == PASSWORD_DO_MATCH){
            // this is the formate of the response
            $response_data  = array();
            $response_data ['error'] = true;
            $response_data ['message'] = 'You given the Wrong Password';

            // we will use the $response object to write the output in json format
            $response->write(json_encode($response_data));

            // last thing is to return the response
            // code 200 means OK
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
        }else if($result == PASSWORD_NOT_CHANGED){
            // this is the formate of the response
            $response_data  = array();
            $response_data ['error'] = true;
            $response_data ['message'] = 'Password not Changed try again';

            // we will use the $response object to write the output in json format
            $response->write(json_encode($response_data));

            // last thing is to return the response
            // code 200 means OK
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
        }
    } 


    //(21 - A - 2)
    //else we will return the response like this
    return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(422);

});
// ---------------endpoint: /deleteuser/{id} -----------------------------------
// (23) create the API call to update existing user 
// the endpint: /deleteuser/{id} --> the id is for the user that updated
// the http request : DELETE 
// as we know the first parameter is the end piont but this time the second will be
// function of three parameters the third param is an array that we will get the ID from 
$app->delete('/deleteuser/{id}', function(Request $request, Response $response, array $args){
    //(23 - A)first we will get the id
    $id = $args['id'];

    $db = new DbOperations;
    // if the function delete user returns true we will display a message
    // else display another message 

    $response_data = array();
    if($db->deleteUser($id)){
        $response_data['error'] = false;
        $response_data['message'] = 'User has been DELETED';
    } else{
        $response_data['error'] = true;
        $response_data['message'] = 'Please Try again Later';    
    }
    // we will use the $response object to write the output in json format
    $response->write(json_encode($response_data));

    //else we will return the response like this
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);

});   
    // (11) (B) Verify that all the requierd parameters (email, password, name, school) are avilable
    // this will be a separeted method for the validations
    // haveEmptyParameters() this check if the requierd parameter are empty
    function haveEmptyParameters($required_params , $request, $response){
        // 1- initially we will assume the error is false
        // and that mean we have all the parameters and no parameters are empty
        $error = false;
        // 2- we will create one more variable to store all the error parameters
        $error_params = '';

        // 3- we will get all the request parameters that we have in our request
        // (19) the issu here we are reading the request like this
        //         $request_params = $_REQUEST;
        // and this will cause not getting value from PUT request so
        // we will pass ine more parameter (Request) to this method
        // adn use it insted super global variable $_REQUEST
        //          last is tho modify every where I using haveEmptyParameters()   
        // (next Step twinthy) will be in DbOperations.php 
        $request_params = $request->getParsedBody();

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
