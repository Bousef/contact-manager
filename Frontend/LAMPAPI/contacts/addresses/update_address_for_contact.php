<?php

    // Include the address functions files
    require_once 'create_address_for_contact.php';
    require_once 'read_address_for_contact.php';
    require_once 'errors.php';

    // Function to update an address for a contact
    function update_address_for_contact($contact_id, $address_line_01, $address_line_02, $city, $state, $zip_code) 
    {

        // Read the address for the contact
        ob_start();
        read_address_for_contact($contact_id);
        $read_result = ob_get_clean();
        $read_result = json_decode($read_result, true);

        // Check if the read was successful
        if (isset($read_result['success']) && $read_result['success'] === false) 
        {

            // Check if the address was not found
            if (isset($read_result['error']) && $read_result['error'] === ErrorCodes::ADDRESS_NOT_FOUND) 
            {

                // Check if all address parameters are not null or empty strings
                if 
                (
                    ($address_line_01 !== null && $address_line_01 !== '') &&
                    ($city !== null && $city !== '') &&
                    ($state !== null && $state !== '') &&
                    ($zip_code !== null && $zip_code !== '')
                ) 
                {
                    
                    // If the address was not found, create a new address
                    ob_start();
                    create_address_for_contact($contact_id, $address_line_01, $address_line_02, $city, $state, $zip_code);
                    $create_result = ob_get_clean();
                    $create_result = json_decode($create_result, true);

                    if (isset($create_result['success']) && $create_result['success'] === true) 
                    {

                        // Return a success response
                        echo json_encode([
                            'success' => true
                        ]);
                        
                    } 
                    else 
                    {

                        // Assuming $create_result['error'] contains a meaningful error code or message
                        $error_code = $create_result['error_code'] ?? ErrorCodes::INVALID_REQUEST;
                        send_error_response($error_code);

                    }

                    // Return to prevent further execution
                    return;

                }

            }

        }

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for updating an address
        $stmt = $conn->prepare("CALL update_address_for_contact(?, ?, ?, ?, ?, ?)");
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

        // Return a success response with the update status
        echo json_encode([
            'success' => (bool) $result['exit_status']
        ]);

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

?>