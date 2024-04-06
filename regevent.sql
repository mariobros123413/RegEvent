
create table usuario(
	id serial primary key,
	correo varchar,
	contrasena varchar,
	nombre varchar(30)
);


create table asistencia(
	id serial primary key,
    id_invitacion int,
	fecha_llegada varchar(20)
);

ALTER TABLE asistencia
ADD COLUMN id_evento INT;
-- También puedes agregar la restricción de la clave foránea en este paso si lo deseas
ALTER TABLE asistencia
ADD CONSTRAINT id_evento FOREIGN KEY (id_evento) REFERENCES evento(id);


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
	id_evento int,
	nombre_invitado varchar,
	nro_celular int,
	foreign key(id_usuario) references usuario(id),
	foreign key(id_evento) references evento(id)
);
select * from evento
select * from asistencia
select * from invitacion
drop table mesa
delete from asistencia where id= 5
alter table invitacion drop column link_confirmacion
insert into usuario values(1, 'j.mario18@hotmail.es', 'jose', 'jose mario')
insert into invitacion values(2,1,3,'José Jimenez', 75615676)
insert into asistencia values(2,'2024-03-30 19:05',2, 3)