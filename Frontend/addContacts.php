<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Contacts Page</title>
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
        <div class="login-form">

            <h3>Add Contact</h3>

            <form id="addContact">
                
                <!-- Include contact form elements -->
                <?php include 'components/contactForm.php'; ?>
                
                <!-- Address fields container -->
                <div id="addressFieldsContainer">

                    <!-- Initially empty, address form will be added dynamically -->

                </div>

                <!-- Button to toggle address fields -->
                <div class="form-group">
                    <button type="button" id="toggleAddressButton" onclick="toggleAddressField()">Add Address</button>
                </div>

                <!-- Submit button -->
                <div class="form-group">
                    <input class="buttonAdd" type="button" value="Add Contact" onclick="doAddContact()">
                </div>

                <!-- Submit result -->
                <span id="addResult"></span>

            </form>

        </div>

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script>

            // Function to handle form submission
            function doAddContact(event) 
            {

                event.preventDefault();
                document.getElementById("addResult").innerHTML = "";

                let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
                let addressRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/addresses.php");

                urlRequest.searchParams.append('req_type', 'create');
                urlRequest.searchParams.append('user_id', sessionStorage.getItem("userID"));
                urlRequest.searchParams.append('first_name', document.getElementById("first_name").value);
                urlRequest.searchParams.append('last_name', document.getElementById("last_name").value);
                urlRequest.searchParams.append('phone_number', document.getElementById("phone_number").value);
                urlRequest.searchParams.append('email', document.getElementById("email").value);

                let addressField = document.querySelector('.addressField');

                if (addressField) 
                {
                    addressRequest.searchParams.append('req_type', 'create');
                    addressRequest.searchParams.append('address_line_01', addressField.querySelector('.address_line_01').value);
                    addressRequest.searchParams.append('address_line_02', addressField.querySelector('.address_line_02').value);
                    addressRequest.searchParams.append('city', addressField.querySelector('.city').value);
                    addressRequest.searchParams.append('state', addressField.querySelector('.state').value);
                    addressRequest.searchParams.append('zip_code', addressField.querySelector('.zip_code').value);
                }

                fetch(urlRequest, 
                {
                    headers: 
                    {
                        "Content-Type": "application/json",
                    },
                    method: 'GET',
                })
                .then(async (response) => 
                {

                    if (!response.ok) 
                    {
                        throw new Error('Network response was not ok');
                    }

                    let data = await response.json();

                    if (data.success == false) 
                    {
                        $("#addResult").append("<p>ERROR: Contact not created </p>");
                    } 
                    else if (data.success == true) 
                    {

                        if (addressField) 
                        {
                            addressRequest.searchParams.append('contact_id', data.result);
                            fetch(addressRequest, 
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
                            .catch(error => 
                            {
                                $("#addResult").append("<p>ERROR: Contact not created </p>");
                            });
                        }

                        $("#addResult").append("<p>Contact created successfully</p>");

                    }
                })
                .catch(error => 
                {
                    $("#addResult").append("<p>ERROR: Contact not created </p>");
                });

                return false;

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
                    button.textContent = 'Add Address';
                } 
                else 
                {
                    let newField = document.createElement('div');
                    newField.classList.add('addressField');
                    newField.innerHTML = `<?php include 'components/addressForm.php'; ?>`;
                    container.appendChild(newField);
                    button.textContent = 'Remove Address';
                }
                
            }

            // Get the document elements of the form inputs for required fields
            let first_name = document.getElementById("first_name");
            let address_line_01 = document.querySelector(".address_line_01");
            let city = document.querySelector(".city");
            let state = document.querySelector(".state");
            let zip_code = document.querySelector(".zip_code");

            // Get the document elements of the form inputs for optional fields
            let last_name = document.getElementById("last_name");
            let phone_number = document.getElementById("phone_number");
            let email = document.getElementById("email");
            let address_line_02 = document.querySelector(".address_line_02");

            // Add required attributes to the required fields
            first_name.required = true;
            if (address_line_01) address_line_01.required = true;
            if (city) city.required = true;
            if (state) state.required = true;
            if (zip_code) zip_code.required = true;

            // Remove required attributes from the optional fields
            last_name.required = false;
            phone_number.required = false;
            email.required = false;
            if (address_line_02) address_line_02.required = false;

            // Add optional placeholder text to the optional fields
            last_name.placeholder = "Optional";
            phone_number.placeholder = "Optional";
            email.placeholder = "Optional";
            if (address_line_02) address_line_02.placeholder = "Optional";

            // Add required placeholder text to the required fields
            first_name.placeholder = "Required";
            if (address_line_01) address_line_01.placeholder = "Required";
            if (city) city.placeholder = "Required";
            if (state) state.placeholder = "Required";
            if (zip_code) zip_code.placeholder = "Required";

        </script>
        
    </body>

</html>
