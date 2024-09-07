<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Include the database connection and error handling files
    require_once 'database.php';
    require_once 'errors.php';

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

        // Open a connection to the database
        $conn = open_connection_to_database();
        if ($conn === null) 
        {
            // If the connection failed, return an error response
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;
        }

        // Prepare the SQL statement to read all contacts for the user
        $stmt = $conn->prepare("CALL read_contacts_for_user(?, '')");
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
        $result = $stmt->get_result();
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

        }

        // Close the statement
        $stmt->close();

        // Prepare the SQL statement to delete all contacts for the user
        $delete_contacts_stmt = $conn->prepare("CALL delete_contacts_for_user(?)");
        if (!$delete_contacts_stmt) 
        {
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;
        }

        // Bind the parameters to the SQL statement
        $delete_contacts_stmt->bind_param("i", $user_id);
        if (!$delete_contacts_stmt->execute()) 
        {
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $delete_contacts_stmt->close();
            close_connection_to_database($conn);
            return;
        }

        $delete_contacts_stmt->close();

        // Prepare the SQL statement to delete the user
        $stmt = $conn->prepare("CALL delete_user(?)");
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
        $result = $stmt->get_result()->fetch_assoc();
        if ($result['exit_status'] == 0) 
        {
            send_error_response(ErrorCodes::NO_ROWS_DELETED);
        } 
        else 
        {
            echo json_encode([
                'success' => (bool) $result['exit_status']
            ]);
        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
    
        create_user($1, $username, $password, $first_name, $last_name);
    
        echo "User created successfully!";
    }

?>