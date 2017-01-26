<?php
require_once( '../../common/includes/common_header.php' );

$map = array( 'pk_client'        => $_REQUEST['pk_client'],
              'name'             => $_REQUEST['name'],
              'address_line_one' => $_REQUEST['address_line_one'],
              'address_line_two' => $_REQUEST['address_line_two'],
              'city'             => $_REQUEST['city'],
              'region'           => $_REQUEST['region'],
              'zip_postal'       => $_REQUEST['zip_postal'],
            );

if( !is_null( update_client( $map ) ) )
{
   echo 'Failed to update client.';
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

        <p> Successfully updated client! </p>

        <?php insert_footer(); ?>
    </body>
</html>
