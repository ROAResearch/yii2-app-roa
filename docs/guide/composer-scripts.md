Composer Scripts
================

Yii2 ROA Application uses composer scripts to ease  the instalation process and
handle recurring operations.

Composer Events
---------------

List of composer events used by this application.
[Read more](https://getcomposer.org/doc/articles/scripts.md#event-names)

### pre-install-cmd

Checks that the composer version is `1.3` or higher.

### pre-update-cmd

Checks that the composer version is `1.3` or higher.

### command

Checks that the composer version is `1.3` or higher.

### post-create-project-cmd

Executes the `deploy` script.

> Note: Requires to have yii2 framework installed

> Uses commands
>
> * `deploy`

Parameter |	Type   | Description                  | Default
--------- | ------ | ---------------------------- | -------
env       | string | Application work environment | dev
overwrite | bool   | Overwrite local files        |	n


Custom Commands
---------------

List of commands created for this application.

### deploy

`composer deploy -- [arguments]`

Runs all the necessary operations to have a functional application

*Requires to have yii2 framework installed*

> Uses commands
>
> * `deploy-framework`
> * `deploy-database`
> * `run-tests`

Parameter |	Type   | Description                  | Default
--------- | ------ | ---------------------------- | -------
env       | string | Application work environment | dev
overwrite | bool   | Overwrite local files        |	Prompt question
db-user   | string | Database username            | Prompt question
db-pass   | string | Database password            | Prompt question
db-name   | string | Database name                | Prompt question

### deploy-framework

`composer deploy-framework -- [arguments]`

Creates all the local files, assign permissions to folders and creates symlinks.
This command replaced the `init` script from the [yii2-app-avanced] repository.

Can be customized by editing the `console\FrameworkListener` class.

> Note: Requires to have yii2 framework installed

Parameter |	Type   | Description                  | Default
--------- | ------ | ---------------------------- | -------
env       | string | Application work environment | dev
overwrite | bool   | Overwrite local files        |	n

### deploy-database

`composer deploy-database -- [arguments]`

Ensures the database and loads the structure using migrations and fixtures.

> Note: Currently only mysql driver is supported.

> Note: Requires to have yii2 framework installed

> Uses commands
>
> * `config-database`
> * `run-migrations`
> * `run-fixtures`
> * `clear-cache`

### truncate-database

`composer truncate-database`

Drops the configured database and create it again using migrations and fixtures.

> Note: Currently only mysql driver is supported.

> Note: requires a configured database.

*Requires to have yii2 framework installed*

> Uses commands
>
> * `run-migrations`
> * `run-fixtures`
> * `clear-cache`

### config-database

`composer config-database -- [arguments]`

Ensures the database permissions and save them for framework usage.

Can be customized by editing the `console\DatabaseListener` class.

> Note: Currently only mysql driver is supported.

> Note: Requires to have yii2 framework installed*

Parameter |	Type   | Description       | Default
--------- | ------ | ----------------- | ---------------
db-user   | string | Database username | Prompt question
db-pass   | string | Database password | Prompt question
db-name   | string | Database name     | Prompt question

### run-migrations

`composer run-migrations`

Runs all the required migrations for the application

Can be customized by editing the `composer.json` file.

> Note: Supports all drivers after configuration.

> Note: Requires to have yii2 framework installed*

### run-fixtures

`composer run-fixtures`

Runs all the fixtures that load the minimum data to use the database.

Can be customized by editing the `composer.json` file.

> Note: Supports all drivers after configuration.

> Note: Requires to have yii2 framework installed*

### clear-cache

`composer clear-cache`

Clears all the cache used by the framework.

Can be customized by editing the `composer.json` file.

### run-tests

`composer run-tests`

Short cut to run the codeception tests.

Can be customized by editing the `composer.json` file.

### run-coverage

`composer run-coverage`

Short cut to run the codeception tests with code coverage.

Can be customized by editing the `composer.json` file.

