<?php

    // Function to create an address for a contact
    function create_address_for_contact($contact_id, $address_line_01, $address_line_02, $city, $state, $zip_code) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for creating an address
        $stmt = $conn->prepare("CALL create_address_for_contact(?, ?, ?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("isssss", $contact_id, $address_line_01, $address_line_02, $city, $state, $zip_code);
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
        if ($result === null || $result['exit_status'] === false) 
        {

            // If the address creation failed, return an error response
            send_error_response(ErrorCodes::ADDRESS_CREATION_FAILED);

        } 
        else 
        {

            // If the address creation was successful, return a success response
            echo json_encode([
                'success' => true
            ]);

        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

?>