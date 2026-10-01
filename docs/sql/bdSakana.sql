drop database bdsakana;

create database bdSakana;
use bdSakana;


create table LoginUser(
	idUser int(11) auto_increment primary key,
    nomeUser varchar(30) not null,
    email varchar(50) unique not null,
    senha varchar(255) not null,
    fotoPerfil varchar(255) null,
    nivelAcesso enum('gerente', 'garcom', 'cozinha') not null default 'gerente'
);

create table imagem(
	nomeImg varchar (100) not null,
    endpasta varchar (255) not null
);

create table cargo (
	idCargo int(11) auto_increment primary key,
    nomeCargo varchar(20) not null,
    salario decimal (7,2) not null
);

create table Funcionario (
    idFuncionario int(11) auto_increment primary key,
    nomeFunc varchar(100) not null,
    cpf char(11) unique not null,
    endereco varchar(255) not null,
    telefone char(11) unique null,
    dataCadastro timestamp default current_timestamp,
    idUser int(11) not null unique,
    constraint fkFunc_user foreign key (idUser) references LoginUser(idUser),
    idCargo int(11) not null,
    constraint fkFunc_cargo foreign key (idCargo) references cargo(idCargo),
    idDepartamento int(11) null
);

create table categoria(
    idCategoria int (11) auto_increment primary key,
    nomeCategoria varchar(40) not null unique,
    descCategoria varchar(255) not null unique,
    imgCategoria varchar (255) unique
);

create table produto (
    idProduto int (11) auto_increment primary key,
    idCategoria int (11) not null,
    nomeProduto varchar(40) not null unique,
	  descProduto varchar (255) unique not null,
	  imgProduto varchar (255) unique not null,
    valorProduto decimal (7,2) not null,
    constraint fkProd_Cat foreign key (idCategoria) references categoria(idCategoria)
);

create table mesa (
    idmesa int auto_increment primary key,
    numeromesa int not null unique,
    lugares int not null,
    status enum('Disponivel', 'Indisponivel') not null default 'Disponivel'
);

create table pedido (
    idPedido  int (11) auto_increment primary key,
    idProduto int (11) not null,
    constraint fkPed_Prod foreign key (idProduto) references produto(idProduto),
    idMesa int (11) not null,
    constraint fkPed_Mesa foreign key (idMesa) references mesa(idMesa),
    Quantidade int(5) not null,
    Valor decimal (7,2) not null
);



insert into cargo (nomeCargo, salario) values
('Gerente', 0.00),
('Garçom', 0.00),
('Cozinha', 0.00);

select * from imagem;
select * from LoginUser;
select * from Funcionario;
select * from categoria;
select * from produto;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE categoria;
truncate table produto;

SET FOREIGN_KEY_CHECKS = 1;
