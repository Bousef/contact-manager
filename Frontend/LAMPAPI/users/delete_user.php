<?php

    function delete_user($user_id) 
    {

        // Establish a connection to the database
        $conn = open_connection_to_database();
        if (!$conn) 
        {
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;
        }

        // Prepare the SQL statement to call the stored procedure read_contacts_for_user
        $stmt = $conn->prepare("CALL read_contacts_for_user(?, '', 0, 0)");
        if (!$stmt) 
        {
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;
        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) 
        {
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $stmt->close();
            close_connection_to_database($conn);
            return;
        }

        // Fetch the result of the statement execution
        $result = $stmt->get_result();
        if (!$result) 
        {
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $stmt->close();
            close_connection_to_database($conn);
            return;
        }

        while ($contact = $result->fetch_assoc()) 
        {

            // Get the ID of the contact
            $contact_id = $contact['id'];
        
            // Prepare the SQL statement to delete the address for the contact
            $delete_address_stmt = $conn->prepare("CALL delete_address_for_contact(?)");
            if (!$delete_address_stmt) 
            {
                send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
                $stmt->close();
                close_connection_to_database($conn);
                return;
            }
        
            // Bind the parameters to the SQL statement
            $delete_address_stmt->bind_param("i", $contact_id);
            if (!$delete_address_stmt->execute()) 
            {
                send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
                $delete_address_stmt->close();
                $stmt->close();
                close_connection_to_database($conn);
                return;
            }
        
            // Close the statement
            $delete_address_stmt->close();
        
            // Prepare the SQL statement to delete the contact for the user
            $delete_contact_stmt = $conn->prepare("CALL delete_contact_for_user(?, ?)");
            if (!$delete_contact_stmt) 
            {
                send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
                $stmt->close();
                close_connection_to_database($conn);
                return;
            }
        
            // Bind the parameters to the SQL statement
            $delete_contact_stmt->bind_param("ii", $user_id, $contact_id);
            if (!$delete_contact_stmt->execute()) 
            {
                send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
                $delete_contact_stmt->close();
                $stmt->close();
                close_connection_to_database($conn);
                return;
            }
        
            // Close the statement
            $delete_contact_stmt->close();

        }

        // Close the statement
        $stmt->close();

        // Prepare the SQL statement to delete the user
        $delete_user_stmt = $conn->prepare("CALL delete_user(?)");
        if (!$delete_user_stmt) 
        {
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;
        }

        // Bind the parameters to the SQL statement
        $delete_user_stmt->bind_param("i", $user_id);
        if (!$delete_user_stmt->execute()) 
        {
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $delete_user_stmt->close();
            close_connection_to_database($conn);
            return;
        }

        // Close the statement
        $delete_user_stmt->close();

        // Close the database connection
        close_connection_to_database($conn);

    }

?>
