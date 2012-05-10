<?php

function getAdresses(){
  global $db;
  $data = array();
  $sql = 'SELECT address_book_id, customers_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state,	entry_country_id, entry_zone_id FROM '
    . DB_DATABASE . '.' . TABLE_ADDRESS_BOOK;
  $result = $db->Execute($sql);
  
  if ($result->RecordCount() > 0) {
    while (!$result->EOF) {
      array_push($data, array_slice($result->fields, 0));
      $result->MoveNext();	
    }
  }
  return $data;
}

function display_addresses($data){
	echo '<div id="div_id_value">' . "\n";
	echo '<form name="address_form" action="' . zen_href_link(FILENAME_PRINTLABEL, 'NONSSL') . '" method="get">' . "\n";
	echo '<table border=0><tr><td>' . "\n";
	echo LABEL_SELECTOR_NAME . '</td><td><select name="addressBookID">' . "\n";
	for($i=0; $i<sizeof($data); $i++){
		echo '<option value="' . 
		$data[$i]['address_book_id']. '" >' . 
		$data[$i]['entry_company'] . ' - ' . 
		$data[$i]['entry_firstname'] . ' ' .
		$data[$i]['entry_lastname'] . ' - ' .
		$data[$i]['entry_street_address'] . ' - ' .
		$data[$i]['entry_suburb'] . ' - ' .
		$data[$i]['entry_postcode'] . ' - ' .
		$data[$i]['entry_city'] . ' - ' .
		$data[$i]['entry_state'] . '</option>' . "\n";
	}
	echo '</select>' . "\n";
	echo'</td><td><input type="submit" value="Submit" />' . "\n";
	echo '</td></tr></table>' . "\n";
	echo '</form></div>' . "\n";
}

function printLabel($adressBookID){
  global $db;
  global $messageStack;
  $sql = null;
  $result = null;
  $address = null;
  $country = null;
  $filename = null;
  $file = null;
  $zpl = array();
  $separator1 = '';
  $separator2 = '';
  
  $sql = 'SELECT address_book_id, customers_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id FROM '
    . DB_DATABASE . '.' . TABLE_ADDRESS_BOOK . ' WHERE address_book_id = ' . $adressBookID;
  $result = $db->Execute($sql);
  $address = array_slice($result->fields, 0);
  
  $sql = 'SELECT * FROM ' . DB_DATABASE . '.' . TABLE_COUNTRIES . ' WHERE countries_id = "' . $address['entry_country_id'] . '"';
  $result = $db->Execute($sql);
  $country = $result->fields['countries_name'];
  
  if($address['entry_company'] != ''){
  	$separator1 = ', ';
  }
  if ($address['entry_suburb'] != ''){
  	$separator2 = ', ';
  }
 
  //configure layout
  array_push($zpl, '^XA');
  array_push($zpl, '^DFR:SAMPLE.GRF^FS');
  array_push($zpl, '^FO50,50^GB750,400,4,B^FS');
  
  array_push($zpl, '^FO150,75^ADN,18,10^FN1^FS (Receiver label)');
  array_push($zpl, '^FO150,125^ADN,36,20^FN2^FS (Name)');
  array_push($zpl, '^FO150,175^ADN,36,20^FN3^FS (Address)');
  array_push($zpl, '^FO150,225^ADN,36,20^FN4^FS (Zip, city)');
  array_push($zpl, '^FO150,275^ADN,36,20^FN5^FS (Country)');
  
  
  array_push($zpl, '^FO50,50^GB750,800,4,B,^FS');
  array_push($zpl, '^FO50,450^GD750,400,4,,R^FS');
  array_push($zpl, '^FO50,450^GD750,400,4,,L^FS');
  array_push($zpl, '^FO150,475^ADN,18,10^FN11^FS (Sender label)');
  array_push($zpl, '^FO150,525^ADN,36,20^FN12^FS (Name)');
  array_push($zpl, '^FO150,575^ADN,36,20^FN13^FS (Address)');
  array_push($zpl, '^FO150,625^ADN,36,20^FN14^FS (Zip, city)');
  array_push($zpl, '^FO150,675^ADN,36,20^FN15^FS (Country)');
  
  array_push($zpl, '^FO225,950^GC400,10,^FS');
  array_push($zpl, '^FO350,1050^GC20,30,^FS');
  array_push($zpl, '^FO500,1050^GC20,30,^FS');
  array_push($zpl, '^FO325,1200^GD50,50,10,B,L^FS');
  array_push($zpl, '^FO380,1245^GB100,0,5,B,^FS');
  array_push($zpl, '^FO475,1200^GD50,50,10,B,R^FS');
  array_push($zpl, '^XZ');
  
  
  //put in data
  array_push($zpl, '^XA');
  array_push($zpl, '^XFR:SAMPLE.GRF');
  array_push($zpl, '^FN1^FD' . LABEL_FN1 . '^FS'); 
  array_push($zpl, '^FN2^FD' .
    $address['entry_company'] .
    $separator1 . 
    $address['entry_firstname'] . ' ' . 
    $address['entry_lastname'] . '^FS');
  array_push($zpl, '^FN3^FD' . $address['entry_street_address'] .
  	$separator2 .
  	$address['entry_suburb'] . '^FS');
  array_push($zpl, '^FN4^FD' . $address['entry_postcode'] . ' ' . $address['entry_city'] . '^FS');
  array_push($zpl, '^FN5^FD' . $country . '^FS');
  array_push($zpl, '^FN11^FD' . LABEL_FN11 . '^FS');
  array_push($zpl, '^FN12^FD' . LABEL_FN12 . '^FS');
  array_push($zpl, '^FN13^FD' . LABEL_FN13 . '^FS');
  array_push($zpl, '^FN14^FD' . LABEL_FN14 . '^FS');
  array_push($zpl, '^FN15^FD' . LABEL_FN15 . '^FS');
  
  array_push($zpl, '^XZ');
  

  
  
  
  
  //TODO: write to file
  $filename = LABEL_PRINT_FOLDER . $adressBookID . '.zpl';
  
  if($file = fopen($filename, 'x+')){
  	for($i=0;$i<sizeof($zpl);$i++){
  		//echo $zpl[$i] . '<br />';
  		fwrite($file, $zpl[$i] . "\n");
  	}
  	fclose($file);
  	$messageStack->add(LABEL_PRINTED_MESSAGE . $adressBookID, 'success');
  	
  }else{
  	$messageStack->add(LABEL_NOT_PRINTED_MESSAGE . ' ' . $adressBookID, 'warning');
  }

}
?>