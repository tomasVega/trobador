drop database  IF EXISTS trobador;
create database trobador DEFAULT CHARACTER SET UTF8 DEFAULT COLLATE UTF8_general_ci;
use trobador;

-- ACL roles
CREATE TABLE roles(
	
	role_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	role_name varchar(100) not null UNIQUE,
	role_description varchar(1000) not NULL

)ENGINE = MYISAM DEFAULT CHARSET=UTF8;


CREATE TABLE languages(
	
	language_id integer AUTO_INCREMENT not null,
	language varchar(5) not null,
	unit_language varchar(5) not null,	
	language_name varchar(500) not null,

	PRIMARY KEY(language_id, language)

) ENGINE = MYISAM DEFAULT CHARSET=UTF8;


CREATE TABLE users(
	
	user_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	email varchar(200) not null UNIQUE,
	password varchar(200) not null,
	name varchar(100) not null,
	validation_token varchar(60) not null,
	activated integer not null default 0,
	role_id integer not null default 2,

	-- Clave externa con la id del rol de usuario
	FOREIGN KEY(role_id) REFERENCES roles(role_id) -- ON DELETE RESTRICT ON UPDATE CASCADE

)ENGINE = MYISAM DEFAULT CHARSET=UTF8;


-- ACL resources
CREATE TABLE resources(
	
	resource_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	resource_name varchar(100) not null UNIQUE,
	resource_description varchar(255) not NULL

)ENGINE = MYISAM DEFAULT CHARSET=UTF8;


-- ACL privilegios
CREATE TABLE privileges(
	
	privilege_id integer AUTO_INCREMENT not null PRIMARY KEY,
	privilege_name varchar(75) not null

)ENGINE = MYISAM DEFAULT CHARSET=UTF8;


-- ACL recursos/permisos/roles
CREATE TABLE roles_resources_privileges(

	role_id integer not null,
	resource_id integer not null,
	privilege_id integer not null,

	PRIMARY KEY(role_id, resource_id, privilege_id),

	-- Clave externa con la id del rol de usuario
	FOREIGN KEY(role_id) REFERENCES roles(role_id), -- ON DELETE RESTRICT ON UPDATE CASCADE,

	-- Clave externa con la id del recurso
	FOREIGN KEY(resource_id) REFERENCES resources(resource_id), -- ON DELETE CASCADE ON UPDATE CASCADE,

	-- Clave externa con la id del privilegio
	FOREIGN KEY(privilege_id) REFERENCES privileges(privilege_id) -- ON DELETE CASCADE ON UPDATE CASCADE

)ENGINE = MYISAM DEFAULT CHARSET=UTF8;


CREATE TABLE projects(
	
	project_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	project_name varchar(300) not null UNIQUE,
	percent_completed float default 0,
	creation_date timestamp not null default now(),
	user_id integer not null,

	-- Clave externa con la id del usuario
	FOREIGN KEY(user_id) REFERENCES users(user_id) -- ON DELETE RESTRICT ON UPDATE CASCADE

)ENGINE = MYISAM DEFAULT CHARSET=UTF8;

-- Grupos de usuarios
CREATE TABLE users_projects(
	
	user_id integer not null,
	project_id integer not null,

	PRIMARY KEY(project_id, user_id),

	-- Clave externa con la id del rol de usuario
	FOREIGN KEY(project_id) REFERENCES projects(project_id), -- ON DELETE CASCADE ON UPDATE CASCADE,

	-- Clave externa con la id del usuario
	FOREIGN KEY(user_id) REFERENCES users(user_id) -- ON DELETE CASCADE ON UPDATE CASCADE

)ENGINE = MYISAM DEFAULT CHARSET=UTF8;


CREATE TABLE versions(
	
	version_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	version_name varchar(200) not null,
	project_id integer not null,
	user_id integer not null,
	creation_date timestamp not null default now(),

	-- Clave externa con la id del usuario
	FOREIGN KEY(user_id) REFERENCES users(user_id), -- ON DELETE RESTRICT ON UPDATE CASCADE,
	-- Clave externa con la id del proyecto que engloba a la version
	FOREIGN KEY(project_id) REFERENCES projects(project_id) -- ON DELETE CASCADE ON UPDATE CASCADE
	
)ENGINE = MYISAM DEFAULT CHARSET=UTF8;


CREATE TABLE translation_units(

	translation_unit_id integer AUTO_INCREMENT not null PRIMARY KEY,
	
	original_unit varchar(500) not null,
	original_unit_language varchar(5) not null,
	
	translated_unit varchar(500) not null,
	translated_unit_language varchar(5) not null,
	upload_date timestamp not null default now(),
	
	comment varchar(1000),

	-- Clave externa con la id del usuario
	user_id integer not null,
	FOREIGN KEY(user_id) REFERENCES users(user_id), -- ON DELETE RESTRICT ON UPDATE CASCADE,

	-- Clave externa con la id de la version
	version_id integer not null,
	FOREIGN KEY(version_id) REFERENCES versions(version_id) -- ON DELETE CASCADE ON UPDATE CASCADE

)ENGINE = MYISAM DEFAULT CHARSET=UTF8;

ALTER TABLE translation_units ADD FULLTEXT(original_unit);
ALTER TABLE translation_units ADD FULLTEXT(translated_unit);





-- ------------------------------- INSERCIÓN DE DATOS -------------------------------------------------------------------------------------------------

-- Roles
insert into roles(role_id, role_name, role_description) values(1, 'invitado', 'Usuario sin registrar');
insert into roles(role_id, role_name, role_description) values(2, 'basico', 'Usuario registrado de la aplicacion');
insert into roles(role_id, role_name, role_description) values(3, 'administrador', 'Usuario registrado con permisos de root');

