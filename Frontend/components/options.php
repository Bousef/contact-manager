<div class = "optionsWrapper" id = "optionsWrapperID">
  <div class = "overlay" id = "overlayID" onclick = "closeOptions()"></div>

  <div class = "options" id = "optionsID">
    <h1>popup test</h1>
  </div>

<!--
  <h1 class="siteHeader">
    Contact Manager
  </h1>
  <div class = "navButtons">
    <button type="submit" id = "buttonID" class="profileBtn addContactBtn" onclick="window.location.href='https://jo531962ucf.xyz/addContacts.php';" title="Add Contact" aria-label="Add Contact">
      <i class="fa-solid fa-plus icon plusIcon"></i>
    </button>
    <button type="submit" class="profileBtn logoutBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
      <i class="fa-solid fa-gear icon profileIcon"></i>
    </button>
  </div>
-->

  <script>
    function openOptions() {
      document.getElementById("optionsWrapperID").style.display = "block";
      //document.getElementById("overlayID").style.display = "block";
      //document.getElementById("optionsID").style.display = "block";
    }

    function closeOptions() {
      document.getElementById("optionsWrapperID").style.display = "none";
      //document.getElementById("overlayID").style.display = "none";
      //document.getElementById("optionsID").style.display = "none";
    }
  </script>
</div>
