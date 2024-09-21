<?php

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Include the database connection and error handling files
    require_once '../database.php';
    require_once '../errors.php';

    // Include the contact functions files
    require_once 'create_contact_for_user.php';
    require_once 'read_contact_for_user.php';
    require_once 'read_contacts_for_user.php';
    require_once 'update_contact_for_user.php';
    require_once 'delete_contact_for_user.php';

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

    case 'create':
        {

            // Ensure the necessary parameters are set
            if 
            (
                isset($json_decoded['user_id'])     && 
                isset($json_decoded['first_name'])
            )
            {

                // Initialize optional parameters
                $last_name = isset($json_decoded['last_name']) ? $json_decoded['last_name'] : null;
                $phone_number = isset($json_decoded['phone_number']) ? $json_decoded['phone_number'] : null;
                $email = isset($json_decoded['email']) ? $json_decoded['email'] : null;
        
                // Validate and sanitize phone number if provided
                if ($phone_number !== null)
                {

                    // Clean the phone number to the format xxxxxxxxxx, remove country code, area code, and any other characters
                    $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
                    $phone_number = substr($phone_number, -10);
        
                    // If the phone number is not 10 digits, return an error
                    if (strlen($phone_number) != 10)
                    {
                        send_error_response(ErrorCodes::INVALID_PHONE_NUMBER);
                        return;
                    }

                }
        
                // Validate and sanitize email if provided
                if ($email !== null)
                {

                    // Clean the email to remove any characters that are not allowed
                    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
                    // If the email is not valid, return an error
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        send_error_response(ErrorCodes::INVALID_EMAIL);
                        return;
                    }

                }
        
                // Call the function to create a contact for a user
                create_contact_for_user($json_decoded['user_id'], $json_decoded['first_name'], $last_name, $phone_number, $email);

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
                isset($json_decoded['user_id']) &&
                isset($json_decoded['contact_id'])
            )
            {
                read_contact_for_user($json_decoded['user_id'], $json_decoded['contact_id']);
            }
            else
            {
                send_error_response(ErrorCodes::MISSING_PARAMETERS);
                return;
            }

            break;

        }

        case 'search':
        {

            // Ensure all necessary parameters are set
            if 
            (
                isset($json_decoded['user_id'])         &&
                isset($json_decoded['search_string'])
            )
            {
                read_contacts_for_user($json_decoded['user_id'], $json_decoded['search_string']);
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

            // Ensure the necessary parameters are set
            if 
            (
                isset($json_decoded['user_id'])         && 
                isset($json_decoded['contact_id'])
            )
            {

                // Initialize optional parameters
                $first_name = isset($json_decoded['first_name']) ? $json_decoded['first_name'] : null;
                $last_name = isset($json_decoded['last_name']) ? $json_decoded['last_name'] : null;
                $phone_number = isset($json_decoded['phone_number']) ? $json_decoded['phone_number'] : null;
                $email = isset($json_decoded['email']) ? $json_decoded['email'] : null;
        
                // Validate and sanitize phone number if provided
                if ($phone_number !== null)
                {

                    // Clean the phone number to the format xxxxxxxxxx, remove country code, area code, and any other characters
                    $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
                    $phone_number = substr($phone_number, -10);
        
                    // If the phone number is not 10 digits, return an error
                    if (strlen($phone_number) != 10)
                    {
                        send_error_response(ErrorCodes::INVALID_PHONE_NUMBER);
                        return;
                    }

                }
        
                // Validate and sanitize email if provided
                if ($email !== null)
                {

                    // Clean the email to remove any characters that are not allowed
                    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
                    // If the email is not valid, return an error
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        send_error_response(ErrorCodes::INVALID_EMAIL);
                        return;
                    }

                }
        
                // Call the function to update a contact for a user
                update_contact_for_user($json_decoded['user_id'], $json_decoded['contact_id'], $first_name, $last_name, $phone_number, $email);
                
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
                isset($json_decoded['user_id'])         &&
                isset($json_decoded['contact_id'])
            )
            {
                delete_contact_for_user($json_decoded['user_id'], $json_decoded['contact_id']);
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