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
  <link href="../styles/card.css" rel="stylesheet">

  <body>
  <div class = 'cardGrid'>
    <?php
    //For each example contact, reference the invidivuals components and inject the php card with those vars
    for($i = 0; $i < 4; $i++ ){      
        $name = $namearr[$i];
        $number = $numberarr[$i];
        $email = $emailarr[$i];
        $company = $companyarr[$i];
        include '../contactCard.php'; 
    }
    ?>
</div>
</body>
</html>
