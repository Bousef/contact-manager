<!DOCTYPE html>

<html lang="en">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="components/styles/navBar.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ac1c3ec324.js" crossorigin="anonymous"></script>
    <link href="components/styles/card.css" rel="stylesheet">
    <link href="components/styles/options.css" rel="stylesheet">
</head>
<body id="body" onload="autofillDetails(<?php echo $json_decoded['contact_id']; ?>)">
    <?php include 'components/navBar.php'; ?>
    <div class="login-title">
        <h2 id="title">Edit Contact</h2>
    </div>

        <!-- Contact form -->
        <div class="login-form">

            <h3>Edit Contact</h3>

            <form id="editContact">
                
                <!-- Include contact form elements -->
                <?php include 'components/contactForm.php'; 
                // Get the request data
                $json_req = file_get_contents('php://input');

                // Turn input data into Object
                parse_str($_SERVER['QUERY_STRING'], $json_decoded);
                ?>
                
                <!-- Address fields container -->
                <div id="addressFieldsContainer">

                    <!-- Initially empty, address form will be added dynamically -->

                </div>

                <!-- Button to toggle address fields -->
                <div class="form-group">
                    <button type="button" id="toggleAddressButton" onclick="toggleAddressField()">Edit Address</button>
                </div>

                <!-- Submit button -->
                <div class="form-group">
                    <button type="button" onclick="doEditContact(<?php echo($json_decoded['contact_id']);?>)">Edit Contact</button>
                </div>

                <!-- Submit result -->
                <span id="editResult"></span>

            </form>

        </div>

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script>

            function autofillDetails(contact_id)
            {

                console.log("autofillDetails function called with contact_id: " + contact_id); //Debugging

                let get_contact_request = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
                get_contact_request.searchParams.append('req_type', 'get');
                get_contact_request.searchParams.append('contact_id', contact_id);

                fetch (get_contact_request,
                    {
                        headers:
                            {
                                "Content-Type": "application/json",
                            },
                        method: 'GET',
                    })
                    .then(async (response) => {

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        let data = await response.json();

                        if (data.success == false) {
                            console.error(data.error_code);
                            console.error(data.error_message);

                            $("#editResult").append("<p>ERROR: Contact not edited  data.success==false</p>");
                        } else if (data.success == true) {
                            let contact = data.contact;

                            document.getElementById("first_name").value = contact.first_name;
                            document.getElementById("last_name").value = contact.last_name;
                            document.getElementById("phone_number").value = contact.phone_number;
                            document.getElementById("email").value = contact.email;

                            let address_form = document.getElementById("address_form");

                            if (address_form) {
                                let get_address_request = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/addresses.php");

                                get_address_request.searchParams.append('req_type', 'get');
                                get_address_request.searchParams.append('contact_id', contact_id);

                                fetch(get_address_request,
                                    {
                                        headers:
                                            {
                                                "Content-Type": "application/json",
                                            },
                                        method: 'GET',
                                    })
                                    .then(async (response) => {

                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }

                                        let data = await response.json();

                                        if (data.success == false) {
                                            console.log(data);

                                            $("#editResult").append("<p>Contact Update Failed</p>");
                                        } else if (data.success == true) {
                                            let address = data.address;

                                            address_form.querySelector('#address_line_01').value = address.address_line_01;
                                            address_form.querySelector('#address_line_02').value = address.address_line_02;
                                            address_form.querySelector('#city').value = address.city;
                                            address_form.querySelector('#state').value = address.state;
                                            address_form.querySelector('#zip_code').value = address.zip_code;
                                        }
                                    })
                                    .catch(error => {
                                        console.error(error);
                                        $("#editResult").append("<p>ERROR: Contact not edited  .catch(error2</p>");
                                    });
                            }
                        }
                    })

            }

            // Function to handle form submission
            function doEditContact(contact_id) {

                event.preventDefault();
                document.getElementById("editResult").innerHTML = "";

                let edit_contact_request = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
                //let addressRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/addresses.php");

                edit_contact_request.searchParams.append('req_type', 'update');
                edit_contact_request.searchParams.append('user_id', sessionStorage.getItem("userID"));
                edit_contact_request.searchParams.append('contact_id', contact_id);
                edit_contact_request.searchParams.append('first_name', document.getElementById("first_name").value);
                edit_contact_request.searchParams.append('last_name', document.getElementById("last_name").value || null);
                edit_contact_request.searchParams.append('phone_number', document.getElementById("phone_number").value || null);
                edit_contact_request.searchParams.append('email', document.getElementById("email").value || null);

                fetch(edit_contact_request,
                    {
                        headers:
                            {
                                "Content-Type": "application/json",
                            },
                        method: 'GET',
                    })
                    .then(async (response) => {

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        let data = await response.json();

                        if (data.success == false) {
                            console.error(data.error_code);
                            console.error(data.error_message);

                            $("#editResult").append("<p>ERROR: Contact not edited  data.success==false</p>");
                        } else if (data.success == true) {
                            let address_form = document.getElementById("address_form");

                            if (address_form) {
                                let edit_address_request = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/addresses.php");

                                edit_address_request.searchParams.append('req_type', 'update');
                                edit_address_request.searchParams.append('contact_id', contact_id);
                                edit_address_request.searchParams.append('address_line_01', address_form.querySelector('#address_line_01').value || null);
                                edit_address_request.searchParams.append('address_line_02', address_form.querySelector('#address_line_02').value || null);
                                edit_address_request.searchParams.append('city', address_form.querySelector('#city').value || null);
                                edit_address_request.searchParams.append('state', address_form.querySelector('#state').value || null);
                                edit_address_request.searchParams.append('zip_code', address_form.querySelector('#zip_code').value || null);
                                
                                fetch(edit_address_request,
                                    {
                                        headers:
                                            {
                                                "Content-Type": "application/json",
                                            },
                                        method: 'GET',
                                    })
                                    .then(async (response) => {

                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }

                                        let data = await response.json();

                                        if (data.success == false) {
                                            console.log(data);

                                            $("#editResult").append("<p>Contact Update Failed</p>");
                                        } else if (data.success == true) {
                                            $("#editResult").append("<p>Contact Edited Successfully</p>");
                                            window.location.href = "/contactsPage.php";
                                        }
                                    })
                                    .catch(error => {
                                        console.error(error);
                                        $("#editResult").append("<p>ERROR: Contact not edited  .catch(error2</p>");
                                    });


                            }
                        }
                    })
            }
                

            // Function to toggle address fields
            function toggleAddressField()
            {

                // ...
                let container = document.getElementById('addressFieldsContainer');
                let button = document.getElementById('toggleAddressButton');

                // ...
                if (container.children.length > 0) 
                {
                    container.innerHTML = '';
                    button.textContent = 'Edit Address';
                } 
                else 
                {
                    
                    let newField = document.createElement('div');
                    newField.classList.add('addressField');
                    newField.innerHTML = `<?php include 'components/addressForm.php'; ?>`;
                    container.appendChild(newField);
                    button.textContent = 'Remove Address';

                    // Required fields for address
                    let address_line_01 = newField.querySelector(".address_line_01");
                    let city = newField.querySelector(".city");
                    let state = newField.querySelector(".state");
                    let zip_code = newField.querySelector(".zip_code");

                    // Optional fields for address
                    let address_line_02 = newField.querySelector(".address_line_02");

                    // Add required attribute to the required fields
                    if (address_line_01) address_line_01.required = false;
                    if (city) city.required = false;
                    if (state) state.required = false;
                    if (zip_code) zip_code.required = false;

                    // Remove required attribute from the optional fields
                    if (address_line_02) address_line_02.required = false;

                    // Add placeholder text for the required fields
                    if (address_line_01) address_line_01.placeholder = "Optional";
                    if (city) city.placeholder = "Optional";
                    if (state) state.placeholder = "Optional";
                    if (zip_code) zip_code.placeholder = "Optional";

                    // Add placeholder text for the optional fields
                    if (address_line_02) address_line_02.placeholder = "Optional";

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

            // Remove required attributes from the optional fields
            last_name.required = false;
            phone_number.required = false;
            email.required = false;

            // Add optional placeholder text to the optional fields
            last_name.placeholder = "Optional";
            phone_number.placeholder = "Optional";
            email.placeholder = "Optional";

            // Add required placeholder text to the required fields
            first_name.placeholder = "Optional";
        </script>
        
    </body>

</html>
