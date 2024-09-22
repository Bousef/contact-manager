<div class = "optionsWrapper" id = "optionsWrapperID" style = "display: none;">
  <div class = "overlay" id = "overlayID" onclick = "closeOptions()"></div>

  <div class = "options" id = "optionsID">
    <h1>
      Options
    </h1>
    
    <div class = "logoutOption">
      <p>Logout</p>
      <button type="submit" class="profileBtn logoutBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
        <i class="fa-solid fa-gear icon profileIcon"></i>
      </button>
    </div>

    <!-- Import method -->
    <?php include 'components/import.php'; ?>

    <div class = "logoutOption">
      <p>Export</p>
      <button type="submit" class="profileBtn logoutBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
        <i class="fa-solid fa-gear icon profileIcon"></i>
      </button>
    </div>

    <div class = "logoutOption">
      <p>Delete Account</p>
      <button type="submit" class="profileBtn logoutBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
        <i class="fa-solid fa-gear icon profileIcon"></i>
      </button>
    </div>

  </div>


  <script>
    function doLogout() {
        sessionStorage.clear();
        window.location.href = "https://jo531962ucf.xyz";
    }
  </script>
</div>
