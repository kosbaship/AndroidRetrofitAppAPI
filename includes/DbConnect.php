<?php

    // (4) here we will create a class that will connect with the database

    class DbConnect{
        
        // (A) this Variable to store the Connection link
        private $con;

        // (B) we will call this method when we want to connect to the database 
        function connect(){

            //(C) First thing we need inside this method is Constants.php
            //because it contains the constants requierd to connect with the database
            // dirname() this function returns the current directory and 
            //we need to pass __FILE__
            // and also concatenat the file itself that we need
            include_once dirname(__FILE__) . '/Constants.php';


            // (D) Now here is the actuall connection to the DB
            // 1- we need new MySQL object as a connction link
            $this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            // (E) we will check if the connection is succesful or not
            // by using the pre defind method (mysqli_connect_errno())
            if(mysqli_connect_errno()){
                //mysqli_connct_error() this method will return the actuall error method
                echo "Faild to Connect" . mysqli_connct_error();
                //also we will return null
                return null;
            }

            // (F) if every thing is OK we will return the connection
            return $this->con;
        }
    }