<?php

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Include the database connection and error handling files
    require_once '../database.php';
    require_once '../errors.php';

    // Include the user functions files
    require_once 'create_user.php';
    require_once 'read_user.php';
    require_once 'update_user.php';
    require_once 'delete_user.php';
    require_once 'login_user.php';

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
                isset($json_decoded['username'])      &&
                isset($json_decoded['password'])      &&
                isset($json_decoded['first_name'])
            )
            {

                // Optional parameters
                $last_name = $json_decoded['last_name'] ?? null;

                // Call the create_user function
                create_user($json_decoded['username'], $json_decoded['password'], $json_decoded['first_name'], $last_name);
                
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
                isset($json_decoded['user_id'])       &&
                isset($json_decoded['username'])      &&
                isset($json_decoded['password'])      &&
                isset($json_decoded['first_name'])    &&
                isset($json_decoded['last_name'])
            )
            {
                http_response_code(219);
                update_user($json_decoded['user_id'], $json_decoded['username'], $json_decoded['password'], $json_decoded['first_name'], $json_decoded['last_name']);
            }
            else
            {
                http_response_code(419);
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
                isset($json_decoded['username'])    &&
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