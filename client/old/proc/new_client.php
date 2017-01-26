<?php
require_once( '../../common/includes/common_header.php' );

$map = array( 'name'             => $_REQUEST['name'],
              'address_line_one' => $_REQUEST['address_line_one'],
              'address_line_two' => $_REQUEST['address_line_two'],
              'city'             => $_REQUEST['city'],
              'region'           => $_REQUEST['region'],
              'zip_postal'       => $_REQUEST['zip_postal'],
            );

$pk_client_out = -1;

if( !is_null( create_new_client( $map, $pk_client_out ) ) )
{
   echo 'Failed to create new client.';
   exit;
}

?>

<!DOCTYPE html>
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <?php includes(); ?>
    </head>
    <body>
        <?php insert_header(); ?>

        <p> Successfully created new client ID <?= $pk_client_out ?> </p>

        <?php insert_footer(); ?>
    </body>
</html>