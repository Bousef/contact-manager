<?php

    // Function to read an address for a contact
    function read_address_for_contact($contact_id) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for reading an address
        $stmt = $conn->prepare("CALL read_address_for_contact(?)");
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
        if ($result === null) 
        {

            // If the address was not found, return an error response
            send_error_response(ErrorCodes::ADDRESS_NOT_FOUND);

        } 
        else 
        {

            // If the address was found, return a success response with the address data
            $filtered_result = [
                'address_line_01' => $result['address_line_01'],
                'address_line_02' => $result['address_line_02'],
                'city' => $result['city'],
                'state' => $result['state'],
                'zip_code' => $result['zip_code']
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