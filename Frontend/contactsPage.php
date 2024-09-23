<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon & manifest -->
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">

    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>

    <!-- Links -->
    <link rel='stylesheet' href="components/styles/pageGrid.css">
    <link href="components/styles/card.css" rel="stylesheet">
    <link href="components/styles/searchBar.css" rel="stylesheet">
    <link href="components/styles/navBar.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ac1c3ec324.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
    
<body>
    <!-- Include components -->
    <?php
        include 'components/navBar.php';
        include 'components/searchBar.php';
    ?>

    <!-- Main card layout grid -->
    <div class = 'cardGrid'>

        <!-- JS for -->
        <script>
            $(document).ready(function() {
                // Starting grid
                createGrid();
                
                // Delete contact button
                $(".deleteButton").click(function() {
                    let contactId = $(this).data("contact-id");
                    doDelete(contactId);
                });
                
                // Search for contacts and create grid button
                $(".searchSubmitBtn").click(function() {
                    createGrid();
                });
            });

            // Create grid of contacts
            function createGrid() {
                let elements = document.getElementsByClassName("contactWrapper");

                // Remove current displayed cards
                while(elements.length > 0) {
                    elements[0].parentNode.removeChild(elements[0]);
                }

                // Fetch contacts based on search
                let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
                let data, addressData, addressStr;
                urlRequest.searchParams.append('req_type', 'search');
                urlRequest.searchParams.append('user_id', sessionStorage.getItem("userID"));
                urlRequest.searchParams.append('search_string', document.getElementById("searchText").value);
                
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
                        $('.cardGrid').append("<p>No Contacts Found</p>");
                    } else if(data.success == true){
                        data.result.forEach((contact) => {
                            $.ajax({
                                url: 'components/contactCard.php',
                                method: 'GET',
                                success: function(responseHTML) {
                                    let addressRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/addresses.php");
                                    addressRequest.searchParams.append('req_type', 'read');
                                    addressRequest.searchParams.append('contact_id', contact.id.toString());
                                    
                                    console.log(addressRequest.toString());
                                    
                                    fetch(addressRequest, {
                                        headers: {
                                            "Content-Type": "application/json",
                                        },
                                        method: 'GET',
                                    })
                                    .then(async (response) => {
                                        addressData = await response.json();
                                        console.log(addressData);
                                        
                                        if(addressData.success == false){
                                            addressStr = " ";
                                        } else {
                                            addressStr = "" + addressData.result.address_line_01.toString() + " " + addressData.result.city.toString() + " " + addressData.result.state.toString() + ", " + addressData.result.zip_code.toString();
                                        }
                                        console.log(addressStr);
                                        
                                        responseHTML = responseHTML.replaceAll('*CONTACT_NAME*', (contact.first_name + " " + contact.last_name))
                                            .replaceAll('*CONTACT_NUMBER*', contact.phone_number)
                                            .replaceAll('*CONTACT_EMAIL*', contact.email_address)
                                            .replaceAll('*CONTACT_COMPANY*', addressStr)
                                            .replaceAll('*CONTACT_ID*', contact.id)
                                            //Replace undefined fields with empty
                                            .replaceAll("undefined", " ")
                                            .replaceAll("null", " ")
                                            .replaceAll('<h4 class="company">  , </h4>', '<h4 class="company"> </h4>')
                                            .replaceAll(' " class="contactBtn"', ' " class="contactBtn greyBtn"');
                                        $('.cardGrid').append(responseHTML);
                                    });
                                }
                            });
                        });
                    }
                });
            }
            
            // Add contact button
            $(".addContactBtn").click(function(){
                window.location.href = "https://jo531962ucf.xyz/addContacts.php";
            });
    
            // Edit contact button
            function doEdit(contactId) {
                window.location.href = "https://jo531962ucf.xyz/editContacts.php?contact_id=" + contactId;
            }
            
            // Delete contact button
            function doDelete(contactId) {
                if (confirm("Are you sure you want to delete this contact?")) {
                    let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
                    urlRequest.searchParams.append('req_type', 'delete');
                    urlRequest.searchParams.append('user_id', sessionStorage.getItem("userID")); //Replace with actual user ID
                    urlRequest.searchParams.append('contact_id', contactId);
                    
                    console.log("URL req: ", urlRequest.toString());//Debugging
                    
                    fetch(urlRequest, {
                        headers: {
                            "Content-Type": "application/json",
                        },
                        method: 'POST',
                    })
                    .then(async (response) => {
                        let data = await response.json();
                        console.log("Response data: ", data);//Debbuging
                        
                        if (data.success) {
                            $("#" + contactId + "ID").remove();
                        } else {
                            alert("Failed to delete contact.");
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert("An error occurred while deleting the contact.");
                    });
                }
            }

      </script>
</div>

</body>
</html>
