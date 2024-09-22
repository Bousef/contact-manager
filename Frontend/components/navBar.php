<div class="navBarWrapper">
  <h1 class="siteHeader">
    Contact Manager
  </h1>
  <div class = "navButtons">
    <button type="submit" id = "buttonID" class="profileBtn addContactBtn" onclick="window.location.href='https://jo531962ucf.xyz/addContacts.php';" title="Add Contact" aria-label="Add Contact">
      <i class="fa-solid fa-plus icon plusIcon"></i>
    </button>
    <button type="submit" class="profileBtn logoutBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
      <i class="fa-solid fa-cog icon profileIcon"></i>
    </button>
  </div>
  <script>
    function flipButton() { // For flipping add contact button to home when not at home.
      if(window.location.pathname == "/addContacts.php") {
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
    
    function doLogout() {
        sessionStorage.clear();
        window.location.href = "https://jo531962ucf.xyz";
    }
  </script>
</div>
