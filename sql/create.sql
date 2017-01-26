create sequence sq_pk_client;

create table tb_client
(
    client              integer primary key default nextval('sq_pk_client'),
    name                varchar(200) not null,
    address_line_one    varchar(300) not null,
    address_line_two    varchar(350),
    city                varchar(200) not null,
    region              varchar(200) not null,
    zip_postal          varchar(10) not null,
    created             timestamp not null default now(),
    modified            timestamp not null default now()
);


create sequence sq_pk_contact;

create table tb_contact
(
    contact             integer primary key default nextval('sq_pk_contact'),
    first_name          varchar(200) not null,
    last_name           varchar(200) not null,
    phone_primary       varchar(20) not null,
    extension_primary   varchar(10),
    email_address       varchar(200) not null,
    client              integer references tb_client,
    created             timestamp not null default now(),
    modified            timestamp not null default now()
);
