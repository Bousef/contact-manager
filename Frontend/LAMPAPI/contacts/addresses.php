<?php

// Set the content type to JSON
header('Content-Type: application/json');

// Include the database connection and error handling files
require_once '../database.php';
require_once '../errors.php';

    function create_address_for_user($user_id, $street, $city, $state, $zip_code) 
    {
        $conn = open_connection_to_database();
        if ($conn === null) 
        {
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;
        }

        $stmt = $conn->prepare("CALL create_address_for_user(?, ?, ?, ?, ?)");
        if (!$stmt) 
        {
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;
        }

        $stmt->bind_param("issss", $user_id, $street, $city, $state, $zip_code);
        if (!$stmt->execute()) 
        {
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $stmt->close();
            close_connection_to_database($conn);
            return;
        }

        $result = $stmt->get_result()->fetch_assoc();
        if ($result === null || $result['address_id'] === null) 
        {
            send_error_response(ErrorCodes::ADDRESS_CREATION_FAILED);
        } 
        else 
        {
            echo json_encode([
                'success' => true, 
                'result' => $result['address_id']
            ]);
        }

        $stmt->close();
        close_connection_to_database($conn);
    }

    function read_address_for_user($user_id, $address_id) 
    {
        $conn = open_connection_to_database();
        if ($conn === null) 
        {
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;
        }

        $stmt = $conn->prepare("CALL read_address_for_user(?, ?)");
        if (!$stmt) 
        {
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;
        }

        $stmt->bind_param("ii", $user_id, $address_id);
        if (!$stmt->execute()) 
        {
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $stmt->close();
            close_connection_to_database($conn);
            return;
        }

        $result = $stmt->get_result()->fetch_assoc();
        if ($result === null) 
        {
            send_error_response(ErrorCodes::ADDRESS_NOT_FOUND);
        } 
        else 
        {
            $filtered_result = [
                'id' => $result['address_id'],
                'street' => $result['street'],
                'city' => $result['city'],
                'state' => $result['state'],
                'zip_code' => $result['zip_code']
            ];

            echo json_encode([
                'success' => true, 
                'result' => $filtered_result
            ]);
        }

        $stmt->close();
        close_connection_to_database($conn);
    }

    function read_addresses_for_user($user_id, $search_string) 
    {
        $conn = open_connection_to_database();
        if ($conn === null) 
        {
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;
        }

        $stmt = $conn->prepare("CALL read_addresses_for_user(?, ?)");
        if (!$stmt) 
        {
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;
        }

        $stmt->bind_param("is", $user_id, $search_string);
        if (!$stmt->execute()) 
        {
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $stmt->close();
            close_connection_to_database($conn);
            return;
        }

        $result = $stmt->get_result();
        $addresses = [];
        while ($row = $result->fetch_assoc()) 
        {
            $addresses[] = [
                'id' => $row['address_id'],
                'street' => $row['street'],
                'city' => $row['city'],
                'state' => $row['state'],
                'zip_code' => $row['zip_code']
            ];
        }

        echo json_encode([
            'success' => true, 
            'result' => $addresses
        ]);

        $stmt->close();
        close_connection_to_database($conn);
    }

    function update_address_for_user($user_id, $address_id, $street, $city, $state, $zip_code) 
    {
        $conn = open_connection_to_database();
        if ($conn === null) 
        {
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;
        }

        $stmt = $conn->prepare("CALL update_address_for_user(?, ?, ?, ?, ?, ?)");
        if (!$stmt) 
        {
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;
        }

        $stmt->bind_param("iissss", $user_id, $address_id, $street, $city, $state, $zip_code);
        if (!$stmt->execute()) 
        {
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $stmt->close();
            close_connection_to_database($conn);
            return;
        }

        $result = $stmt->get_result()->fetch_assoc();
        echo json_encode([
            'success' => (bool) $result['exit_status']
        ]);

        $stmt->close();
        close_connection_to_database($conn);
    }

    function delete_address_for_user($user_id, $address_id) 
    {
        $conn = open_connection_to_database();
        if ($conn === null) 
        {
            send_error_response(ErrorCodes::DATABASE_CONNECTION_FAILED);
            return;
        }

        $stmt = $conn->prepare("CALL delete_address_for_user(?, ?)");
        if (!$stmt) 
        {
            send_error_response(ErrorCodes::STATEMENT_PREPARATION_FAILED);
            close_connection_to_database($conn);
            return;
        }

        $stmt->bind_param("ii", $user_id, $address_id);
        if (!$stmt->execute()) 
        {
            send_error_response(ErrorCodes::STATEMENT_EXECUTION_FAILED);
            $stmt->close();
            close_connection_to_database($conn);
            return;
        }

        $result = $stmt->get_result()->fetch_assoc();
        echo json_encode([
            'success' => (bool) $result['exit_status']
        ]);

        $stmt->close();
        close_connection_to_database($conn);
    }

?>