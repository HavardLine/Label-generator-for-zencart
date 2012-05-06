<?php
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

if (function_exists('zen_register_admin_page')) {
  if (!zen_page_key_exists('label_generator')) {
    zen_register_admin_page('label_generator', 'BOX_SERVICE_PRINT_LABEL', 'FILENAME_PRINTLABEL', '', 'tools', 'Y', 17);
  } 
}
?>