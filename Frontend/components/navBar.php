<div class="navBarWrapper">
  <h1 class="siteHeader">
    Contact Manager
  </h1>
  <div class = "navButtons">
    <button type="submit" id = "buttonID" class="addContactBtn" onclick="window.location.href='https://jo531962ucf.xyz/contactsPage.php';" title="Add Contact" aria-label="Add Contact">
      <i class="fa-solid fa-plus icon plusIcon"></i>
    </button>
    <button type="submit" class="profileBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
      <i class="fa-solid fa-door-open icon profileIcon"></i>
    </button>
  </div>
  <script>
    function flipButton() { // For flipping add contact button to home when not at home.
      if(window.location.pathranme == "/addContacts.php") {
        const buttonID = document.getElementById("buttonID");
        buttonID.innerHTML = "<i class='fa-solid fa-house icon profileIcon'></i>";
      }
    }
    
    function doLogout() {
        sessionStorage.clear();
        window.location.href = "https://jo531962ucf.xyz";
    }
  </script>
</div>
