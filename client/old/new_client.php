<?php
require_once( '../common/includes/common_header.php' );
?>

<!DOCTYPE html>
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <?php includes( '../client/js/new_client_validate.js' ); ?>
    </head>
    <body>
    <h1> Create New Client </h1>

    <form name = "new_client_form" action = "proc/new_client.php" method = "post" onsubmit = "return validate_form()">
        Name *:              <input type = "text" name = "name">             <br>
        Address Line One *:  <input type = "text" name = "address_line_one"> <br>
        Address Line Two :   <input type = "text" name = "address_line_two"> <br>
        City *:              <input type = "text" name = "city">             <br>
        Region *:            <input type = "text" name = "region">           <br>
        Zip Postal *:        <input type = "text" name = "zip_postal">       <br>
        <p>* Required Information </p>
        <input type = "submit">
    </form>

        <?php insert_footer(); ?>
    </body>
</html>
