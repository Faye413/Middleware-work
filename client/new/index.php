<?php
require_once( '../common/includes/common_header.php' );
?>
<!DOCTYPE html>
<html xmlns = 'http://www.w3.org/1999/xhtml'>
    <head>
        <?php includes( '../client/js/index.js' ); ?>
        <?php includes( '../client/js/create_client.js' ); ?>
        <?php includes( '../client/js/update_client.js' ); ?>
    </head>

    <body>
        <h1> Clients </h1>
        <form method = "link" action = "/contact/index_new.php">
        <input type = "submit" value = "View Contacts">
        </form>
        <table id = "client_table" border = "1" style = "width:100%" >
            <thead>
            <tr>
                 <th> Client           </th>
                 <th> Name             </th>
                 <th> Address line one </th>
                 <th> Address line two </th>
                 <th> City             </th>
                 <th> Region           </th>
                 <th> Zip postal       </th>
                 <th> Created          </th>
                 <th> Modified         </th>
                 <th> Actions          </th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <button type = "button" onclick = "show_add_client_modal();"> Add Client </button>
    </body>
</html>
