<?php

    function create_user($username, $password, $first_name, $last_name) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Hash the user's password using bcrypt
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Prepare the SQL statement to call the stored procedure for creating a user
        $stmt = $conn->prepare("CALL create_user(?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ssss", $username, $hashed_password, $first_name, $last_name);
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
        if ($result === null || $result['user_id'] === null) 
        {

            // If the user creation failed, return an error response
            send_error_response(ErrorCodes::USER_CREATION_FAILED);

        } 
        else 
        {

            // If the user creation was successful, return a success response with the user ID
            echo json_encode([
                'success' => true, 
                'result' => $result['user_id']
            ]);

        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

?>