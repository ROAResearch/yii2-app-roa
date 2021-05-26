Testing
===============================

Yii2 ROA Project Template uses Codeception as its primary test framework. 
There are already some sample tests prepared in `tests` directory of `frontend`, `backend`, and `common`.

The command `composer deploy` initialize testing when its used with the `dev`
environment. This will create a `_test` database run the migrations on it, and
load the required fixtures.

```
composer deploy -- environment=dev
```

To run the tests you can use the following commands

```
composer run-tests -- # codeception options here
composer run-tests-debug -- # codeception options here
composer run-coverage -- # codeception options here
```

You will see output similar to this:

![](images/tests.png)

It is recommended to keep your tests up to date. If a class, or functionality is deleted, corresponding tests should be deleted as well.
You should run tests regularly, or better to set up Continuous Integration server for them.  

Please refer to [Yii2 Framework Case Study](http://codeception.com/for/yii) to learn how to configure Codeception for your application.

### Common

Tests for common classes are located in `common/tests`. In this template there are only `unit` tests.
Execute them by running:

```
composer run-tests -- -c common
```

`-c` option allows to set path to `codeception.yml` config.

Tests in `unit` test suite (located in `common/tests/unit`) can use Yii framework features: `Yii::$app`, Active Record, fixtures, etc.
This is done because `Yii2` module is enabled in unit tests config: `common/tests/unit.suite.yml`. You can disable it to run tests in complete isolation. 


### Frontend

Frontend tests contain unit tests, functional tests, and acceptance tests.
Execute them by running:

```
composer run-tests -- -c frontend
```

Description of test suites:

* `unit` ⇒ classes related to frontend application only.
* `functional` ⇒ application internal requests/responses (without a web server).
* `acceptance` ⇒ web application, user interface and javascript interactions in real browser.

By default acceptance tests are disabled, to run them use:

#### Running Acceptance Tests

The acceptance tests use [geckodriver](https://github.com/mozilla/geckodriver) for firefox by default, so make sure [geckodriver](https://github.com/mozilla/geckodriver) is in the `PATH`.

To execute acceptance tests do the following:  

1. Rename `frontend/tests/acceptance.suite.yml.example` to `frontend/tests/acceptance.suite.yml` to enable suite configuration

1. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full featured
   version of Codeception

1. Update dependencies with Composer 

    ```
    composer update  
    ```

1. Auto-generate new support classes for acceptance tests:

    ```
    vendor/bin/codecept build -- -c frontend
    ```

1. Download [Selenium Server](http://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```
    > There are currently [issues](https://github.com/facebook/php-webdriver/issues/492) with geckodriver's
    > interactions with selenium that require you to enable the protocol translating in Selenium.
    > `java -jar ~/selenium-server-standalone-x.xx.x.jar -enablePassThrough false`

1. Start web server:

    ```
    yii serve -t=@frontend/web
    ```

1. Now you can run all available tests

   ```
   composer run-tests -- acceptance -c frontend
   ```

## Backend

Backend application contain unit and functional test suites. Execute them by running:

```
composer run-tests -- -c backend
```
