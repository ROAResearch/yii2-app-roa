Composer Scripts
================

Yii2 ROA Application uses composer scripts to ease the instalation process and
handle recurring operations.

You can get the list of all the composer scripts along with a brief description
using the comand

`composer run-script -l`

Composer Events
---------------

List of composer events used by this application.
[Read more](https://getcomposer.org/doc/articles/scripts.md#event-names)

Currently only 2 events are used for this library, both check that the composer
version used is 1.3.0 or higher.

- pre-install-cmd
- pre-update-cmd


Custom Commands
---------------

List of commands created for this application.

### help-scripts

`composer help-scripts`

Show this documentaiton file in console.

### browse-help-scripts

`composer browse-help-scripts`

Shows this documentation file in your default browser.

### validate-php-file

`composer validate-php-file -- [filePath]`

Where `[filePath]` is the path to a file ending in `.php`. It checks php syntax
(lint) and coding style (php-cs-fixer).

> Note: After deploying the framework a `.php_cs` is automatically created, you
  can edit  this file for local changes or `environments/dev/.php_cs` to commit
 changes for all your team.

> Note: a precommit githook is created automatically when creating this project
  and utilizes this script to validate all the files to be commited which end
  in `.php`.

### deploy

`composer deploy -- [arguments]`

Runs all the necessary operations to have a functional application

> Uses commands
>
> * `update --prefer-stable --prefer-dist`
> * [deploy-framework](#deploy-framework)
> * [deploy-database](#deploy-database)
> * [run-tests](#run-tests)

Parameter |	Type   | Description                  | Default
--------- | ------ | ---------------------------- | -------
env       | string | Application work environment | dev
overwrite | bool   | Overwrite local files        |	Prompt question
dbhost    | string | Database host                | Prompt question[127.0.0.1]
dbuser    | string | Database username            | Prompt question[root]
dbpass    | string | Database password            | Prompt question
dbname    | string | Database name                | Prompt question[yii2_app_roa]

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
> * [config-database](#config-database)
> * [run-migrations](#run-migrations)
> * [run-fixtures](#run-fixtures)
> * [clear-cache](#clear-cache)

Parameter |	Type   | Description       | Default
--------- | ------ | ----------------- | ---------------
dbhost    | string | Database host     | Prompt question[127.0.0.1]
dbuser    | string | Database username | Prompt question[root]
dbpass    | string | Database password | Prompt question
dbname    | string | Database name     | Prompt question[yii2_app_roa]

### truncate-database

`composer truncate-database`

Drops the configured database and create it again using migrations and fixtures.

> Note: Currently only mysql driver is supported.

> Note: requires a configured database.

> Requires to have yii2 framework installed*

> Uses commands
>
> * [run-migrations](#run-migrations)
> * [run-fixtures](#run-fixtures)
> * [clear-cache](#clear-cache)

### config-database

`composer config-database -- [arguments]`

Ensures the database permissions and save them for framework usage.

Can be customized by editing the `console\DatabaseListener` class.

> Note: Currently only mysql driver is supported.

> Note: Requires to have yii2 framework installed*

Parameter |	Type   | Description       | Default
--------- | ------ | ----------------- | ---------------
dbhost    | string | Database host     | Prompt question[127.0.0.1]
dbuser    | string | Database username | Prompt question[root]
dbpass    | string | Database password | Prompt question
dbname    | string | Database name     | Prompt question[yii2_app_roa]

### migrate-up

`composer migrate-up -- [arguments]`

shortcut for `yii migrate/up 0 --interactive=0`

See https://www.yiiframework.com/doc/api/2.0/yii-console-controllers-basemigratecontroller#actionUp()-detail

### migrate-down

`composer migrate-down -- [arguments]`

shortcut for `yii migrate/down 0 --interactive=0`

See https://www.yiiframework.com/doc/api/2.0/yii-console-controllers-basemigratecontroller#actionDown()-detail

### run-migrations

`composer run-migrations`

Runs all the required migrations for the application

Can be customized by editing the `composer.json` file.

> Note: Supports all drivers after configuration.

> Note: Requires to have yii2 framework installed*

> Uses commands
>
> * [migrate-up](#migrate-up)


### truncate-migrations

`composer truncate-migrations`

Runs down all the migrations and runs them up again.

Can be customized by editing the `composer.json` file.

> Note: Supports all drivers after configuration.

> Note: Requires to have yii2 framework installed*

> Uses commands
>
> * [migrate-down](#migrate-down)
> * [run-migrations](#run-migrations)
> * [clear-framework-cache](#clear-framework-cache)

### fixture-load

`composer fixture-load -- [arguments]`

shortcut for `yii fixture/load '*' 0 --interactive=0`

See https://www.yiiframework.com/doc/api/2.0/yii-console-controllers-fixturecontroller#actionLoad()-detail

### run-fixtures

`composer run-fixtures`

Runs all the fixtures that load the minimum data to use the database.

Can be customized by editing the `composer.json` file.

> Note: Supports all drivers after configuration.

> Note: Requires to have yii2 framework installed*

> Uses commands
>
> * [fixture-load](#fixture-load)

### clear-framework-cache

`composer clear-framework-cache`

Clears all the cache used by the framework.

Can be customized by editing the `composer.json` file.

### run-tests

`composer run-tests -- [codeceptionOptions]`

Shortcut for `codecept run --steps`

See https://codeception.com/docs/reference/Commands#Run

### run-tests-debug

`composer run-tests -- [codeceptionOptions]`

Shortcut for `run-tests -- -vv -f --no-rebuild`

> Uses commands
>
> * [run-tests](#run-tests)

See https://codeception.com/docs/reference/Commands#Run

### run-coverage

`composer run-coverage -- [codeceptionOptions]`

Shortcut for `run-tests -- --coverage --coverage-xml`

> Uses commands
>
> * [run-tests](#run-tests)

See https://codeception.com/docs/reference/Commands#Run
