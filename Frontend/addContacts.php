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
        
            <!-- Include contact form elements -->
            <?php include 'components/contactForm.php'; ?>

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

            let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
            let addressRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/addresses.php");

            urlRequest.searchParams.append('req_type', 'create');
            urlRequest.searchParams.append('user_id', sessionStorage.getItem("userID"));
            urlRequest.searchParams.append('first_name', document.getElementById("first_name").value);
            urlRequest.searchParams.append('last_name', document.getElementById("last_name").value);
            urlRequest.searchParams.append('phone_number', document.getElementById("phone_number").value);
            urlRequest.searchParams.append('email', document.getElementById("email").value);
            addressRequest.searchParams.append('address_line_01', document.getElementById("address_line_01").value);
            addressRequest.searchParams.append('address_line_02', document.getElementById("address_line_02").value);
            addressRequest.searchParams.append('city', document.getElementById("city").value);
            addressRequest.searchParams.append('state', document.getElementById("state").value);
            addressRequest.searchParams.append('zip_code', document.getElementById("zip_code").value);
            addressRequest.searchParams.append('req_type', 'create');

            console.log(urlRequest.toString());

            fetch(urlRequest, {
                headers: {
                    "Content-Type": "application/json",
                },
                method: 'GET',
            })
            .then(async (response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                let data = await response.json();
                console.log(data);
                if (data.success == false) {
                    $("#loginResult").append("<p>ERROR: Contact not created </p>");
                } else if (data.success == true) {
                    addressRequest.searchParams.append('contact_id', data.result)
                    fetch(addressRequest, {
                        headers: {
                        "Content-Type": "application/json",
                        },
                        method: 'GET',
                    })
                    .then(async (response) => {
                        addressData = await response.json();
                        console.log(addressData);
                    })
                    
                    window.location.href = "https://jo531962ucf.xyz/contactsPage.php";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $("#loginResult").append("<p>ERROR: Contact not created </p>");
            });

            // Return false to prevent the default form submission
            return false;
        }

    </script>
</body>
</html>