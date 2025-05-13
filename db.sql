create database gestioncommande;
use gestioncommande;
create table client(idclient int primary key,
                    nom varchar(20),
                    ville varchar(30),
                    telephone varchar(20))engine=innodb default charset=utf8;
create table commande(idcommande int primary key,
                            idclient int,
                            date timestamp default current_timestamp,
                            constraint fk_idclient foreign key(idclient)
                            references client(idclient)
                            on delete cascade 
                            on update cascade)engine=innodb default charset=utf8;
create table lignecommande(idarticle int primary key,
                            idcommande int,
                            quantite int,
                            constraint fk_idarticle foreign key(idcommande)
                            references commande(idcommande)
                            on delete cascade
                            on update cascade)engine=innodb default charset=utf8;
create table article(idarticle int primary key,
                    description varchar(250),
                    prix_unitaire decimal(12,0))engine=innodb default charset=utf8;
