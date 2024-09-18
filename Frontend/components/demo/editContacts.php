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
        include 'components/demo/cardDemo.php';

        $contactId = $_GET['contac_id'];

        //Fetch details of the contact
        
    ?>
    <div class="login-title">
        <h2 id="title">Edit Contact</h2>
    </div>

    <div class="login-form">
        <h3>Edit Contact</h3>
        <form id="editContactForm" method = "POST" onsubmit="return doEdit(contactId)">
        
            <!-- First Name -->
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <!-- It would be nice to have placeholders of the current details of the contact -->
                <input class="textForm" type="text" id="first_name" name="first_name">
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input class="textForm" type="text" id="last_name" name="last_name">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input class="textForm" type="email" id="email" name="email">
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input class="textForm" type="tel" id="phone_number" name="phone_number" >
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="street_address">Street Address:</label>
                <input class="textForm" type="text" id="address_line_01" name="address_line_01" placeholder="123 Candyland Ln"  >
                <label for="street_address_2">Street Address 2:</label>
                <input class="textForm" type="text" id="address_line_02" name="address_line_02" placeholder="Apt 4B">
                <label for="city">City:</label>
                <input class="textForm" type="text" id="city" name="city" placeholder="Orlando" >
                <label for="state">State:</label>
                <input class="textForm" type="text" id="state" name="state" placeholder="FL" >
                <label for="zip_code">Zip code:</label>
                <input class="textForm" type="text" id="zip_code" name="zip_code" placeholder="12345" >
            </div>

            <!-- Submit Button -->
            <span id="loginResult"></span>
            <div class="form-group">
                <input class="buttonAdd" type="submit" value="Edit Contact">
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function doEdit(contactId) {
            event.preventDefault();

            

            document.getElementById("loginResult").innerHTML = " ";

            let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");

            urlRequest.searchParams.append('req_type', 'update');
            urlRequest.searchParams.append('user_id', 1);
            urlRequest.searchParams.append('first_name', document.getElementById("first_name").value);
            urlRequest.searchParams.append('last_name', document.getElementById("last_name").value);
            urlRequest.searchParams.append('phone_number', document.getElementById("phone_number").value);
            urlRequest.searchParams.append('email', document.getElementById("email").value);
            urlRequest.searchParams.append('address_line_01', document.getElementById("address_line_01").value);
            urlRequest.searchParams.append('address_line_02', document.getElementById("address_line_02").value);
            urlRequest.searchParams.append('city', document.getElementById("city").value);
            urlRequest.searchParams.append('state', document.getElementById("state").value);
            urlRequest.searchParams.append('zip_code', document.getElementById("zip_code").value);

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
                    window.location.href = "https://jo531962ucf.xyz/components/demo/cardDemo.php";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $("#loginResult").append("<p>ERROR: Contact not updated </p>");
            });

            // Return false to prevent the default form submission
            return false;
        }

    </script>
</body>
</html>