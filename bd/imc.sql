create database if not exists bdimc;
use bdimc;

create table if not exists tusuario (
nusuarioID int auto_increment,
ccedula varchar(15) unique,
primary key (nusuario)
)engine=InnoDB;

create table if not exists timc (
nimcID int auto_increment,
naltura varchar(10) not null,
npeso varchar(10) not null,
dfecha date,
primary key (nimcID)
)engine=InnoDB;

create table if not exists timc_usuario (
nimc_usuarioID int auto_increment,
nusuarioFK int not null,
nimcFK int not null,
primary key (nimc_usuarioID),
foreign key (nusuarioFK) references tusuario(nusuarioID),
foreign key (nimcFK) references timc(nimcID)
)engine=InnoDB;
