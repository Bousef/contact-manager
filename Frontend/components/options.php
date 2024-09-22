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
        
    <div class="importContacts">
    	<form id="importForm">
    		<input type="file" id="fileInput" accept=".csv,.vcf" required>
    		<button type="submit">Parse Input</button>
    	</form>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    	<script src="/js/import.js"></script>
    </div>

    <div class = "exportContacts">
      <p>Export</p>
      <button type="submit" class="profileBtn logoutBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
        <i class="fa-solid fa-gear icon profileIcon"></i>
      </button>
    </div>

    <div class = "deleteOption">
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
