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
    }
    
?>