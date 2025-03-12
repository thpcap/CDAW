SET foreign_key_checks = 0;
drop table if exists USER;
create table USER
(
   USER_ID smallint not null AUTO_INCREMENT,
   USER_LOGIN varchar(25) not null,
   USER_EMAIL varchar(255) not null,
   USER_PASSWORD varchar(255),
   USER_FIRSTNAME varchar(50) not null,
   USER_LASTNAME varchar(50) not null,
   USER_ROLE smallint not null,
   primary key (USER_ID)
) DEFAULT CHARSET=utf8;