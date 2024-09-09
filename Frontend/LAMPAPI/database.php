<?php

    function open_connection_to_database()
    {

        $servername = "localhost";
        $username = "bitnami";
        $password = "X7g9#4vZ1$2cQ5";
        $database = "cop4331_contact_manager";

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