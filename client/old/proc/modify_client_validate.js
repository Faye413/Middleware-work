function validate_form()
{
    var name             = document.forms["modify_client_form"]["name"].value;
    var address_line_one = document.forms["modify_client_form"]["address_line_one"].value;
    var city             = document.forms["modify_client_form"]["city"].value;
    var region           = document.forms["modify_client_form"]["region"].value;
    var zip_postal       = document.forms["modify_client_form"]["zip_postal"].value;

    if( is_missing_or_empty( name ) == true )
    {
        alert( "Name is requred." );
        document.getElementById( "name" ).focus();
        return false;
    }

    if( is_missing_or_empty( address_line_one ) == true )
    {
        alert( "Address is requred." );
        document.getElementById( "address_line_one" ).focus();
        return false;
    }

    if( is_missing_or_empty( city ) == true )
    {
        alert( "City is requred." );
        document.getElementById( "city" ).focus();
        return false;
    }

    if( is_missing_or_empty( region ) == true )
    {
        alert( "Region is requred." );
        document.getElementById( "region" ).focus();
        return false;
    }

    if( is_missing_or_empty( zip_postal ) == true )
    {
        alert( "Zip postal is requred." );
         document.getElementById( "zip_postal" ).focus();
        return false;
    }

    if( !( /^[0-9]{5}([- ]?[0-9]{4})?$/.test( zip_postal ) ) )
    {
        alert( "Invalid zip postal" );
        document.getElementById( "zip_postal" ).focus();
        return false;
    }

    return true;
}
