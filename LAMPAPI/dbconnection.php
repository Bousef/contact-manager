<?php

    function open_connection_to_database()
    {

        $servername = $_SERVER['DB_SERVER_NAME'];
        $username = $_SERVER['DB_USER'];
        $password = $_SERVER['DB_PWD'];
        $database = $_SERVER['DB_NAME'];

        $conn = @mysqli_connect($servername, $username, $password, $database);

        if (!$conn) 
        {
            return null;
            
        }

        return $conn;
        
    }

    function close_connection_to_database($conn) 
    {

        mysqli_close($conn);
        
    }
    
?>