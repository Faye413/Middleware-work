insert into tb_client (name, address_line_one, address_line_two, city, region, zip_postal)
   values ('Example Client 1', '1234 Main St', 'Apt 123', 'Atlanta', 'GA', '30082'),
          ('Example Client 2', '1234 Main St', 'Apt 123', 'Atlanta', 'GA', '30082'),
          ('Example Client 3', '1234 Main St', 'Apt 123', 'Atlanta', 'GA', '30082');

insert into tb_contact (client, first_name, last_name, phone_primary, email_address)
   values ( 1, 'Kirk', 'Bauer', '404-123-4567', 'kirk@neadwerx.com' ),
          ( 2, 'Kirk', 'Bauer', '404-123-4567', 'kirk@neadwerx.com' ),
          ( 3, 'Kirk', 'Bauer', '404-123-4567', 'kirk@neadwerx.com' );
