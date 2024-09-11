<?php

    function read_contacts_for_user($user_id, $search_string) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for reading contacts
        $stmt = $conn->prepare("CALL read_contacts_for_user(?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("is", $user_id, $search_string);
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
        $contacts = [];
        while ($row = $result->fetch_assoc()) 
        {

            $contacts[] = 
            [
                'id' => $row['id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'phone_number' => $row['phone_number'],
                'email_address' => $row['email_address']
            ];
            
        }

        // Return a success response with the contacts data
        echo json_encode([
            'success' => true, 
            'result' => $contacts
        ]);

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);
        
    }

?>