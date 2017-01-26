<?php
require_once( '../common/includes/common_header.php' );

$pk_client = $_REQUEST['pk_client_out'];

if( !is_number_positive( $_REQUEST, 'pk_client_out' ) )
{
    echo 'Invalid client.';
    exit;
}

if( !is_null( delete_client( $pk_client ) ) )
{
    echo 'Failed to delete client.';
    exit;
}
else
{
    echo 'Successfully deleted the client.';
}

?>

<!DOCTYPE html>
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <?php includes(); ?>
    </head>
    <body>
        <?php insert_header(); ?>
        <?php insert_footer(); ?>
    </body>
</html>
