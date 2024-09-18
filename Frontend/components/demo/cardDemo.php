<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Manager</title>
  </head>
  <link rel='stylesheet' href="../styles/pageGrid.css">
  <script src="https://kit.fontawesome.com/ac1c3ec324.js" crossorigin="anonymous"></script>
  <link href="../styles/card.css" rel="stylesheet">
  <link href="../styles/searchBar.css" rel="stylesheet">
  <link href="../styles/navBar.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <body>
    <?php
      include '../navBar.php';
      include '../searchBar.php';
    ?>
    <div class = 'cardGrid'>

      <script>
        $(document).ready(function() {
    $(".deleteButton").click(function() {
        let contactId = $(this).data("contact-id");
        doDelete(contactId);
    });
  });
      $(".searchSubmitBtn").click(function(){
        let elements = document.getElementsByClassName("contactWrapper");
    
        while(elements.length > 0) {
            elements[0].parentNode.removeChild(elements[0]);
        }
        
        let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
        let data, addressData, addressStr;
        
        urlRequest.searchParams.append('req_type', 'search');
        urlRequest.searchParams.append('user_id', 1);
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
          }
          else if(data.success == true){
            data.result.forEach((contact) => {
              $.ajax({
            url: '../contactCard.php',
            method: 'GET',
            success: function(responseHTML) {
            let addressRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/addresses.php");
            addressRequest.searchParams.append('req_type', 'read')
            addressRequest.searchParams.append('contact_id', contact.id.toString());
            console.log(addressRequest.toString())
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
              }
              else{
                addressStr = "" + addressData.result.address_line_01.toString() + " " + addressData.result.city.toString() + " " + addressData.result.state.toString() + ", " + addressData.result.zip_code.toString();
              }
              console.log(addressStr);
            
            responseHTML = responseHTML.replaceAll('*CONTACT_NAME*', (contact.first_name + " " + contact.last_name))
                                       .replaceAll('*CONTACT_NUMBER*', contact.phone_number)
                                       .replaceAll('*CONTACT_EMAIL*', contact.email_address)
                                       .replaceAll('*CONTACT_COMPANY*', addressStr)
                                       .replaceAll('*CONTACT_ID*', contact.id)
                                       //Replace undefined fields with empty
                                       .replaceAll("undefined", "");
            $('.cardGrid').append(responseHTML);
          })
          }
        }
        )
        });
        }
        });

        
    });

    //Jose editing from this point
    
    function doEdit(contactId) {
    window.location.href = "https://jo531962ucf.xyz/components/demo/editContacts.php?contact_id=" + contactId;

      }

      function doDelete(contactId) {
        if (confirm("Are you sure you want to delete this contact?")) {
          let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
          urlRequest.searchParams.append('req_type', 'delete');
          urlRequest.searchParams.append('user_id', 1); //Replace with actual user ID
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
              alert("Contact deleted successfully.");
              location.reload(); //Reload the page to get changes
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

    //End Jose edits

      </script>
</div>



<!--
<?php
    //For each example contact, reference the invidivuals components and inject the php card with those vars
    for($i = 0; $i < 4; $i++ ){      
        $name = $namearr[$i];
        $number = $numberarr[$i];
        $email = $emailarr[$i];
        $company = $companyarr[$i];
        include '../contactCard.php'; 
    }

    //Test output for requests
    $url = "http://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php";

    $test_Obj = [
      'req_type' => "read",
      'user_id' => "456",
      'contact_id' => "567"
    ];

    $json_encoded = json_encode($test_Obj);
    $urlReq = $url . '?' . http_build_query($test_Obj);
    
    //Using cURL to do the Browser URL query rather than json packets
    $ch = curl_init();

    //cURL config stuff
    curl_setopt($ch, CURLOPT_URL, $urlReq);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    

    //Submit request and parse the returned JSON
    $response = curl_exec($ch);
    $jsonDecoded = json_decode($response, true);

    ?>
    -->

</body>
</html>
