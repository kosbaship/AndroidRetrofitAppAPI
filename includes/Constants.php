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
