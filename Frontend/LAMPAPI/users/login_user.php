<?php

    function login_user($username, $password)
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for fetching user details
        $stmt = $conn->prepare("CALL read_user_login_data(?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("s", $username);
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

            // If the result is null, return a user not found error response
            send_error_response(ErrorCodes::USER_NOT_FOUND);

        } 
        else 
        {

            // Verify the password using password_verify
            if (password_verify($password, $result['hashed_password'])) 
            {

                // If the password is correct, return a success response with the user ID
                echo json_encode([
                    'success' => true, 
                    'result' => $result['user_id']
                ]);

            } 
            else 
            {

                // If the password is incorrect, return an invalid password error response
                send_error_response(ErrorCodes::INVALID_PASSWORD);

            }
            
        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

?>