<?php

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Include the database connection and error handling files
    require_once '../database.php';
    require_once '../errors.php';

    function create_user($username, $password, $first_name, $last_name) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Hash the user's password using bcrypt
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Prepare the SQL statement to call the stored procedure for creating a user
        $stmt = $conn->prepare("CALL create_user(?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ssss", $username, $hashed_password, $first_name, $last_name);
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
        if ($result === null || $result['user_id'] === null) 
        {

            // If the user creation failed, return an error response
            send_error_response(ErrorCodes::USER_CREATION_FAILED);

        } 
        else 
        {

            // If the user creation was successful, return a success response with the user ID
            echo json_encode([
                'success' => true, 
                'result' => $result['user_id']
            ]);

        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

    function read_user($user_id) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for reading a user
        $stmt = $conn->prepare("CALL read_user(?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("i", $user_id);
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

            // If the user was not found, return an error response
            send_error_response(ErrorCodes::USER_NOT_FOUND);

        } 
        else 
        {

            // If the user was found, return a success response with the user data
            $filtered_result = 
            [
                'id' => $result['user_id'],
                'username' => $result['username'],
                'password' => $result['password'],
                'first_name' => $result['first_name'],
                'last_name' => $result['last_name']
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

    function update_user($user_id, $username, $password, $first_name, $last_name) 
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for updating a user
        $stmt = $conn->prepare("CALL update_user(?, ?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("issss", $user_id, $password, $username, $first_name, $last_name);
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
        if ($result['exit_status'] == 0) 
        {

            // If no rows were updated, return an error response
            send_error_response(ErrorCodes::NO_ROWS_UPDATED);

        } 
        else 
        {

            // If the user update was successful, return a success response
            echo json_encode([
                'success' => (bool) $result['exit_status']
            ]);

        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

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
        $stmt = $conn->prepare("CALL read_contacts_for_user(?, '')");
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

    function login_user($username, $password)
    {

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {

            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;

        }

        // Hash the user's password using bcrypt (same as in create_user)
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement to call the stored procedure for logging in a user
        $stmt = $conn->prepare("CALL login_user(?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ss", $username, $hashed_password);
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

            // If the result is null, return a generic error response
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);

        } 
        else 
        {

            // Check the error code returned by the stored procedure
            switch ($result['error_code']) 
            {

                case 1:
                {
                    send_error_response(ErrorCodes::USER_NOT_FOUND);
                    break;
                }

                case 2:
                {
                    send_error_response(ErrorCodes::INVALID_PASSWORD);
                    break;
                }

                default:
                {

                    // If the login was successful, return a success response with the user ID
                    echo json_encode([
                        'success' => true, 
                        'result' => $result['user_id']
                    ]);

                    break;
                
                }

            }

        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }

    // Get the request data
    $json_req = file_get_contents('php://input');

    // Turn input data into Object
    parse_str($_SERVER['QUERY_STRING'], $json_decoded);

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

    // Switch case to handle the different kinds of actions taken on contacts
    switch($json_decoded['req_type'])
    {

        case 'register':
        {

            // Ensure all necessary parameters are set (including parameters not yet handled by function)
            if 
            (
                isset($json_decoded['username'])         &&
                isset($json_decoded['password'])      &&
                isset($json_decoded['first_name'])       &&
                isset($json_decoded['last_name'])
            )
            {
                create_user($json_decoded['username'], $json_decoded['password'], $json_decoded['first_name'], $json_decoded['last_name']);
            }
            else
            {
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }

            break;

        }

        case 'read':
        {

            // Ensure all necessary parameters are set
            if 
            (
                isset($json_decoded['user_id'])
            )
            {
                read_user($json_decoded['user_id']);
            }
            else
            {
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }

            break;

        }

        case 'delete':
        {

            // Ensure all necessary parameters are set
            if 
            (
                isset($json_decoded['user_id'])
            )
            {
                delete_user($json_decoded['user_id']);
            }
            else
            {
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }

            break;

        }

        case 'update':
        {

            // Ensure all necessary parameters are set
            if 
            (
                isset($json_decoded['user_id'])         &&
                isset($json_decoded['username'])      &&
                isset($json_decoded['password'])      &&
                isset($json_decoded['first_name'])       &&
                isset($json_decoded['last_name'])
            )
            {
                update_user($json_decoded['user_id'], $json_decoded['username'], $json_decoded['password'], $json_decoded['first_name'], $json_decoded['last_name']);
            }
            else
            {
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }

            break;

        }

        case 'login':
        {

            // Ensure all necessary parameters are set
            if 
            (
                isset($json_decoded['username'])         &&
                isset($json_decoded['password'])
            )
            {
                login_user($json_decoded['username'], $json_decoded['password']);
            }
            else
            {
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }

            break;

        }

    }
?>