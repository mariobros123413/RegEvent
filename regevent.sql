create table evento(
	id serial primary key,
	titulo varchar(30),
	direccion varchar(40),
	descripcion varchar,
	fecha varchar(30),	
);

create table mesa(
	id serial primary key,
	tipo varchar(20),
	capacidad smallint,
	id_evento int,
	foreign key(id_evento) references evento(id)
	ON UPDATE CASCADE
	ON DELETE CASCADE
);

create table silla(
	id serial primary key,
	nro smallint,
	id_mesa int,
	foreign key(id_mesa) references mesa(id)
	ON UPDATE CASCADE
	ON DELETE CASCADE
);

create table invitacion(
	id serial primary key,
	nombre_invitado varchar,
	nro_celular int,
	mesa_asignada int,
	id_evento int,
	foreign key(id_evento) references evento(id) 
	ON UPDATE CASCADE
	ON DELETE CASCADE,
);

create table asistencia(
	id serial primary key,
	fecha_llegada varchar(20),
	id_invitacion int,
	foreign key(id_invitacion) references invitacion(id)
	ON UPDATE CASCADE
	ON DELETE CASCADE
);
delete from mesa where id>2
SELECT COUNT(*) AS TOTAL FROM invitacion, mesa WHERE invitacion.id_evento = 2 AND invitacion.mesa_asignada = mesa.id
SELECT COUNT(*) AS TOTAL FROM invitacion WHERE id_evento = 2
SELECT COUNT(*) AS TOTAL FROM invitacion, mesa WHERE invitacion.mesa_asignada = mesa.id AND mesa.id = 2
alter table silla drop column nro
select * from evento
select * from mesa
select * from silla
select * from invitacion
select * from asistencia
insert into asistencia values(16, '2024-03-30 19:05', 137,5)
delete from mesa where id=2
alter table silla add column nro int
alter table invitacion drop column id_mesa
delete from evento where id>2
alter table asistencia drop column id_evento
insert into usuario values(1, 'j.mario18@hotmail.es', 'jose', 'jose mario')
insert into invitacion values(2,1,3,'José Jimenez', 75615676)
insert into asistencia values(2,'2024-03-30 19:05',2, 3)
DELETE FROM asistencia, invitacion, evento WHERE evento.id = 1 AND evento.id = invitacion.id_evento AND invitacion.id = asistencia.id_invitacion
insert into mesa values(2,42, 'cuadrado', 2);
insert into mesa values(2,2, 'redonda', 2);
insert into silla values(1,2);
insert into silla values(2,2);
insert into silla values(3,1);
insert into silla values(4,1);
insert into silla values(5,1);

insert into silla values(6,2);
insert into silla values(7,2);
ALTER TABLE asistencia
DROP CONSTRAINT asistencia_id_invitacion_fkey; -- Elimina la restricción existente

ALTER TABLE asistencia
ADD CONSTRAINT asistencia_id_invitacion_fkey
FOREIGN KEY (id_invitacion)
REFERENCES invitacion(id)
ON DELETE CASCADE
ON UPDATE CASCADE; -- Define la nueva restricción con ON DELETE CASCADE
--------------------------------
ALTER TABLE invitacion
DROP CONSTRAINT invitacion_id_evento_fkey; -- Elimina la restricción existente

ALTER TABLE invitacion
ADD CONSTRAINT invitacion_id_evento_fkey
FOREIGN KEY (id_evento)
REFERENCES evento(id)
ON DELETE CASCADE
ON UPDATE CASCADE; -- Define la nueva restricción con ON DELETE CASCADE

ALTER TABLE invitacion
ADD CONSTRAINT fk_invitacion_evento FOREIGN KEY (id_evento)
REFERENCES evento (id) ON DELETE CASCADE;

DROP table asistencia cascade

SELECT COUNT(*) AS total FROM invitacion, evento WHERE evento.id = 40 AND invitacion.id= 2 AND invitacion.id_evento = evento.id