<?php

// (3) here we will define the Constants that are requierd to connect to our database
// (Forth step) will be in DbConnct.php
//                    we need 4

// (A) first Constants HOST_NAME and this is the host of our database
// in this case will be LOCALHOST
define('DB_HOST', 'localhost');

// (B) the USER of our database and this will be root in case of xampp
define('DB_USER', 'root');
// (C) the password of the user of that database and because we are using xampp
// the password will be blank by defult
define('DB_PASSWORD', '');
// (D) the database NAME inside PHPMYADMIN (MySQL)
//  ---------------    u have to got to http://localhost/phpmyadmin/ and 
// create Database with this name (kosba_app) first 
define('DB_NAME', 'kosba_app');


// (9) for all the user creation cases (three)
//  we will define a constants for the cases we face in the creation (register) process
// (tenth step) in the DbOperations
define('USER_CREATED', 101);
define('USER_EXIST', 102);
define('USER_FAILURE', 103);


// (12) creating the three posibilities when the user tri to login 
// we will define a constants for the cases we face in the (login) process
define('USER_AUTHENTICATED', 201);
define('USER_NOT_FOUND', 202);
define('USER_PASSWORD_DO_NOT_MATCH', 203);

// (20 - C)  for updating the password
define('PASSWORD_CHANGED', 301);
define('PASSWORD_DO_MATCH', 302);
define('PASSWORD_NOT_CHANGED', 303);
