<?php
require_once( '../common/includes/common_header.php' );
?>
<!DOCTYPE html>
<html xmlns = "http://www.w3.org/1999/xhtml">
        <?php includes( '../client/js/create_client.js' ); ?>
    <head>
    </head>
    <body>
        <form>
            <p> Create New Client </p>
            <P>
                <label for  = "name"> Name* : </label><br/>
                <input type = "text" name = "name" id = "name" value = "" placeholder = "">
            </p>
            <P>
                <label for  = "address_line_one"> Address line one* : </label><br/>
                <input type = "text" name = "address_line_one" id = "address_line_one" value = "" placeholder = "">
            </p>
            <P>
                <label for  = "address_line_two"> Address line two : </label><br/>
                <input type = "text" name = "address_line_two" id = "address_line_two" value = "" placeholder = "">
            </p>
            <P>
                <label for  = "city"> City* : </label><br/>
                <input type = "text" name = "city" id = "city" value = "" placeholder = "">
            </p>
            <P>
                <label for  = "region"> Region* : </label><br/>
                <input type = "text" name = "region" id = "region" value = "" placeholder = "">
            </p>
            <P>
                <label for  = "zip_postal"> Zip Postal* : </label><br/>
                <input type = "text" name = "zip_postal" id = "zip_postal" value = "" placeholder = "">
            </p>
            <p> *Required information </p>

            <button type = "button" id = "submit" onClick = "submit_function(this.id)"> Submit </button>
            <button type = "button" id = "cancel" onClick = "cancel_function(this.id)"> Cancel </button>
        </form>
    </body>
</html>