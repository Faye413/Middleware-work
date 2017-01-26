create or replace function fn_new_client
(
    in_name             varchar,
    in_address_line_one varchar,
    in_address_line_two varchar,
    in_city             varchar,
    in_region           varchar,
    in_zip_postal       varchar
)
returns integer as
 $_$
declare
    my_new_client       integer;
begin

    my_new_client := nextval('sq_pk_client');

    insert into tb_client
    (
        client,
        name,
        address_line_one,
        address_line_two,
        city,
        region,
        zip_postal
    )
    values
    (
        my_new_client,
        in_name,
        in_address_line_one,
        in_address_line_two,
        in_city,
        in_region,
        in_zip_postal
    );

    return my_new_client;

end;
 $_$
    language plpgsql;


create or replace function fn_new_contact
(
    in_first_name        varchar,
    in_last_name         varchar,
    in_phone_primary     varchar,
    in_extension_primary varchar,
    in_email_address     varchar,
    in_client            integer
)
returns integer as
 $_$
declare
    my_new_contact       integer;
begin

    my_new_contact := nextval('sq_pk_contact');

    insert into tb_contact
    (
        contact,
        first_name,
        last_name,
        phone_primary,
        extension_primary,
        email_address,
        client
    )
    values
    (
        my_new_contact,
        in_first_name,
        in_last_name,
        in_phone_primary,
        in_extension_primary,
        in_email_address,
        in_client
     );

     return my_new_contact;

end;
 $_$
    language plpgsql;