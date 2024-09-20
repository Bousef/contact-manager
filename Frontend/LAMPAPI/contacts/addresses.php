<?php

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Include the database connection and error handling files
    require_once '../database.php';
    require_once '../errors.php';

    // Include the address functions files
    require_once 'addresses/create_address_for_contact.php';
    require_once 'addresses/read_address_for_contact.php';
    require_once 'addresses/update_address_for_contact.php';
    require_once 'addresses/delete_address_for_contact.php';

    // Get the request data
    $json_req = file_get_contents('php://input');

    // Turn input data into Object
    $json_decoded = json_decode($json_req, true);

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

    // Switch case to handle the different kinds of actions taken on addresses
    switch($json_decoded['req_type'])
    {

        case 'create':
        {

            // Ensure all necessary parameters are set
            if 
            (
                isset($json_decoded['contact_id'])      && 
                isset($json_decoded['address_line_01']) && 
                isset($json_decoded['city'])            && 
                isset($json_decoded['state'])           && 
                isset($json_decoded['zip_code'])
            ) 
            {

                // Optional parameters
                $address_line_02 = isset($json_decoded['address_line_02']) ? $json_decoded['address_line_02'] : null;

                // Call the function to create the address
                create_address_for_contact($json_decoded['contact_id'], $json_decoded['address_line_01'], $json_decoded['address_line_02'], $json_decoded['city'], $json_decoded['state'], $json_decoded['zip_code']);
            } 
            else 
            {
                send_error_response(ErrorCodes::INVALID_REQUEST);
            }

            break;
            
        }

        case 'read':
        {

            // Ensure all necessary parameters are set
            if (isset($json_decoded['contact_id'])) 
            {
                read_address_for_contact($json_decoded['contact_id']);
            } 
            else 
            {
                send_error_response(ErrorCodes::INVALID_REQUEST);
            }

            break;

        }

        case 'update':
        {

            // Ensure all necessary parameters are set
            if (isset($json_decoded['contact_id'])) 
            {

                // Optional parameters
                $address_line_01 = isset($json_decoded['address_line_01']) ? $json_decoded['address_line_01'] : null;
                $address_line_02 = isset($json_decoded['address_line_02']) ? $json_decoded['address_line_02'] : null;
                $city = isset($json_decoded['city']) ? $json_decoded['city'] : null;
                $state = isset($json_decoded['state']) ? $json_decoded['state'] : null;
                $zip_code = isset($json_decoded['zip_code']) ? $json_decoded['zip_code'] : null;

                // Call the function to update the address
                update_address_for_contact($json_decoded['contact_id'], $address_line_01, $address_line_02, $city, $state, $zip_code);

            } 
            else 
            {
                send_error_response(ErrorCodes::INVALID_REQUEST);
            }

            break;
        }

        case 'delete':
        {

            // Ensure all necessary parameters are set
            if (isset($json_decoded['contact_id'])) 
            {
                delete_address_for_contact($json_decoded['contact_id']);
            } 
            else 
            {
                send_error_response(ErrorCodes::INVALID_REQUEST);
            }

            break;

        }

        default:
        {

            send_error_response(ErrorCodes::INVALID_REQUEST);
            break;

        }

    }

?>