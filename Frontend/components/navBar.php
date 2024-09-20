<div class="navBarWrapper">
  <h1 class="siteHeader">
    Contact Manager
  </h1>
  <form action="/LAMPAPI/users/logout_user.php" method="post" style="display:inline;">
    <button type="submit" class="profileBtn" onclick="doLogout()">
      <i class="fa-solid fa-door-open icon profileIcon"></i>
    </button>
  </form>
  <script>
    function doLogout() {
        sessionStorage.clear();
        window.location.href = "https://jo531962ucf.xyz";
    }
    </script>
</div>