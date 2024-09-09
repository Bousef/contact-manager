<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
  </head>
  <link rel='stylesheet' href="../styles/pageGrid.css">
<?php
  // Define variables for test array
  $namearr = array("Joseph Smith", "Johnny Appleseed", "Oprah Winfrey", "Bill Belichek");
  $numberarr = array("772-789-0987", "561-909-6547", "765-278-3827", "278-940-3513");
  $emailarr = array("skibbidi@yahoo.com", "nfl@nba.com", "test123@google.edu", "fakeemail@com.com");
  $companyarr = array("Mr. Beast Media", "PGA Tour", "Ultimate Fighting Championship", "McDonalds");
  ?>
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
      <script>
      $(".searchSubmitBtn").click(function(){
        let nameVar = "Joseph Smith";
        let numberVar = "67890";
        let emailVar = "gary@gmail.com";
        let companyVar = "UCF Knights";

        $.ajax({
           url: '../contactCard.php',
           method: 'GET',
          success: function(response) {
          response = response.replaceAll('*CONTACT_NAME*', nameVar)
                             .replaceAll('*CONTACT_NUMBER*', numberVar)
                             .replaceAll('*CONTACT_EMAIL*', emailVar)
                             .replaceAll('*CONTACT_COMPANY*', companyVar);

          $('.cardGrid').append(response);
        }
      }
      )
    });

      </script>
</div>
</body>
</html>
