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
    <?php
        include 'components/navBar.php';
    ?>
    <div class="login-title">
        <h2 id="title">Add Contact</h2>
    </div>

    <div class="login-form">
        <h3>Add New Contact</h3>
        <form id="addContact" onsubmit="return doAddContact(event)">
        
            <!-- First Name -->
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input class="textForm" type="text" id="first_name" name="first_name" required>
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input class="textForm" type="text" id="last_name" name="last_name" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input class="textForm" type="email" id="email" name="email" required>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input class="textForm" type="tel" id="phone_number" name="phone_number" required>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="street_address">Unit or apartment number and street address:</label>
                <input class="textForm" type="text" id="street_address" name="street_address" placeholder="123 Candyland Ln" required>
                <label for="street_address">Unit or apartment number and street address 2:</label>
                <input class="textForm" type="text" id="street_address" name="street_address" placeholder="Apt 1A" required>
                <label for="city">City:</label>
                <input class="textForm" type="text" id="city" name="city" placeholder="Orlando" required>
                <label for="state">State:</label>
                <input class="textForm" type="text" id="state" name="state" placeholder="FL" required>
                <label for="zip_code">Zip code:</label>
                <input class="textForm" type="text" id="zip_code" name="zip_code" placeholder="12345" required>
            </div>

            <!-- Submit Button -->
            <span id="loginResult"></span>
            <div class="form-group">
                <input class="buttonAdd" type="submit" value="Add Contact">
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function doAddContact(event) {
            event.preventDefault();

            document.getElementById("loginResult").innerHTML = " ";

            let contactUrl = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/create_contact.php");
            let addressUrl = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/create_address.php");

            let contactData = {
                req_type: 'create',
                user_id: 1,
                first_name: document.getElementById("first_name").value,
                last_name: document.getElementById("last_name").value,
                phone_number: document.getElementById("phone_number").value,
                email: document.getElementById("email").value
            };

            fetch(contactUrl, {
                headers: {
                    "Content-Type": "application/json",
                },
                method: 'POST',
                body: JSON.stringify(contactData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success == false) {
                    $("#loginResult").append("<p>ERROR: Contact not created </p>");
                } else {
                    let contact_id = data.contact_id;
                    let addressData = {
                        req_type: 'create',
                        contact_id: contact_id,
                        street_address: document.getElementById("street_address").value,
                        street_address_2: document.getElementById("street_address_2").value,
                        city: document.getElementById("city").value,
                        state: document.getElementById("state").value,
                        zip_code: document.getElementById("zip_code").value
                    };

                    fetch(addressUrl, {
                        headers: {
                            "Content-Type": "application/json",
                        },
                        method: 'POST',
                        body: JSON.stringify(addressData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success == false) {
                            $("#loginResult").append("<p>ERROR: Address not created </p>");
                        } else {
                            window.location.href = "https://jo531962ucf.xyz/components/demo/cardDemo.php";
                        }
                    });
                }
            });

            //Return false to prevent the default form submission
            return false;
        }

        document.addEventListener('DOMContentLoaded', function() {
     
            document.getElementById('addContact').addEventListener('submit', doAddContact);
        });
    </script>
</body>
</html>