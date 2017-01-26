<?php
require_once( '../common/includes/common_header.php' );

$pk_client = $_REQUEST['pk_client_out'];

$client           = get_client( $pk_client );
$name             = $client['name'];
$address_line_one = $client['address_line_one'];
$address_line_two = $client['address_line_two'];
$city             = $client['city'];
$region           = $client['region'];
$zip_postal       = $client['zip_postal'];

if( !is_number_positive( $_REQUEST, 'pk_client_out' ) )
{
    echo 'Invalid client.';
    exit;
}
?>

<!DOCTYPE html>
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <?php includes( '../client/js/modify_client.js' ); ?>
    </head>
    <body>
        <?php insert_header(); ?>

        <h1> Modify Client </h1>

        <form name = "modify_client_form" action = "proc/modify_client.php" method = "post" onsubmit = "return validate_form()">
            Client :             <?php  echo $_REQUEST[ 'pk_client_out' ] ?>
                                 <input type = "hidden" id = "pk_client" name = "pk_client" value = "<?php echo $pk_client; ?>"> <br>

            Name *:              <input type = "text" id = "name" name = "name" value = "<?php echo $name; ?>"> <br>

            Address Line One *:  <input type = "text" id = "address_line_one" name = "address_line_one" value = "<?php echo $address_line_one; ?>"> <br>
            
            Address Line Two :   <input type = "text" id = "address_line_one" name = "address_line_two" value = "<?php echo $address_line_two; ?>"> <br>

            City *:              <input type = "text" id = "city" name = "city" value = "<?php echo $city; ?>"> <br>

            Region *:            <input type = "text" id = "region" name = "region" value = "<?php echo $region; ?>" <br>

            Zip Postal *:        <input type = "text" id = "zip_postal" name = "zip_postal" value = "<?php echo $zip_postal; ?>"> <br>

            <p> * Required Information </p>
            <input type = "submit">
        </form>

        <?php insert_footer(); ?>
    </body>
</html>
