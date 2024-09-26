<div class = "optionsWrapper" id = "optionsWrapperID" style = "display: none; justify-content: center;">
  <div class = "overlay" id = "overlayID" onclick = "closeOptions()"></div>
  
  <div class = "options" id = "optionsID">
    
    <div class = "optionsChildDiv optionsHeader">
      <div style = "width: 46px;"></div>
      <div><h1 style = "margin: 0px;">Options</h1></div>
      <button type="submit" class="profileBtn closeOptionsBtn" onclick = "closeOptions()" title="Close" aria-label="Close Options Button">
        <i class="fa-solid fa-xmark icon profileIcon"></i>
      </button>
    </div>
    
    <div class = "optionsChildDiv logoutOption">
      <h3>Logout</h3>
      <button type="submit" class="profileBtn logoutBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
        <i class="fa-solid fa-door-open icon profileIcon"></i>
      </button>
    </div>
    
    <div class="optionsChildDiv importContacts">
      <h3>Import Contacts</h3>
      <form id="importForm" style="display: block;">
        <div>
          <input type="file" id="fileInput" accept=".csv,.vcf" required>
        </div>
        <div>
          <button type="submit" class="profileBtn importBtn" title="Import" aria-label="Import Button">
            <i class="fa-solid fa-file-import icon profileIcon"></i>
          </button>
        </div>
      </form>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
      <script src="/js/import.js"></script>
    </div>
    
    <div class = "optionsChildDiv exportContacts">
      <h3>Export</h3>
      <button type="submit" class="profileBtn exportBtn" onclick="doLogout()" title="Logout" aria-label="Logout Button">
        <i class="fa-solid fa-file-export icon profileIcon"></i>
      </button>
    </div>
    
    <div class = "optionsChildDiv deleteOption">
      <h3>Delete Account</h3>
      <button type="submit" class="profileBtn deleteBtn" onclick="doDeleteAccount()" title="Delete Account" aria-label="Delete Account Button">
        <i class="fa-solid fa-trash icon profileIcon"></i>
      </button>
    </div>
  </div>
  
  <script>
    function doLogout() {
      sessionStorage.clear();
      window.location.href = "https://jo531962ucf.xyz";
    }
  
    function doDeleteAccount() {
      if(confirm("Permanently Delete User?")) {
        alert(sessionStorage.getItem("userID"));
        
        let urlRequest = new URL("https://jo531962ucf.xyz/LAMPAPI/users/users.php")
        urlRequest.searchParams.append('req_type', 'delete');
        urlRequest.searchParams.append('user_id', sessionStorage.getItem("userID"));

        fetch(urlRequest, {
          headers: {
            "Content-Type": "application/json"
          },
          method: 'GET',
        })
        .then(async(response) => {
          data = await response.json();
          console.log(data);
          window.location.href = "https://jo531962ucf.xyz/";
        })
      }
    }
  </script>
</div>
