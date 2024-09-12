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
      include '../contactCard.php';
    ?>
    <div class = 'cardGrid'>

      <script>
      $(".searchSubmitBtn").click(function(){
        let elements = document.getElementsByClassName("contactWrapper");
    
        while(elements.length > 0) {
            elements[0].parentNode.removeChild(elements[0]);
        }
        
        let emailVar = "placeholder@placeholder.com";
        let companyVar = "University of Central Florida";
        let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/contacts/contacts.php");
        let data;
        
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
            responseHTML = responseHTML.replaceAll('*CONTACT_NAME*', (contact.first_name + " " + contact.last_name))
                                       .replaceAll('*CONTACT_NUMBER*', contact.phone_number)
                                       .replaceAll('*CONTACT_EMAIL*', emailVar)
                                       .replaceAll('*CONTACT_COMPANY*', companyVar)
                                       .replaceAll('*CONTACT_ID*', contact.id)
                                       //Replace undefined fields with empty
                                       .replaceAll("undefined", "");
            $('.cardGrid').append(responseHTML);
          }
        }
        )
        });
        }
        });

        
    });

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
