<?php
/*
             (8) here we will create a class To organize database operations 
*/
    class DbOperations{
        // (A) this Variable to store the Connection link
        // and we will get the value for this variable from DbConnect.php
        private $con;

        /**
         *                  --- Constructor ---
         */
        // (B) create a constarctor to  get the value for this variable from DbConnect.php
        function __construct(){
            // 1- first we will import DbConnect.php file
            include_once dirname(__FILE__) . '/DbConnect.php';

            // 2- SECOND we will create an object from the DbConnect class to access the methods and feilds inside it
            $db = new DbConnect();

            // 3- get the value of con (Connection Link) from there by using connect() Method 
            // and store it here in the variacle cone
            $this->con = $db->connect();
        }


        // (C) this method to insert a user to our Database
        // go to the database (check the table) and see what is the data u want 
        // to create a user in our cass we need emaill, password, name, school 
        public function createUser($email, $password, $name, $school){
            /*
             *FROM HERE WE HAVE THREE OPTIONS
                ONE WHER THE USER CREATED
                TWO WHEN THE USER NOT CREATED 
                WHEN THE EMAIL ALREADY EIST 
             */

            // (C) 5- this shulod be created after the inner code
            // and this mean if email not already exist 
            // (ninth step) go to Constants.php file 
            if(!$this->isEmailExist($email)){
                //1- here we create a prepare statment
                // prepare("") 
                //      inside this method we write the MySQL query we want to execute
                //      make sure to mach the coulmn name here with the ones in the table
                //      and also insted of values we will put a ?

                // store the return of this prepare() method in another variavle stmt (short for statment)
                $stmt = $this->con->prepare("INSERT INTO users (email, password, name, school) VALUES (? ,? ,? ,? )");
                
                //2- we will bind the actual prameter the we want to insert to the proper Value
                //   we will do that using the bind_param() function
                //   it takes the parameter type in order and atatch it wil the question marks in order also
                //   i.e ssss = ????
                //   s = refer to a string
                $stmt->bind_param("ssss", $email, $password, $name, $school);

                //3- execute the query but we will but it inside if statment to distinguish between the success and failer
                //   but we have to be sure is the email is unique frist
                if($stmt->execute()){
                    /*
                    * OPTION ONE
                    */
                    // (10) We will return the statment fo the three options
                    // (ELEVENTH STEP) will be in index.php
                    return USER_CREATED;
                }else{
                    /*
                    * OPTION tWO
                    */
                    return USER_FAILURE;
                }
                /*
                * END OF OPTION THREE
                */
            }
            return USER_EXIST;
        }
        // (C) 4-this function is to check if the given email is exist or not
        private function isEmailExist($email){
            // here again we need a statment but this time we will check if the email already exist or not
            // and we will do this by writing a select query
            $stmt = $this->con->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            // this will return something sw we need to store the result
            $stmt->store_result();
            // if the result contains one row that means the email already exist
            //  num_rows    this return total number of rows returnd by this query
            //   "SELECT `id` FROM `users` WHERE 'email' = ?"
            return $stmt->num_rows > 0;
        }



    }
