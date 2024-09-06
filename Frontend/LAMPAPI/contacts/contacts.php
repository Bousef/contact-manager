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
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 
                'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']
            ]);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for creating a contact
        $stmt = $conn->prepare("CALL create_contact_for_user(?, ?, ?, ?)");
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
        $stmt->bind_param("isss", $user_id, $first_name, $last_name, $phone_number);
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
        if ($result === null || $result['contact_id'] === null) 
        {

            // If the contact creation failed, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::CONTACT_CREATION_FAILED['code'], 
                'error_message' => ErrorCodes::CONTACT_CREATION_FAILED['message']
            ]);

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
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 
                'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']
            ]);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for reading a contact
        $stmt = $conn->prepare("CALL read_contact_for_user(?, ?)");
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
        $stmt->bind_param("ii", $user_id, $contact_id);
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

            // If the contact was not found, return an error response
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::CONTACT_NOT_FOUND['code'], 
                'error_message' => ErrorCodes::CONTACT_NOT_FOUND['message']
            ]);

        } 
        else 
        {

            // If the contact was found, return a success response with the contact data
            echo json_encode([
                'success' => true, 
                'result' => $result
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
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 
                'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']
            ]);
            return;
        }

        // Prepare the SQL statement to call the stored procedure for reading contacts
        $stmt = $conn->prepare("CALL read_contacts_for_user(?, ?)");
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
        $stmt->bind_param("is", $user_id, $search_string);
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
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Return a success response with the contacts data
        echo json_encode([
            'success' => true, 
            'result' => $result
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
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 
                'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']
            ]);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for updating a contact
        $stmt = $conn->prepare("CALL update_contact_for_user(?, ?, ?, ?, ?)");
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
        $stmt->bind_param("iisss", $user_id, $contact_id, $first_name, $last_name, $phone_number);
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

        // Return a success response with the update status
        echo json_encode([
            'success' => true, 
            'result' => $result['exit_status']
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
            echo json_encode([
                'success' => false, 
                'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 
                'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']
            ]);
            return;

        }

        // Prepare the SQL statement to call the stored procedure for deleting a contact
        $stmt = $conn->prepare("CALL delete_contact_for_user(?, ?)");
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
        $stmt->bind_param("ii", $user_id, $contact_id);
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

        // Return a success response with the delete status
        echo json_encode([
            'success' => true, 
            'result' => $result['exit_status']
        ]);

        // Close the statement and the database connection
        $stmt->close();
        close_connection_to_database($conn);
        
    }

?>