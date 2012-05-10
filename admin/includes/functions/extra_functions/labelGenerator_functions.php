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
  
  $sql = 'SELECT address_book_id, customers_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id FROM '
    . DB_DATABASE . '.' . TABLE_ADDRESS_BOOK . ' WHERE address_book_id = ' . $adressBookID;
  $result = $db->Execute($sql);
  $address = array_slice($result->fields, 0);
  
  $sql = 'SELECT * FROM ' . DB_DATABASE . '.' . TABLE_COUNTRIES . ' WHERE countries_id = "' . $address['entry_country_id'] . '"';
  $result = $db->Execute($sql);
  $country = $result->fields['countries_name'];
 
  //configure layout
  array_push($zpl, '^XA');
  array_push($zpl, '^DFR:SAMPLE.GRF^FS');
  array_push($zpl, '^FO20,30^GB750,1100,4^FS');
  array_push($zpl, '^FO20,30^GB750,200,4^FS');
  array_push($zpl, '^FO20,30^GB750,400,4^FS');
  array_push($zpl, '^FO20,30^GB750,700,4^FS');
  array_push($zpl, '^FO20,226^GB325,204,4^FS');
  array_push($zpl, '^FO30,40^ADN,36,20^FDShip to:^FS');
  array_push($zpl, '^FO30,260^ADN,18,10^FDPart number #^FS');
  array_push($zpl, '^FO360,260^ADN,18,10^FDDescription:^FS');
  array_push($zpl, '^FO30,750^ADN,36,20^FDFrom:^FS');
  array_push($zpl, '^FO150,125^ADN,36,20^FN1^FS (ship to)');
  array_push($zpl, '^FO60,330^ADN,36,20^FN2^FS(part num)');
  array_push($zpl, '^FO400,330^ADN,36,20^FN3^FS(description)');
  array_push($zpl, '^FO70,480^BY4^B3N,,200^FN4^FS(barcode)');
  array_push($zpl, '^FO150,800^ADN,36,20^FN5^FS (from)');
  array_push($zpl, '^XZ');
  //put in data
  array_push($zpl, '^XA');
  array_push($zpl, '^XFR:Sfunction printLabel(){
	
}AMPLE.GRF');
  array_push($zpl, '^FN1^FD' .
    $address['entry_company'] . ' ' . 
    $address['entry_firstname'] . ' ' . 
    $address['entry_lastname'] . '^FS');
  array_push($zpl, '^FN2^FD14042^FS');
  array_push($zpl, '^FN3^FDScrew^FS');
  array_push($zpl, '^FN4^FD12345678^FS');
  array_push($zpl, '^FN5^FDMacks Fabricating^FS');
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