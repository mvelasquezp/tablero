CREATE TABLE ma_empresa (
	id_empresa           INTEGER PRIMARY KEY AUTO_INCREMENT,
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME DEFAULT NULL,
	st_vigente          VARCHAR(10) NOT NULL DEFAULT 'Vigente',
	cod_entidad          VARCHAR(15) NULL,
	CHECK (st_vigente in ('Vigente','Retirado'))
);


CREATE TABLE ma_entidad (
	cod_entidad          VARCHAR(15) NOT NULL,
	des_nombre_1         VARCHAR(50) NOT NULL,
	des_nombre_2         VARCHAR(50) NULL,
	des_nombre_3         VARCHAR(50) NULL,
	tp_documento         VARCHAR(3) NOT NULL,
	st_vigente          VARCHAR(10) NOT NULL DEFAULT 'Vigente',
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME DEFAULT NULL,
	CHECK (st_vigente in ('Vigente','Retirado'))
);
ALTER TABLE ma_entidad
ADD PRIMARY KEY (cod_entidad);


CREATE TABLE ma_menu (
	id_item              INTEGER PRIMARY KEY AUTO_INCREMENT,
	des_nombre           VARCHAR(30) NOT NULL,
	st_vigente          VARCHAR(15) NOT NULL DEFAULT 'Vigente',
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME DEFAULT NULL,
	id_ancestro          INTEGER NULL,
	des_url              VARCHAR(30) NOT NULL,
	CHECK (st_vigente in ('Vigente','Retirado'))
);


CREATE TABLE ma_usuarios (
	id_usuario           INTEGER AUTO_INCREMENT,
	des_alias            VARCHAR(30) NOT NULL,
	des_email            VARCHAR(100) NOT NULL,
	des_telefono         VARCHAR(15) NULL,
	tp_usuario           CHAR NOT NULL default 'U',
	st_vigente          VARCHAR(10) NOT NULL DEFAULT 'Vigente',
	password             VARCHAR(200) NOT NULL,
	remember_token       VARCHAR(200) NULL,
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME DEFAULT NULL,
	fe_ingreso           DATETIME NULL,
	st_verifica_mail     CHAR NOT NULL,
	fe_ultimo_acceso     DATETIME NULL,
	cod_entidad          VARCHAR(15) NULL,
	id_empresa           INTEGER NOT NULL,
	CHECK (st_vigente in ('Vigente','Retirado')),
	CHECK (tp_usuario in ('U','S','I')),
	primary key (id_usuario,id_empresa)
);


CREATE TABLE sys_permisos (
	id_item              INTEGER NOT NULL,
	st_habilitado        CHAR NOT NULL DEFAULT 'S',
	st_vigente           VARCHAR(15) NOT NULL DEFAULT 'Vigente',
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME DEFAULT NULL,
	id_usuario           INTEGER NOT NULL,
	id_empresa           INTEGER NOT NULL,
	CHECK (st_vigente in ('Vigente','Retirado')),
    PRIMARY KEY (id_usuario, id_empresa, id_item)
);

ALTER TABLE ma_empresa
ADD FOREIGN KEY R_1 (cod_entidad) REFERENCES ma_entidad (cod_entidad);

ALTER TABLE ma_usuarios
ADD FOREIGN KEY R_2 (cod_entidad) REFERENCES ma_entidad (cod_entidad);

ALTER TABLE ma_usuarios
ADD FOREIGN KEY R_3 (id_empresa) REFERENCES ma_empresa (id_empresa);

ALTER TABLE sys_permisos
ADD FOREIGN KEY R_6 (id_usuario, id_empresa) REFERENCES ma_usuarios (id_usuario, id_empresa);

ALTER TABLE sys_permisos
ADD FOREIGN KEY R_7 (id_item) REFERENCES ma_menu (id_item);



-- 02-09-2018

CREATE TABLE us_usuario_puesto (
	st_vigente           VARCHAR(10) NULL DEFAULT 'Vigente',
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME NULL,
	id_usuario           INTEGER NOT NULL,
	id_empresa           INTEGER NOT NULL,
	id_puesto            INTEGER NOT NULL,
	primary key (id_usuario, id_empresa, id_puesto)
);

