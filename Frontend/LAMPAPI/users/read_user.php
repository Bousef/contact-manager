<?php

    function read_user($user_id) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for reading a user
        $stmt = $conn->prepare("CALL read_user(?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) 
        {

            // If the statement execution failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $stmt->close();
            close_connection_to_database($conn);
            return;
            
        }

        // Fetch the result of the statement execution
        $result = $stmt->get_result()->fetch_assoc();
        if ($result === null) 
        {

            // If the user was not found, return an error response
            send_error_response(ErrorCodes::USER_NOT_FOUND);

        } 
        else 
        {

            // If the user was found, return a success response with the user data
            $filtered_result = 
            [
                'id' => $result['user_id'],
                'username' => $result['username'],
                'password' => $result['password'],
                'first_name' => $result['first_name'],
                'last_name' => $result['last_name']
            ];

            echo json_encode([
                'success' => true, 
                'result' => $filtered_result
            ]);

        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

?>