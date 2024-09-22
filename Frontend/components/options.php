<div class = "optionsWrapper" id = "optionsWrapperID" style = "display: none;">
  <div class = "overlay" id = "overlayID" onclick = "closeOptions()"></div>

  <div class = "options" id = "optionsID">

    <div class = "optionsChildDiv optionsHeader">
      <h1>Options</h1>
    </div>
      
    <div class = "optionsChildDiv logoutOption">
      <h3>Logout</h3>
      <button type="submit" class="profileBtn logoutBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
        <i class="fa-solid fa-gear icon profileIcon"></i>
      </button>
    </div>
        
    <div class="optionsChildDiv importContacts">
      <h3>Import Contacts</h3>
    	<form id="importForm">
    		<input type="file" id="fileInput" accept=".csv,.vcf" required>
    		<button type="submit">Parse Input</button>
    	</form>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    	<script src="/js/import.js"></script>
    </div>

    <div class = "optionsChildDiv exportContacts">
      <h3>Export</h3>
      <button type="submit" class="profileBtn logoutBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
        <i class="fa-solid fa-gear icon profileIcon"></i>
      </button>
    </div>

    <div class = "optionsChildDiv deleteOption">
      <h3>Delete Account</h3>
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
