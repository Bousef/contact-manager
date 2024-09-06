<?php
header('Content-Type: application/json');
require_once 'database.php';
require_once 'ErrorCodes.php';

/**
 * Create a new user in the database.
 *
 * @param string $username
 * @param string $password
 * @param string $first_name
 * @param string $last_name
 */
function create_user($username, $password, $first_name, $last_name) 
{
    $conn = open_connection_to_database();
    if ($conn === null) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']]);
        return;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("CALL create_user(?, ?, ?, ?)");
    if (!$stmt) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::STATEMENT_PREPARATION_FAILED['code'], 'error_message' => ErrorCodes::STATEMENT_PREPARATION_FAILED['message']]);
        close_connection_to_database($conn);
        return;
    }

    $stmt->bind_param("ssss", $username, $hashed_password, $first_name, $last_name);
    if (!$stmt->execute()) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::STATEMENT_EXECUTION_FAILED['code'], 'error_message' => ErrorCodes::STATEMENT_EXECUTION_FAILED['message']]);
        $stmt->close();
        close_connection_to_database($conn);
        return;
    }

    $result = $stmt->get_result()->fetch_assoc();
    if ($result === null || $result['user_id'] === null) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::USER_CREATION_FAILED['code'], 'error_message' => ErrorCodes::USER_CREATION_FAILED['message']]);
    } 
    else 
    {
        echo json_encode(['success' => true, 'user_id' => $result['insert_id']]);
    }

    $stmt->close();
    close_connection_to_database($conn);
}

/**
 * Read a user from the database.
 *
 * @param int $user_id
 */
function read_user($user_id) 
{
    $conn = open_connection_to_database();
    if ($conn === null) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']]);
        return;
    }

    $stmt = $conn->prepare("CALL read_user(?)");
    if (!$stmt) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::STATEMENT_PREPARATION_FAILED['code'], 'error_message' => ErrorCodes::STATEMENT_PREPARATION_FAILED['message']]);
        close_connection_to_database($conn);
        return;
    }

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::STATEMENT_EXECUTION_FAILED['code'], 'error_message' => ErrorCodes::STATEMENT_EXECUTION_FAILED['message']]);
        $stmt->close();
        close_connection_to_database($conn);
        return;
    }

    $result = $stmt->get_result()->fetch_assoc();
    if ($result === null) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::USER_NOT_FOUND['code'], 'error_message' => ErrorCodes::USER_NOT_FOUND['message']]);
    } 
    else 
    {
        echo json_encode(['success' => true, 'result' => $result]);
    }

    $stmt->close();
    close_connection_to_database($conn);
}

/**
 * Update a user in the database.
 *
 * @param int $user_id
 * @param string $username
 * @param string $password
 * @param string $first_name
 * @param string $last_name
 */
function update_user($user_id, $username, $password, $first_name, $last_name) 
{
    $conn = open_connection_to_database();
    if ($conn === null) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']]);
        return;
    }

    $stmt = $conn->prepare("CALL update_user(?, ?, ?, ?, ?)");
    if (!$stmt) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::STATEMENT_PREPARATION_FAILED['code'], 'error_message' => ErrorCodes::STATEMENT_PREPARATION_FAILED['message']]);
        close_connection_to_database($conn);
        return;
    }

    $stmt->bind_param("issss", $user_id, $username, $password, $first_name, $last_name);
    if (!$stmt->execute()) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::STATEMENT_EXECUTION_FAILED['code'], 'error_message' => ErrorCodes::STATEMENT_EXECUTION_FAILED['message']]);
        $stmt->close();
        close_connection_to_database($conn);
        return;
    }

    $result = $stmt->get_result()->fetch_assoc();
    if ($result === null || $result['exit_status'] == FALSE) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::USER_UPDATE_FAILED['code'], 'error_message' => ErrorCodes::USER_UPDATE_FAILED['message']]);
    } 
    else 
    {
        echo json_encode(['success' => true]);
    }

    $stmt->close();
    close_connection_to_database($conn);
}

/**
 * Delete a user from the database.
 *
 * @param int $user_id
 */
function delete_user($user_id) 
{
    $conn = open_connection_to_database();
    if ($conn === null) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::DATABASE_CONNECTION_FAILED['code'], 'error_message' => ErrorCodes::DATABASE_CONNECTION_FAILED['message']]);
        return;
    }

    $stmt = $conn->prepare("CALL delete_user(?)");
    if (!$stmt) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::STATEMENT_PREPARATION_FAILED['code'], 'error_message' => ErrorCodes::STATEMENT_PREPARATION_FAILED['message']]);
        close_connection_to_database($conn);
        return;
    }

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::STATEMENT_EXECUTION_FAILED['code'], 'error_message' => ErrorCodes::STATEMENT_EXECUTION_FAILED['message']]);
        $stmt->close();
        close_connection_to_database($conn);
        return;
    }

    $result = $stmt->get_result()->fetch_assoc();
    if ($result === null || $result['affected_rows'] === 0) 
    {
        echo json_encode(['success' => false, 'error_code' => ErrorCodes::USER_DELETION_FAILED['code'], 'error_message' => ErrorCodes::USER_DELETION_FAILED['message']]);
    } 
    else 
    {
        echo json_encode(['success' => true, 'affected_rows' => $result['affected_rows']]);
    }

    $stmt->close();
    close_connection_to_database($conn);
}
?>