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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    //   $(".addContactBtn").click(function(){
        document.getElementById("loginResult").innerHTML = " ";
        
        let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
        let data;
        
        urlRequest.searchParams.append('req_type', 'create');
        urlRequest.searchParams.append('user_id', 1);
        urlRequest.searchParams.append('first_name',document.getElementById("first_name").value);
        urlRequest.searchParams.append('last_name',document.getElementById("last_name").value);
        urlRequest.searchParams.append('phone_number', document.getElementById("phone_number").value );
        urlRequest.searchParams.append('email', document.getElementById("email").value);
        //urlRequest.searchParams.append('street_address', document.getElementById("street_address").value);

        console.log(urlRequest.toString());

        fetch(urlRequest, {
          headers: {
          "Content-Type": "application/json",
          },
          method: 'GET',
        })
        .then(async (response) => {
          data = await response.json();
          console.log(data);
          if(data.success == false){
            $("#loginResult").append("<p>ERROR: Contact not created </p>");
          } else if(data.success == true){
            window.location.href = "https://jo531962ucf.xyz/components/demo/cardDemo.php";
          }
        });

        
   // });
    function doAddContact(event) {
            // Prevent the default form submission
            event.preventDefault();

            
            let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
            let data;

            urlRequest.searchParams.append('req_type', 'create');
            urlRequest.searchParams.append('user_id', 1);
            urlRequest.searchParams.append('first_name',document.getElementById("first_name").value);
            urlRequest.searchParams.append('last_name',document.getElementById("last_name").value);
            urlRequest.searchParams.append('phone_number', document.getElementById("phone_number").value );
            urlRequest.searchParams.append('email', document.getElementById("email").value);


            console.log(urlRequest.toString()); 

            fetch(urlRequest, {
                headers: {
                    "Content-Type": "application/json",
                },
                method: 'GET',
            })
            .then(async (response) => {
                data = await response.json();
                console.log(data);
                if (data.success == false) {
                    $("#loginResult").append("<p>ERROR: Contact not created </p>");
                } else if (data.success == true) {
                    window.location.href = "https://jo531962ucf.xyz/components/demo/cardDemo.php";
                }
            });

            //Return false to prevent the default form submission
            return false;
        }
         

      </script>
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

            <!-- Phone Number pattern="\d{3}[-.\s]?\d{3}[-.\s]?\d{4}"
                       placeholder="xxx-xxx-xxxx"  required -->
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input class="textForm" type="tel" id="phone_number" name="phone_number" 
                    >
            </div>

            <!-- Address -->
            <!-- <div class="form-group">
                <label for="address">Unit or apartment number and street address:</label>
                <input class="textForm" type="text" id="street_address" name ="street_address" placeholder="123 Candyland Ln" required>
                <label for="'state">State:</label>
                <input class="textForm" type="text" id="state" name="state" placeholder="FL" required>
                <label for="zipcode">Zip code:</label>
                <input class="textForm" type="text" id="zip_code" name="zip_code" placeholder="12345" required>
            </div> -->

            <!-- Submit Button -->
             <span id="loginResult"></span>
            <div class="form-group">
            <input class="buttonAdd" type="submit" value="Add Contact">
                <!-- <input class="buttonAdd" type="button" value="Add Contact" class="addContactBtn"> -->
                
            </div>
        </form>
    </div>

</body>
</html>