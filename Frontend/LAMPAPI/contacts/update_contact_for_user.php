<?php

    function update_contact_for_user($user_id, $contact_id, $first_name, $last_name, $phone_number, $email_address) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for updating a contact
        $stmt = $conn->prepare("CALL update_contact_for_user(?, ?, ?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("iissss", $user_id, $contact_id, $first_name, $last_name, $phone_number, $email_address);
        if (!$stmt->execute()) 
        {

            // If the statement execution failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $stmt->close();
            close_connection_to_database($conn);
            return;

        }

        // Fetch the result of the statement execution
        $result = $stmt->get_result();
        if ($result === false) 
        {

            // If no result is returned, return an error response
            send_error_response(ErrorCodes::NO_RESULT_RETURNED);
            $stmt->close();
            close_connection_to_database($conn);
            return;

        }

        $row = $result->fetch_assoc();
        if ($row === null) 
        {

            // If no row is fetched, return an error response
            send_error_response(ErrorCodes::NO_ROW_FETCHED);
            $stmt->close();
            close_connection_to_database($conn);
            return;

        }

        // Return a success response with the update status
        echo json_encode([
            'success' => (bool) $row['exit_status']
        ]);

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);
        
    }

?>
