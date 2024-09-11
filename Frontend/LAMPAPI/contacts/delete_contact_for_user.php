<?php

    function delete_contact_for_user($user_id, $contact_id) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for deleting the address
        $stmt = $conn->prepare("CALL delete_address_for_contact(?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("i", $contact_id);
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
        $stmt->close();

        // Check if the address deletion was successful
        if ($result['exit_status'] != 1) 
        {

            // If the address deletion failed, return an error response
            send_error_response(ErrorCodes::NO_ROWS_DELETED);
            close_connection_to_database($conn);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for deleting a contact
        $stmt = $conn->prepare("CALL delete_contact_for_user(?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ii", $user_id, $contact_id);
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
        $stmt->close();

        // Check if the contact deletion was successful
        if ($result['exit_status'] == 1) 
        {

            // Return a success response with the delete status
            echo json_encode([
                'success' => (bool) $result['exit_status']
            ]);

        } 
        else 
        {

            // If the contact deletion failed, return an error response
            send_error_response(ErrorCodes::NO_ROWS_DELETED);

        }

        // Close the database connection
        close_connection_to_database($conn);

    }

?>