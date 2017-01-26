<?php
require_once( '../common/includes/common_header.php' );

$pk_client = $_REQUEST['pk_client'];
$client    = get_client( $pk_client );

$name             = $client['name'];
$address_line_one = $client['address_line_one'];
$address_line_two = $client['address_line_two'];
$city             = $client['city'];
$region           = $client['region'];
$zip_postal       = $client['zip_postal'];

?>
<!DOCTYPE html>
<html xmlns = "http://www.w3.org/1999/xhtml">
    <?php includes( '../client/js/update_client.js' ); ?>
    <head>
    </head>
    <body>
        <p> Update Client: </p>
        <form>
            <P>
                <input type = "hidden" name = "pk_client" id = "pk_client" value = "<?php echo $pk_client; ?>" placeholder = "">
            </p>
            <P>
                <label for  = "name"> Name* : </label><br/>
                <input type = "text" name = "name" id = "name" value = "<?php echo $name; ?>" placeholder = "">
            </p>
            <P>
                <label for  = "address_line_one"> Address line one* : </label><br/>
                <input type = "text" name = "address_line_one" id = "address_line_one" value = "<?php echo $address_line_one; ?>" placeholder = "">
            </p>
            <P>
                <label for  = "address_line_two"> Address line two : </label><br/>
                <input type = "text" name = "address_line_two" id = "address_line_two" value = "<?php echo $address_line_two; ?>" placeholder = "">
            </p>
             <P>
                <label for  = "city"> City* : </label><br/>
                <input type = "text" name = "city" id = "city" value = "<?php echo $city; ?>" placeholder = "">
            </p>
            <P>
                <label for  = "region"> Region* : </label><br/>
                <input type = "text" name = "region" id = "region" value = "<?php echo $region; ?>" placeholder = "">
            </p>
            <P>
                <label for  = "zip_postal"> Zip Postal* : </label><br/>
                <input type = "text" name = "zip_postal" id = "zip_postal" value = "<?php echo $zip_postal; ?>" placeholder = "">
            </p>
            <p> *Required information </p>

            <button type = "button" id = "submit" onClick = "submit_function_update( this.id )"> Submit </button>
            <button type = "button" id = "cancel" onClick = "cancel_function( this.id )"> Cancel </button>
        </form>
    </body>
</html>
