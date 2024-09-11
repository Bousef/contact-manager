<?php

    function update_user($user_id, $username, $password, $first_name, $last_name) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for updating a user
        $stmt = $conn->prepare("CALL update_user(?, ?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("issss", $user_id, $password, $username, $first_name, $last_name);
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
        if ($result['exit_status'] == 0) 
        {

            // If no rows were updated, return an error response
            send_error_response(ErrorCodes::NO_ROWS_UPDATED);

        } 
        else 
        {

            // If the user update was successful, return a success response
            echo json_encode([
                'success' => (bool) $result['exit_status']
            ]);

        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

?>