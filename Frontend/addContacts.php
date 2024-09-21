<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    
    <meta charset="UTF-8">
    <title>Contacts</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="components/styles/navBar.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ac1c3ec324.js" crossorigin="anonymous"></script>
    <link href="components/styles/card.css" rel="stylesheet">
</head>
<body id="body">
    <?php include 'components/navBar.php'; ?>
    <?php include 'components/import.php'; ?>
    <div class="login-title">
        <h2 id="title">Add Contact</h2>
    </div>

    <!-- Contact form -->
    <div id="add_contact_form" class="login-form">
        <form>

            <!-- Include contact form elements -->
            <?php include 'components/contactForm.php'; ?>

            <!-- Address form will be included dynamically -->
            <div id="address_container"></div>

            <!-- Add address button -->
            <div class="form-group">
                <button type="button" onclick="address_fields_toggle(this)">Add Address</button>
            </div>

            <!-- Add contact button -->
            <div class="form-group">
                <button type="button" onclick="add_contact()">Add Contact</button>
            </div>

            <!-- Submit result -->
            <span id="form_result_message"></span>

        </form>
    </div>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>

        // Function to handle form submission
        function add_contact() 
        {

            // Clear the form result message
            document.getElementById("form_result_message").innerHTML = "";

            // Set the URL for the API request
            let add_contact_request = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");

            // Add the form data to the request
            add_contact_request.searchParams.append('req_type', 'create');
            add_contact_request.searchParams.append('user_id', sessionStorage.getItem("userID"));
            add_contact_request.searchParams.append('first_name', document.getElementById("first_name").value);
            add_contact_request.searchParams.append('last_name', document.getElementById("last_name").value);
            add_contact_request.searchParams.append('phone_number', document.getElementById("phone_number").value);
            add_contact_request.searchParams.append('email', document.getElementById("email").value);

            fetch(add_contact_request, 
            {
                headers: 
                {
                    "Content-Type": "application/json",
                },
                method: 'GET',
            })
            .then(async (response) => 
            {

                // Check if the response is ok
                if (!response.ok) 
                {
                    throw new Error('Network response was not ok');
                }

                // Wait for the response to be converted to JSON
                let data = await response.json();

                // Check if the contact was created successfully
                if (data.success == false) 
                {
                    $("#form_result_message").append("<p>Contact not created</p>");
                } 
                else if (data.success == true) 
                {
                    
                    // Check if the address form exists
                    let addressField = document.getElementById('address_form');

                    // If the address form exists, send the address data to the API
                    if (addressField) 
                    {

                        // Set the URL for the API request
                        let add_address_request = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/addresses.php");

                        // Add the form data to the request
                        add_address_request.searchParams.append('req_type', 'create');
                        add_address_request.searchParams.append('contact_id', data.result);
                        add_address_request.searchParams.append('address_line_01', document.getElementById('address_line_01').value);
                        add_address_request.searchParams.append('address_line_02', document.getElementById('address_line_02').value);
                        add_address_request.searchParams.append('city', document.getElementById('city').value);
                        add_address_request.searchParams.append('state', document.getElementById('state').value);
                        add_address_request.searchParams.append('zip_code', document.getElementById('zip_code').value);

                        fetch(add_address_request, 
                        {
                            headers: 
                            {
                                "Content-Type": "application/json",
                            },
                            method: 'GET',
                        })
                        .then(async (response) => 
                        {
                            await response.json();
                        })
                        .catch(async (error) => 
                        {

                            // Set the URL for the delete request
                            let delete_contact_request = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
                            delete_contact_request.searchParams.append('req_type', 'delete');
                            delete_contact_request.searchParams.append('user_id', sessionStorage.getItem("userID"));
                            delete_contact_request.searchParams.append('contact_id', data.result);

                            // Delete the contact
                            await fetch(delete_contact_request, 
                            {
                                method: 'POST',
                            })
                            .then(async (response) => 
                            {
                                await response.json();
                            })
                            .catch(error => 
                            {

                                // Display the error message if the delete request fails
                                $("#form_result_message").append("<p>Bad thing happened</p>");

                            });

                            // Display the error message if the address was invalid
                            $("#form_result_message").append("<p>Contact not created because address was invalid</p>");
                            return;
                            
                        });

                    }

                    // Display the success message
                    $("#form_result_message").append("<p>Contact created successfully</p>");

                }
            })
            .catch(error => 
            {

                // Display the error message
                $("#form_result_message").append("<p>ERROR: Contact not created </p>");

            });

        }

        // Function to add or remove address fields after the contact form
        function address_fields_toggle(button) 
        {

            // Get the address container and the address form
            let address_container = document.getElementById('address_container');
            let address_form = document.getElementById('address_form');

            // Check if the address form exists
            if (address_form) 
            {

                // Remove the address fields
                address_form.remove();
                button.textContent = "Add Address";

            } 
            else 
            {

                // Add the address fields
                let address_form_html = `<?php include 'components/addressForm.php'; ?>`;
                address_container.insertAdjacentHTML('beforeend', address_form_html);

                // Required fields for address
                let address_line_01 = document.getElementById("address_line_01");
                let city = document.getElementById("city");
                let state = document.getElementById("state");
                let zip_code = document.getElementById("zip_code");

                // Optional fields for address
                let address_line_02 = document.getElementById("address_line_02");

                // Add required attribute to the required fields (not really necessary to check if they exist)
                if (address_line_01) 
                {
                    address_line_01.required = true;
                    address_line_01.placeholder = "Required";
                }

                if (city) 
                {
                    city.required = true;
                    city.placeholder = "Required";
                }

                if (state) 
                {
                    state.required = true;
                    state.placeholder = "Required";
                }
                
                if (zip_code) 
                {
                    zip_code.required = true;
                    zip_code.placeholder = "Required";
                }

                // Remove required attribute from the optional fields
                if (address_line_02) 
                {
                    address_line_02.required = false;
                    address_line_02.placeholder = "Optional";
                }

                // Change the button text to "Remove Address"
                button.textContent = "Remove Address";
            }

        }

        // Get the document elements of the form inputs for required fields
        let first_name = document.getElementById("first_name");

        // Get the document elements of the form inputs for optional fields
        let last_name = document.getElementById("last_name");
        let phone_number = document.getElementById("phone_number");
        let email = document.getElementById("email");

        // Add required attributes to the required fields
        first_name.required = true;
        first_name.placeholder = "Required";

        // Remove required attributes from the optional fields
        last_name.required = false;
        last_name.placeholder = "Optional";
        phone_number.required = false;
        phone_number.placeholder = "Optional";
        email.required = false;
        email.placeholder = "Optional";

    </script>
</body>
</html>