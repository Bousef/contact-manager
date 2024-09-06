<?php

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
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 
                'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']
            ]);
            return;

        }

        // Hash the user's password using bcrypt
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Prepare the SQL statement to call the stored procedure for creating a user
        $stmt = $conn->prepare("CALL create_user(?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::STATEMENT_PREPARATION_FAILED['code'], 
                'error_message' => ErrorCodes::STATEMENT_PREPARATION_FAILED['message']
            ]);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ssss", $username, $hashed_password, $first_name, $last_name);
        if (!$stmt->execute()) 
        {

            // If the statement execution failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::STATEMENT_EXECUTION_FAILED['code'], 
                'error_message' => ErrorCodes::STATEMENT_EXECUTION_FAILED['message']
            ]);
            $stmt->close();
            close_connection_to_database($conn);
            return;

        }

        // Fetch the result of the statement execution
        $result = $stmt->get_result()->fetch_assoc();
        if ($result === null || $result['user_id'] === null) 
        {

            // If the user creation failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::USER_CREATION_FAILED['code'], 
                'error_message' => ErrorCodes::USER_CREATION_FAILED['message']
            ]);

        } 
        else 
        {

            // If the user creation was successful, return a success response with the user ID
            echo json_encode([
                'success' => true, 
                'result' => $result['insert_id']
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
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 
                'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']
            ]);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for reading a user
        $stmt = $conn->prepare("CALL read_user(?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::STATEMENT_PREPARATION_FAILED['code'], 
                'error_message' => ErrorCodes::STATEMENT_PREPARATION_FAILED['message']
            ]);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameter to the SQL statement
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) 
        {

            // If the statement execution failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::STATEMENT_EXECUTION_FAILED['code'], 
                'error_message' => ErrorCodes::STATEMENT_EXECUTION_FAILED['message']
            ]);
            $stmt->close();
            close_connection_to_database($conn);
            return;

        }

        // Fetch the result of the statement execution
        $result = $stmt->get_result()->fetch_assoc();
        if ($result === null) 
        {

            // If the user was not found, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::USER_NOT_FOUND['code'], 
                'error_message' => ErrorCodes::USER_NOT_FOUND['message']
            ]);

        } 
        else 
        {

            // If the user was found, return a success response with the user data
            $filtered_result = 
            [
                'id' => $result['id'],
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
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 
                'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']
            ]);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for updating a user
        $stmt = $conn->prepare("CALL update_user(?, ?, ?, ?, ?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::STATEMENT_PREPARATION_FAILED['code'], 
                'error_message' => ErrorCodes::STATEMENT_PREPARATION_FAILED['message']
            ]);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameters to the SQL statement
        $stmt->bind_param("issss", $user_id, $username, $password, $first_name, $last_name);
        if (!$stmt->execute()) 
        {

            // If the statement execution failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::STATEMENT_EXECUTION_FAILED['code'], 
                'error_message' => ErrorCodes::STATEMENT_EXECUTION_FAILED['message']
            ]);
            $stmt->close();
            close_connection_to_database($conn);
            return;

        }

        // Fetch the result of the statement execution
        $result = $stmt->get_result()->fetch_assoc();
        if ($result === null || $result['exit_status'] == FALSE) 
        {

            // If the user update failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::USER_UPDATE_FAILED['code'], 
                'error_message' => ErrorCodes::USER_UPDATE_FAILED['message']
            ]);

        } 
        else 
        {

            // If the user update was successful, return a success response
            echo json_encode([
                'success' => true
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
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 
                'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']
            ]);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for deleting a user
        $stmt = $conn->prepare("CALL delete_user(?)");
        if (!$stmt) 
        {

            // If the statement preparation failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::STATEMENT_PREPARATION_FAILED['code'], 
                'error_message' => ErrorCodes::STATEMENT_PREPARATION_FAILED['message']
            ]);
            close_connection_to_database($conn);
            return;

        }

        // Bind the parameter to the SQL statement
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) 
        {

            // If the statement execution failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::STATEMENT_EXECUTION_FAILED['code'], 
                'error_message' => ErrorCodes::STATEMENT_EXECUTION_FAILED['message']
            ]);
            $stmt->close();
            close_connection_to_database($conn);
            return;

        }

        // Fetch the result of the statement execution
        $result = $stmt->get_result()->fetch_assoc();
        if ($result === null || $result['affected_rows'] === 0) 
        {

            // If the user deletion failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::USER_DELETION_FAILED['code'], 
                'error_message' => ErrorCodes::USER_DELETION_FAILED['message']
            ]);

        } 
        else 
        {

            // If the user deletion was successful, return a success response with the number of affected rows
            echo json_encode([
                'success' => true
            ]);

        }

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);

    }
    
?>