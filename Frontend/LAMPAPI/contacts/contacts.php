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
    require_once 'create_address_for_contact.php';

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

            // Ensure all necessary parameters are set (including parameters not yet handled by function)
            if 
            (
                isset($json_decoded['user_id'])         &&
                isset($json_decoded['first_name'])      &&
                isset($json_decoded['last_name'])       &&
                isset($json_decoded['phone_number'])    &&
                isset($json_decoded['email'])  &&
                isset($json_decoded['street_address']) &&
                    isset($json_decoded['street_address_2']) &&
                    isset($json_decoded['city']) &&
                    isset($json_decoded['state']) &&
                    isset($json_decoded['zip_code'])
                //&&
               // isset($json_decoded['street_address'])
            )
            {
                create_contact_for_user($json_decoded['user_id'], $json_decoded['first_name'], $json_decoded['last_name'], $json_decoded['phone_number'],
                 $json_decoded['email'], $json_decoded['street_address'], $json_decoded['street_address_2'], $json_decoded['city'], $json_decoded['state'], $json_decoded['zip_code']);
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

            // Ensure all necessary parameters are set
            if 
            (
                isset($json_decoded['user_id'])         &&
                isset($json_decoded['contact_id'])      &&
                isset($json_decoded['first_name'])      &&
                isset($json_decoded['last_name'])       &&
                isset($json_decoded['phone_number'])    &&
                isset($json_decoded['email'])
            )
            {
                update_contact_for_user($json_decoded['user_id'], $json_decoded['contact_id'], $json_decoded['first_name'], $json_decoded['last_name'], $json_decoded['phone_number'], $json_decoded['email']);
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
        case 'address':
            {
                if
                (
                    isset($json_decoded['contact_id']) &&
                    isset($json_decoded['street_address']) &&
                    isset($json_decoded['street_address_2']) &&
                    isset($json_decoded['city']) &&
                    isset($json_decoded['state']) &&
                    isset($json_decoded['zip_code'])
                )
                {
                    create_address_for_contact($json_decoded['contact_id'], $json_decoded['street_address'],$json_decoded['street_address_2'],$json_decoded['city'], $json_decoded['state'], $json_decoded['zip_code']);
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