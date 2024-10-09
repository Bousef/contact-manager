<?php
  $contacts = read_contacts_for_user($json_decoded['user_id'], $json_decoded['search_string'], $json_decoded['limit'], $json_decoded['offset']);
  $vcf_file = "";

  foreach($contacts.result as $contact) {
    //$address = 
    
    $vcf_file .= "BEGIN:VCARD\n";
    $vcf_file .= "VERSION:3.0\n";


    
    $vcf_file .= "END:VCARD\n";
    $vcf_file .= "\n";
  }

  // Send to user.
  header('Content-Type: text/vcard');
  header('Content-Disposition: attachment; filename="contacts.vcf"');
  echo $vcf_file;
?>
