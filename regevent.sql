
create table usuario(
	id serial primary key,
	correo varchar,
	contrasena varchar,
	nombre varchar(30)
);


select * from evento
create table asistencia(
	id serial primary key,
    id_invitacion int,
	fecha_llegada varchar(20)
);

ALTER TABLE asistencia
ADD COLUMN id_invitacion INT;
-- También puedes agregar la restricción de la clave foránea en este paso si lo deseas
ALTER TABLE asistencia
ADD CONSTRAINT id_invitacion FOREIGN KEY (id_invitacion) REFERENCES invitacion(id);


create table mesa(
	id serial primary key,
	id_evento int,
	tipo varchar(20),
	capacidad smallint,
	foreign key(id_evento) references evento(id)
	ON UPDATE CASCADE
	ON DELETE CASCADE
);

create table silla(
	id serial primary key,
	id_mesa int,
	nro smallint,
	foreign key(id_mesa) references mesa(id)
	ON UPDATE CASCADE
	ON DELETE CASCADE
);

create table evento(
	id serial primary key,
	titulo varchar(30),
	direccion varchar(40),
	descripcion varchar,
	fecha varchar(30),
	id_usuario int,
	foreign key(id_usuario) references usuario(id)
);

create table invitacion(
	id serial primary key,
	id_usuario int,
	id_mesa int,
	id_silla int,
	id_evento int,
	codigo_qr varchar,
	link_confirmacion varchar,
	nombre_invitado varchar,
	nro_celular int,
	imagen varchar,
	foreign key(id_usuario) references usuario(id),
	foreign key(id_mesa) references mesa(id),
	foreign key(id_silla) references silla(id),
	foreign key(id_evento) references evento(id)
);

insert into usuario values(1, 'j.mario18@hotmail.es', 'jose', 'jose mario')