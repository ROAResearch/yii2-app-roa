Comandos de Composer
===================

Yii2 ROA Application usa los comandos de composer para facilitar el proceso de
instalación y manejar operaciones recurrentes.

Eventos de Composer
-------------------

Lista de eventos de composer implementados para esta aplicación
[Read more](https://getcomposer.org/doc/articles/scripts.md#event-names)

### pre-install-cmd

Revisa que la versión de composer sea `1.3` o superior.

### pre-update-cmd

Revisa que la versión de composer sea `1.3` o superior.

### command

Revisa que la versión de composer sea `1.3` o superior.

Comandos Personalizados
-----------------------

Lista de los comandos creados para esta aplicación.

### deploy

`composer deploy -- [arguments]`

Ejecuta todas las operaciones necesarias para tener una aplicación funcional.

* Nota: Requiere tener instalado el framework de Yii2.

> Usa los comandos
>
> * `create-project --prefer-dist`
> * `deploy-framework`
> * `deploy-database`
> * `run-tests`

Parametro |	Tipo   | Descripción                    | Valor por Defecto
--------- | ------ | ------------------------------ | -------
env       | string | Ambiente de trabajo            | dev
overwrite | bool   | Sobreescribir archivos locales |	Preguntar
dbuser    | string | Usuario de la Base de Datos    | Preguntar
dbpass    | string | contraseña de la Base de Datos | Preguntar
dbname    | string | Nombre de la Base de Datos     | Preguntar

### deploy-framework

`composer deploy-framework -- [arguments]`

Crea los archivos de configuración local, asigna permisos a carpetas y crea
enlaces simbolicos. Este comando reemplaza el comando `init` del repositorio
[yii2-app-avanced].

Puede ser personalizado editando la clase `console\FrameworkListener`.

* Nota: Requiere tener instalado el framework de Yii2.

Parametro |	Tipo   | Descripción                    | Valor por Defecto
--------- | ------ | ------------------------------ | -------
env       | string | Ambiente de trabajo            | dev
overwrite | bool   | Sobreescribir archivos locales |	Preguntar
 
### deploy-database

`composer deploy-database -- [arguments]`

Aasegura la existencia de la base de datos y carga la estructura usando migraciones y fixtures.

> Nota: De momento sólo `mysql` esta soportado.

> Note: Requires to have yii2 framework installed

> Uses commands
>
> * `config-database`
> * `run-migrations`
> * `run-fixtures`
> * `clear-cache`

### truncate-database


