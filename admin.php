<?php

if ( isset($_POST['app_id']) ){
  update_option( 'figgins_client_secret', $_POST['app_id'], false );
  echo '<br><p style="color:green;font-weight:800;">Application ID set, thank you!</p>';
}

$secret = get_option( 'figgins_client_secret' );
echo '<br>';
echo '<form method="post">';
echo 'Application ID: ';

if ($secret) {
  echo '<input type="text" value="' . $secret . '" name="app_id">';
} else {
  echo '<input type="text" name="app_id">';
}

echo '<input type="submit" value="Submit">';
echo '</form>';
