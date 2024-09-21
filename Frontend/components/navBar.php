<div class="navBarWrapper">
  <h1 class="siteHeader">
    Contact Manager
  </h1>
  <div class = "navButtons">
    <button type="submit" class="addContactBtn" onclick="window.location.href='https://jo531962ucf.xyz/contactsPage.php';" title="Home" aria-label="Home Button">
      <i class="fa-solid fa-house icon profileIcon"></i>
    </button>
    <button type="submit" class="profileBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
      <i class="fa-solid fa-door-open icon profileIcon"></i>
    </button>
  </div>
  <script>
    
    
    
    function doLogout() {
        sessionStorage.clear();
        window.location.href = "https://jo531962ucf.xyz";
    }
  </script>
</div>
