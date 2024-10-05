<?php
  //read_contacts_for_user($json_decoded['user_id'], $json_decoded['search_string'], $json_decoded['limit'], $json_decoded['offset']);
  $vcf_file = "test_work_pls";

  // Send to user.
  header('Content-Type: text/vcard');
  header('Content-Disposition: attachment; filename="contacts.vcf"');
  echo $vcf_content;
?>
