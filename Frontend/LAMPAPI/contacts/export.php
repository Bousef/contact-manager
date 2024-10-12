<?php
  ob_start();
  read_contacts_for_user($json_decoded['user_id'], $json_decoded['search_string'], $json_decoded['limit'], $json_decoded['offset']);
  $contacts = json_decode(ob_get_clean(), true);
  $vcf_file = "";

  //foreach($contacts.result as $contact) {
    
    $vcf_file .= "BEGIN:VCARD\n";
    $vcf_file .= "VERSION:3.0\n";


    
    $vcf_file .= "END:VCARD\n";
    $vcf_file .= "\n";
  //}

  ob_end_clean();
  // Send to user.
  header('Content-Type: text/vcard');
  header('Content-Disposition: attachment; filename="contacts.vcf"');
  echo $vcf_file;
?>