CREATE TABLE ma_oficina (
	id_oficina           INTEGER NOT NULL AUTO_INCREMENT,
	des_oficina          VARCHAR(50) NOT NULL,
	st_vigente           VARCHAR(10) NOT NULL DEFAULT 'Vigente',
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME NULL,
	num_jerarquia        INTEGER NULL,
	id_encargado         INTEGER NULL,
	id_ancestro          INTEGER NULL,
	id_empresa           INTEGER NOT NULL,
	PRIMARY KEY (id_oficina, id_empresa)
);

CREATE TABLE ma_puesto (
	id_puesto            INTEGER NOT NULL AUTO_INCREMENT,
	des_puesto           VARCHAR(50) NULL,
	num_jerarquia        INTEGER NULL,
	st_vigente           VARCHAR(10) NULL DEFAULT 'Vigente',
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME NULL,
	id_empresa           INTEGER NOT NULL,
	id_oficina           INTEGER NULL,
	id_superior          INTEGER NULL,
	PRIMARY KEY (id_puesto, id_empresa)
);

ALTER TABLE us_usuario_puesto
ADD FOREIGN KEY R_9 (id_usuario, id_empresa) REFERENCES ma_usuarios (id_usuario, id_empresa);

ALTER TABLE us_usuario_puesto
ADD FOREIGN KEY R_10 (id_puesto, id_empresa) REFERENCES ma_puesto (id_puesto, id_empresa);

ALTER TABLE ma_oficina
ADD FOREIGN KEY R_12 (id_empresa) REFERENCES ma_empresa (id_empresa);

ALTER TABLE ma_puesto
ADD FOREIGN KEY R_11 (id_empresa) REFERENCES ma_empresa (id_empresa);

ALTER TABLE ma_puesto
ADD FOREIGN KEY R_13 (id_oficina, id_empresa) REFERENCES ma_oficina (id_oficina, id_empresa);


-- 08-09-2018

create table sys_tipos_dato (
	id_tipo				 int auto_increment primary key,
    des_tipo			 varchar(30),
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME NULL
);
insert into sys_tipos_dato(des_tipo) values ('Número entero');
insert into sys_tipos_dato(des_tipo) values ('Número decimal');
insert into sys_tipos_dato(des_tipo) values ('Texto');
insert into sys_tipos_dato(des_tipo) values ('Fecha');
insert into sys_tipos_dato(des_tipo) values ('Caracter');
insert into sys_tipos_dato(des_tipo) values ('Lógico');

create table ma_campos (
	id_campo			 int auto_increment,
    id_empresa			 int not null,
    id_tipo				 int not null,
    des_campo			 varchar(50) not null,
    st_vigente           VARCHAR(10) NOT NULL DEFAULT 'Vigente',
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME NULL,
    foreign key (id_tipo) references sys_tipos_dato (id_tipo),
    foreign key (id_empresa) references ma_empresa (id_empresa),
    primary key (id_campo, id_empresa)
);

create table ma_hitos_control (
	id_hito				 int auto_increment,
    id_empresa			 int not null,
    id_responsable		 int not null,
    des_hito			 varchar(50) not null,
    st_vigente          VARCHAR(10) NOT NULL DEFAULT 'Vigente',
	created_at           DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at           DATETIME NULL,
    foreign key (id_responsable, id_empresa) references ma_puesto (id_puesto, id_empresa),
    primary key (id_hito, id_empresa)
);


-- 09-09-2018

create table sys_estados (
	id_estado			int auto_increment primary key,
    cod_estado			varchar(30) not null,
    des_estado			varchar(30) not null,
    tp_estado			char not null,
	created_at          DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at          DATETIME NULL
);

create table pr_hitos_campo(
	id_hito				int not null,
    id_empresa			int not null,
    id_campo			int not null,
    id_usuario_asigna	int not null,
    st_vigente          VARCHAR(10) NOT NULL DEFAULT 'Vigente',
	created_at          DATETIME NOT NULL DEFAULT current_timestamp,
	updated_at          DATETIME NULL,
    foreign key (id_hito, id_empresa) references ma_hitos_control (id_hito, id_empresa),
    foreign key (id_campo, id_empresa) references ma_campos (id_campo, id_empresa),
    foreign key (id_usuario_asigna,id_empresa) references ma_usuarios (id_usuario,id_empresa),
    primary key (id_hito, id_empresa, id_campo)
);


-- 10-09-2018

create table pr_catalogo_proyecto(
	id_catalogo			int auto_increment,
    id_empresa			int not null,
    des_catalogo		varchar(30) not null,
    st_vigente			varchar(10) not null default 'Vigente'
);