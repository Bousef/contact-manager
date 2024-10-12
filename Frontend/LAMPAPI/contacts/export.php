<?php
  ob_start();
  read_contacts_for_user($json_decoded['user_id'], $json_decoded['search_string'], $json_decoded['limit'], $json_decoded['offset']);
  $contacts = json_decode(ob_get_clean(), true);

  // Find address
  foreach($contacts["result"] as $contact) {
    require_once 'addresses/read_address_for_contact.php';
    read_address_for_contact($contact['id']);
    $address = json_decode(ob_get_clean(), true);
    if($address['success'] != true) {
      $contact['address'] = false;
    } else {
      $contact['address'] = $address['result'];
    }
  }

  $vcf_file = "";
  foreach($contacts["result"] as $contact) {
    
    $vcf_file .= "BEGIN:VCARD\n";
    $vcf_file .= "VERSION:3.0\n";

    if($contact['last_name'] || $contact['first_name']) $vcf_file .= "N:{$contact['last_name']};{$contact['first_name']};;;\n";
    if($contact['last_name'] || $contact['first_name']) $vcf_file .= "FN:{$contact['first_name']} {$contact['last_name']}\n";
    if($contact['phone_number']) $vcf_file .= "TEL;CELL:{$contact['phone_number']}\n";
    if($contact['email_address']) $vcf_file .= "EMAIL;HOME:{$contact['email_address']}\n";
    if($contact['address']) $vcf_file .= "ADR;TYPE=HOME:;{$contact['address']['address_line_02']};{$contact['address']['address_line_01']};{$contact['address']['city']};{$contact['address']['state']};{$contact['address']['zip_code']}\n";
    
    $vcf_file .= "END:VCARD\n";
    $vcf_file .= "\n";
  }

  ob_end_clean();
  // Send to user.
  header('Content-Type: text/vcard');
  header('Content-Disposition: attachment; filename="contacts.vcf"');
  echo $vcf_file;
?>
