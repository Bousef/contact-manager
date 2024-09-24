<div class="contactWrapper" id="*CONTACT_ID*ID">
  <div class = "contactInfo">
    <h2 class="name">*CONTACT_NAME*</h2>
    <p class="number">*CONTACT_NUMBER*</p>
    <p class="email">*CONTACT_EMAIL*</p>
    <h4 class="company">*CONTACT_COMPANY*</h4>
  </div>
  <div class="buttonGroup">
    <div class="contactGroup">
  <a href="mailto:*CONTACT_EMAIL*" class="contactBtn" id = "emailBtn">
    <i class="fa-solid fa-envelope icon"></i>
  </a>
  <a href="tel:++1*CONTACT_NUMBER*" class="contactBtn" id = "callBtn">
    <i class="fa-solid fa-phone icon"></i>
  </a>
  <a href="sms:++1*CONTACT_NUMBER*" class="contactBtn" id = "textBtn">
    <i class="fa-solid fa-message icon"></i>
  </a>
  <a href="geo:0,0?q=*CONTACT_COMPANY*" class="contactBtn" id = "mapBtn">
    <i class="fa-solid fa-map icon"></i>
  </a>
    </div>
    <div class="modifyGroup">
      <button class="contactBtn editButton" onclick="doEdit(*CONTACT_ID*)">
        <i class="fa-solid fa-pencil icon"></i>
      </button>
      <button class="contactBtn deleteButton" onclick="doDelete(*CONTACT_ID*)">
        <i class="fa-solid fa-trash icon"></i>
      </button>
    </div>
  </div>
</div>
