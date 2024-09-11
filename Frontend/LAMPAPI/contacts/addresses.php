<?php

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Include the database connection and error handling files
    require_once '../database.php';
    require_once '../errors.php';

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

    // Function to update an address for a contact
    function update_address_for_contact($contact_id, $address_line_01, $address_line_02, $city, $state, $zip_code) 
    {

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

    // Function to delete an address for a contact
    function delete_address_for_contact($contact_id) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for deleting an address
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

        // Return a success response with the delete status
        echo json_encode([
            'success' => (bool) $result['exit_status']
        ]);

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

    // Get the request data
    $json_req = file_get_contents('php://input');

    // Turn input data into Object
    $json_decoded = json_decode($json_req, true);

    // Null check for JSON
    if($json_decoded == null)
    {
        send_error_response(ErrorCodes::INVALID_REQUEST);
        return;
    }

    // Ensure the request type is set
    if(!isset($json_decoded['req_type']))
    {
        send_error_response(ErrorCodes::INVALID_REQUEST);
        return;
    }

    // Switch case to handle the different kinds of actions taken on addresses
    switch($json_decoded['req_type'])
    {

        case 'create':
        {

            // Ensure all necessary parameters are set
            if (isset($json_decoded['contact_id']) && isset($json_decoded['address_line_01']) && isset($json_decoded['address_line_02']) && isset($json_decoded['city']) && isset($json_decoded['state']) && isset($json_decoded['zip_code'])) 
            {
                create_address_for_contact($json_decoded['contact_id'], $json_decoded['address_line_01'], $json_decoded['address_line_02'], $json_decoded['city'], $json_decoded['state'], $json_decoded['zip_code']);
            } 
            else 
            {
                send_error_response(ErrorCodes::INVALID_REQUEST);
            }

            break;
            
        }

        case 'read':
        {

            // Ensure all necessary parameters are set
            if (isset($json_decoded['contact_id'])) 
            {
                read_address_for_contact($json_decoded['contact_id']);
            } 
            else 
            {
                send_error_response(ErrorCodes::INVALID_REQUEST);
            }

            break;

        }

        case 'update':
        {

            // Ensure all necessary parameters are set
            if (isset($json_decoded['contact_id']) && isset($json_decoded['address_line_01']) && isset($json_decoded['address_line_02']) && isset($json_decoded['city']) && isset($json_decoded['state']) && isset($json_decoded['zip_code'])) 
            {
                update_address_for_contact($json_decoded['contact_id'], $json_decoded['address_line_01'], $json_decoded['address_line_02'], $json_decoded['city'], $json_decoded['state'], $json_decoded['zip_code']);
            } 
            else 
            {
                send_error_response(ErrorCodes::INVALID_REQUEST);
            }

            break;
        }

        case 'delete':
        {

            // Ensure all necessary parameters are set
            if (isset($json_decoded['contact_id'])) 
            {
                delete_address_for_contact($json_decoded['contact_id']);
            } 
            else 
            {
                send_error_response(ErrorCodes::INVALID_REQUEST);
            }

            break;

        }

        default:
        {

            send_error_response(ErrorCodes::INVALID_REQUEST);
            break;

        }

    }

?>