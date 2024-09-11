<?php

    function read_contact_for_user($user_id, $contact_id) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for reading a contact
        $stmt = $conn->prepare("CALL read_contact_for_user(?, ?)");
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
        if ($result === null) 
        {

            // If the contact was not found, return an error response
            send_error_response(ErrorCodes::CONTACT_NOT_FOUND);

        } 
        else 
        {

            // If the contact was found, return a success response with the contact data
            $filtered_result = 
            [
                'id' => $result['contact_id'],
                'first_name' => $result['first_name'],
                'last_name' => $result['last_name'],
                'phone_number' => $result['phone_number'],
                'email_address' => $result['email_address']
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