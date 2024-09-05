<div class="contactWrapper">
  <h2 class="name"><?php echo $name ?></h2>
  <p class="number"><?php echo $number ?></p>
  <p class="email"><?php echo $email ?></p>
  <h4 class="company"><?php echo $company ?></h4>
  <a href="mailto:<?php echo $email ?>" class="contactBtn">
    <i class="fa-solid fa-envelope icon"></i>
  </a>
  <a href="tel:+<?php echo $number ?>" class="contactBtn">
    <i class="fa-solid fa-phone icon"></i>
  </a>
  <a href="sms:+<?php echo $number ?>" class="contactBtn">
    <i class="fa-solid fa-message icon"></i>
  </a>
</div>
