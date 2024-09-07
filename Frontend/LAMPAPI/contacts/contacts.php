<?php

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Include the database connection and error handling files
    require_once 'database.php';
    require_once 'errors.php';

    function create_contact_for_user($user_id, $first_name, $last_name, $phone_number) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {
            
            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for creating a contact
        $stmt = $conn->prepare("CALL create_contact_for_user(?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("isss", $user_id, $first_name, $last_name, $phone_number);
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
        if ($result === null || $result['contact_id'] === null) 
        {

            // If the contact creation failed, return an error response
            send_error_response(ErrorCodes::CONTACT_CREATION_FAILED);
            close_connection_to_database($conn);
            return;

        } 
        else 
        {

            // If the contact creation was successful, return a success response with the contact ID
            echo json_encode([
                'success' => true, 
                'result' => $result['contact_id']
            ]);

        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

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
                'phone_number' => $result['phone_number']
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
                'id' => $row['contact_id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'phone_number' => $row['phone_number']
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

    function update_contact_for_user($user_id, $contact_id, $first_name, $last_name, $phone_number) 
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
        $stmt = $conn->prepare("CALL update_contact_for_user(?, ?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("iisss", $user_id, $contact_id, $first_name, $last_name, $phone_number);
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

    $json_req = file_get_contents('php://input');
    //Turn input data into Object
    $json_decoded = json_decode($json, true);

    //Null checks
    if($json_decoded == null){
        send_error_response(ErrorCodes::INVALID_JSON);
        return;
    }
    if(!isset($json_decoded['req_type'])){
        send_error_response(ErrorCodes::INVALID_JSON);
        return;
    }

    //Switch case to handle the different kinds of actions taken on contacts
    switch($json_decoded['req_type']){
        case 'create':
            //Ensure all necessary parameters are set (including parameters not yet handled by function)
            if (
                isset($json_decoded['user_id']) &&
                isset($json_decoded['first_name']) &&
                isset($json_decoded['last_name']) &&
                isset($json_decoded['phone_number']) &&
                isset($json_decoded['email']) &&
                isset($json_decoded['img_url'])
            ){
                create_contact_for_user($json_decoded['user_id'], $json_decoded['first_name'], $json_decoded['last_name'], $json_decoded['phone_number']);
            }else{
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }
            break;
        case 'read':
            //Ensure all necessary parameters are set
            if (
                isset($json_decoded['user_id']) &&
                isset($json_decoded['contact_id'])
            ){
                read_contact_for_user($json_decoded['user_id'], $json_decoded['contact_id']);
            }else{
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }
            break;
        case 'search':
            //Ensure all necessary parameters are set
            if (
                isset($json_decoded['user_id']) &&
                isset($json_decoded['search_string'])
            ){
                read_contacts_for_user($json_decoded['user_id'], $json_decoded['search_string']);
            }else{
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }
            break;
        case 'update':
            //Ensure all necessary parameters are set
            if (
                isset($json_decoded['user_id']) &&
                isset($json_decoded['contact_id']) &&
                isset($json_decoded['first_name']) &&
                isset($json_decoded['last_name']) &&
                isset($json_decoded['phone_number']) &&
                isset($json_decoded['email']) &&
                isset($json_decoded['img_url'])
            ){
                update_contact_for_user($json_decoded['user_id'], $json_decoded['contact_id'], $json_decoded['first_name'], $json_decoded['last_name'], $json_decoded['phone_number']);
            }else{
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }
            break;
        case 'delete':
            //Ensure all necessary parameters are set
            if (
                isset($json_decoded['user_id']) &&
                isset($json_decoded['contact_id'])
            ){
                delete_contact_for_user($json_decoded['user_id'], $json_decoded['contact_id']);
            }else{
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }
            break;
    }
?>