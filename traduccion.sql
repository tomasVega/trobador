drop database traduccion;
create database IF NOT EXISTS traduccion DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_spanish_ci;
use traduccion;

CREATE TABLE users(
	
	user_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	email varchar(200) not null UNIQUE,
	password varchar(200) not null,
	name varchar(100) not null,
	validation_token varchar(60) not null,
	activated integer not null default 0

)TYPE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE roles(
	
	role_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	role_name varchar(100) not null UNIQUE,
	role_description varchar(255) not NULL

)TYPE=MYISAM DEFAULT CHARSET=utf8;


CREATE TABLE users_roles(
	
	role_id varchar(100) not null,
	user_id integer not null,

	PRIMARY KEY(role_id, user_id),

	-- Clave externa con la id del rol de usuario
	FOREIGN KEY (role_id) REFERENCES roles(role_id),
	-- Clave externa con la id del usuario
	FOREIGN KEY (user_id) REFERENCES users(user_id)

)TYPE=MYISAM DEFAULT CHARSET=utf8;


CREATE TABLE projects(
	
	project_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	project_name varchar(300) not null,
	percent_completed float default 0,
	creation_date timestamp not null default now(),
	user_id integer not null,

	-- Clave externa con la id del usuario
	FOREIGN KEY(user_id) REFERENCES users(user_id)

)TYPE=MYISAM DEFAULT CHARSET=utf8;


CREATE TABLE versions(
	
	version_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	version_name varchar(200) not null,
	project_id integer not null,
	user_id integer not null,
	creation_date timestamp not null default now(),

	-- Clave externa con la id del usuario
	FOREIGN KEY(user_id) REFERENCES users(user_id),
	-- Clave externa con la id del proyecto que engloba a la version
	FOREIGN KEY(project_id) REFERENCES projects(project_id)
	
)TYPE=MYISAM DEFAULT CHARSET=utf8;


CREATE TABLE tmx(
	
	tmx_id integer AUTO_INCREMENT not null PRIMARY KEY,
	name varchar(200) not null,
	upload_date timestamp not null default now(),
	
	user_id integer not null,
	version_id integer not null,

	-- Clave externa con la id del usuario
	FOREIGN KEY(user_id) REFERENCES users(user_id),
	-- Clave externa con la id de la version
	FOREIGN KEY(version_id) REFERENCES versions(version_id)

)TYPE=MYISAM DEFAULT CHARSET=utf8;


CREATE TABLE translation_units(

	translation_unit_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	original_unit varchar(500) not null,
	original_unit_language varchar(5) not null,
	
	translated_unit varchar(500) not null,
	translated_unit_language varchar(5) not null,
	
	comment varchar(250),

	FULLTEXT (original_unit, translated_unit),

	-- Clave externa con la id del tbx
	tmx_id integer not null,
	FOREIGN KEY(tmx_id) REFERENCES tmx(tmx_id)

)TYPE=MYISAM DEFAULT CHARSET=utf8;

-- ALTER TABLE translation_units ADD FULLTEXT(original_unit);
-- ALTER TABLE translation_units ADD FULLTEXT(translated_unit);



-- datos
insert into users values(1, 'user@user.com', '827ccb0eea8a706c4c34a16891f84e7b', 'User 1', 'aaabbbccc',1);
insert into tmx(tmx_id, name, user_id) values(1, 'file', 1);
insert into translation_units values(1, 'hi', 'en', 'hola', 'es', 'comentario', 1);
insert into translation_units values(2, 'hi', 'en', 'buenas', 'es', 'comentario1', 1);

