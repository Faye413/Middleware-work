<?php
require_once( '../common/includes/common_header.php' );

$client_list  = get_client_array();

if( !is_array( $client_list ) )
{
    echo 'Database Error';
    exit;
}

if( is_null( $client_list ) )
{
    echo 'No Data';
    exit;
}
?>

<!DOCTYPE html>
<html xmlns = 'http://www.w3.org/1999/xhtml' >
    <head>
        <?php includes( '../common/js/client/client_list.js' ); ?>
    </head>
    <body>
        <?php insert_header(); ?>

        <h1> Clients </h1>

        <table border = "1" style = "width:300px">
        <tr>
               <th> Client        </th>
               <th> Name          </th>
               <th> Address One   </th>
               <th> Address Two   </th>
               <th> City          </th>
               <th> Region        </th>
               <th> Zip Postal    </th>
               <th> Created       </th>
               <th> Modified      </th>
               <th> Delete Client </th>
        </tr>
        <? foreach ( $client_list as $client ): ?>
        <tr>
               <td> <?= $client['client']                              ?> </td>
               <td> <?= $client['name']                                ?> </td>
               <td> <?= $client['address_line_one']                    ?> </td>
               <td> <?= $client['address_line_two']                    ?> </td>
               <td> <?= $client['city']                                ?> </td>
               <td> <?= $client['region']                              ?> </td>
               <td> <?= $client['zip_postal']                          ?> </td>
               <td> <?= format_ui_date_and_time( $client['created'] )  ?> </td>
               <td> <?= format_ui_date_and_time( $client['modified'] ) ?> </td>
               <td> <a href = 'http://training.faye.neadwerx.com/client/modify_client.php?pk_client_out=<? echo $client['client']; ?>'>Modify</a> </td>
               <td> <a href = 'http://training.faye.neadwerx.com/client/delete_client.php?pk_client_out=<? echo $client['client']; ?>'>Delete</a> </td>

        </tr>
        <? endforeach; ?>
        </table>

        <a href = "http://training.faye.neadwerx.com/client/new_client.php">Add new client</a>

        <?php insert_footer(); ?>
    </body>
</html>
