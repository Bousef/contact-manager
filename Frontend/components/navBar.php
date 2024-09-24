<div class="navBarWrapper">
  <h1 class="siteHeader">
    Contact Manager
  </h1>
  <div class = "navButtons">
    <button type="submit" id = "buttonID" class="profileBtn addContactBtn" onclick="window.location.href='https://jo531962ucf.xyz/addContacts.php';" title="Add Contact" aria-label="Add Contact">
      <i class="fa-solid fa-plus icon plusIcon"></i>
    </button>
    <button type="submit" class="profileBtn optionsBtn" onclick="openOptions()" title="Options" aria-label="Options Button">
      <i class="fa-solid fa-gear icon profileIcon"></i>
    </button>
  </div>
  <script>
    function flipButton() { // For flipping add contact button to home when not at home.
      if(window.location.pathname !== "/contactsPage.php") {
        const buttonID = document.getElementById("buttonID");
        buttonID.onclick = function() { window.location.href = "https://jo531962ucf.xyz/contactsPage.php";};
        buttonID.innerHTML = "<i class='fa-solid fa-house icon profileIcon'></i>";
        buttonID.setAttribute("title", "Home");
        buttonID.setAttribute("aria-label", "Home");
        buttonID.classList.remove("addContactBtn");
        buttonID.classList.add("homeBtn");
      }
    }
    window.onload = flipButton();
    
    function openOptions() {
      document.getElementById("optionsWrapperID").style.display = "flex";
    }

    function closeOptions() {
      document.getElementById("optionsWrapperID").style.display = "none";
    }
  </script>
</div>

<!-- Link options page here so it always come with the navbar -->
<link href="./components/styles/options.css" rel="stylesheet">
<?php
  include './components/options.php';
?>
