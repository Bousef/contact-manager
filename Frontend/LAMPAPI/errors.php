<?php

    class ErrorCodes 
    {
        const DATABASE_CONNECTION_FAILED = ['code' => 1, 'message' => 'Database connection failed'];
        const STATEMENT_PREPARATION_FAILED = ['code' => 2, 'message' => 'Failed to prepare statement'];
        const STATEMENT_EXECUTION_FAILED = ['code' => 3, 'message' => 'Failed to execute statement'];
        const USER_NOT_FOUND = ['code' => 4, 'message' => 'User not found'];
        const USER_CREATION_FAILED = ['code' => 5, 'message' => 'Failed to create user'];
        const NO_ROWS_UPDATED = ['code' => 6, 'message' => 'No rows updated'];
        const NO_ROWS_DELETED = ['code' => 7, 'message' => 'No rows deleted'];
        const INVALID_ACTION = ['code' => 8, 'message' => 'Invalid action'];
        const INVALID_REQUEST_METHOD = ['code' => 9, 'message' => 'Invalid request method'];
        const CONTACT_CREATION_FAILED = ['code' => 10, 'message' => 'Failed to create contact'];
        const CONTACT_NOT_FOUND = ['code' => 11, 'message' => 'Contact not found'];
        const INVALID_REQUEST = ['code' => 20, 'message' => 'Invalid Query Request'];
        const MISSING_PARAMETERS = ['code' => 21, 'message' => 'Query Request Missing Parameters'];
        const INVALID_PASSWORD = ['code' => 22, 'message' => 'Invalid password'];
        const ADDRESS_CREATION_FAILED = ['code' => 23, 'message' => 'Failed to create address'];
        const ADDRESS_NOT_FOUND = ['code' => 24, 'message' => 'Address not found'];
        const INVALID_PHONE_NUMBER = ['code' => 25, 'message' => 'Invalid phone number'];
        const INVALID_EMAIL = ['code' => 26, 'message' => 'Invalid email address']; // New error code for invalid email address
    }

    function send_error_response($error_code) 
    {
        echo json_encode([
            'success' => false, 
            'error_code' => $error_code['code'], 
            'error_message' => $error_code['message']
        ]);
    }

    function get_error_codes() 
    {
        $reflection = new ReflectionClass('ErrorCodes');
        $constants = $reflection->getConstants();
        echo json_encode($constants);
    }

    // Check if the request is to get error codes
    if (isset($_GET['action']) && $_GET['action'] === 'get_error_codes') 
    {
        get_error_codes();
    }

?>