-- Usuarios
insert into users values(1, 'user@user.com', '827ccb0eea8a706c4c34a16891f84e7b', 'User', 'aaabbbccc', 1, 2);
insert into users values(2, 'admin@admin.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Admin', 'aaabbbcccddd', 1, 3);

-- Recursos
insert into resources(resource_id, resource_name, resource_description) values(1, 'versiones', 'Versiones pertenecientes a los proyectos');
insert into resources(resource_id, resource_name, resource_description) values(2, 'proyectos', 'Proyectos en los que participar en su traducción');
insert into resources(resource_id, resource_name, resource_description) values(3, 'usuarios', 'Usuarios de la aplicación');
insert into resources(resource_id, resource_name, resource_description) values(4, 'grupos', 'Grupos de usuarios formados');
insert into resources(resource_id, resource_name, resource_description) values(5, 'recursos', 'Recursos de la aplicación');
insert into resources(resource_id, resource_name, resource_description) values(6, 'cadenas', 'Cadenas de traducción');
insert into resources(resource_id, resource_name, resource_description) values(7, 'roles', 'Roles de usuario');
insert into resources(resource_id, resource_name, resource_description) values(8, 'privilegios', 'Privilegios de la aplicación');
insert into resources(resource_id, resource_name, resource_description) values(9, 'acl', 'Acl');

-- Privilegios
insert into privileges(privilege_id, privilege_name) values(1, 'ver');
insert into privileges(privilege_id, privilege_name) values(2, 'crear');
insert into privileges(privilege_id, privilege_name) values(3, 'eliminar');
insert into privileges(privilege_id, privilege_name) values(4, 'editar');

-- Recursos/roles/privilegios (role_id, resource_id, privilege_id)
insert into roles_resources_privileges values(2, 1, 1);
insert into roles_resources_privileges values(2, 2, 1);
insert into roles_resources_privileges values(2, 3, 4);
insert into roles_resources_privileges values(2, 6, 2);
insert into roles_resources_privileges values(3, 1, 1);
insert into roles_resources_privileges values(3, 1, 2);
insert into roles_resources_privileges values(3, 1, 3);
insert into roles_resources_privileges values(3, 2, 1);
insert into roles_resources_privileges values(3, 2, 2);
insert into roles_resources_privileges values(3, 2, 3);
insert into roles_resources_privileges values(3, 3, 1);
insert into roles_resources_privileges values(3, 3, 2);
insert into roles_resources_privileges values(3, 3, 3);
insert into roles_resources_privileges values(3, 3, 4);
insert into roles_resources_privileges values(3, 4, 1);
insert into roles_resources_privileges values(3, 4, 2);
insert into roles_resources_privileges values(3, 4, 3);
insert into roles_resources_privileges values(3, 4, 4);
insert into roles_resources_privileges values(3, 5, 1);
insert into roles_resources_privileges values(3, 5, 2);
insert into roles_resources_privileges values(3, 5, 3);
insert into roles_resources_privileges values(3, 5, 4);
insert into roles_resources_privileges values(3, 6, 2);
insert into roles_resources_privileges values(3, 6, 3);
insert into roles_resources_privileges values(3, 7, 1);
insert into roles_resources_privileges values(3, 7, 2);
insert into roles_resources_privileges values(3, 7, 3);
insert into roles_resources_privileges values(3, 7, 4);
insert into roles_resources_privileges values(3, 8, 1);
insert into roles_resources_privileges values(3, 8, 2);
insert into roles_resources_privileges values(3, 8, 3);
insert into roles_resources_privileges values(3, 8, 4);
insert into roles_resources_privileges values(3, 9, 1);
insert into roles_resources_privileges values(3, 9, 2);
insert into roles_resources_privileges values(3, 9, 3);

-- Proyectos
insert into projects(project_id, project_name, user_id) values(1, 'Proyecto', 2);
insert into users_projects values(2, 1);

-- Versiones
insert into versions(version_id, version_name, project_id, user_id) values(1, '1.0', 1, 1);

-- Cadenas de traduccion
insert into translation_units values(1, 'hello <br>', 'en', 'hola', 'es', NOW(), 'comentario', 1, 1);
insert into translation_units values(2, 'hello', 'en', 'buenas', 'es', NOW(), 'comentario1', 1, 1);
insert into translation_units values(3, 'hello', 'en', 'eiii', 'es', NOW(), 'comentario2', 1, 1);
insert into translation_units values(4, 'hello hi', 'en', 'como va', 'es', NOW(), 'comentario3', 1, 1);
insert into translation_units values(5, 'hello hi hou', 'en', 'como va eso', 'es', NOW(), 'comentario4', 1, 1);


-- Idiomas
insert into languages(language, unit_language, language_name) values('gl_GL', 'gl', 'Galego');
insert into languages(language, unit_language, language_name) values('gl_GL', 'es', 'Español');
insert into languages(language, unit_language, language_name) values('gl_GL', 'en', 'Inglés');
insert into languages(language, unit_language, language_name) values('es_ES', 'gl', 'Gallego');
insert into languages(language, unit_language, language_name) values('es_ES', 'es', 'Español');
insert into languages(language, unit_language, language_name) values('es_ES', 'en', 'Inglés');
insert into languages(language, unit_language, language_name) values('en_EN', 'gl', 'Galician');
insert into languages(language, unit_language, language_name) values('en_EN', 'es', 'Spanish');
insert into languages(language, unit_language, language_name) values('en_EN', 'en', 'English');



