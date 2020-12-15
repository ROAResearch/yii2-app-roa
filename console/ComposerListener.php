<?php

namespace console;

use Composer\Script\Event;
use yii\helpers\Console;

class ComposerListener
{
    /**
     * @param  Event  $event
     */
    public static function init(Event $event)
    {
        $composer = $event->getComposer();
        if (version_compare($composer::VERSION, '1.3.0', 'le')) {
            $event->stopPropagation();
            echo "Please update your composer version to 1.3 or higher.\n";
            exit(1);
        }
    }

    /**
     * Load the composer autoload from: `vendor/autoload.php`.
     * @param  Event  $event
     */
    public static function autoload(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require dirname(__DIR__) . '/common/config/constants.php';
        require $vendorDir . '/autoload.php';
        require $vendorDir . '/yiisoft/yii2/Yii.php';
        require dirname(__DIR__) . '/console/models/Host.php';
    }

    /**
     * Arguments in string are parsed to key-pair values.
     *
     * ```php
     * $args = self::parseArguments([
     *     'opt=value',
     *     'foo=bar'
     * ]);
     *
     * print_r($args);
     * ```
     *
     * The output will be:
     *
     * > Array
     * > (
     * >    [opt] => value
     * >    [foo] => bar
     * > )
     *
     * @param  array[]  $args
     * @return array[]
     */
    public static function parseArguments(array $args)
    {
        $parsed = [];
        foreach ($args as $arg) {
            $parse = explode('=', $arg);
            if (!isset($parse[1])) {
                $parsed[$parse[0]] = true;
            } else {
                $parsed[$parse[0]] = $parse[1];
            }
        }

        return $parsed;
    }

    public static function config(Event $event)
    {
        $args = self::parseArguments($event->getArguments());

        if (array_key_exists('env', $args) && $args['env'] === 'Production') {
            $command = 'install --no-dev --optimize-autoloader';
        } else {
            $command = 'update --prefer-dist --prefer-stable';
        }

        $BannerComposer = '
       ______
      / ____/___  ____ ___  ____  ____  ________  _____
     / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
    / /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
    \____/\____/_/ /_/ /_/ .___/\____/____/\___/_/  ' . $command . '
                        /_/';
        self::msgCyan($BannerComposer);

        return system('composer ' . $command);
    }

    public static function migrateUp(Event $event)
    {
        self::autoload($event);
        $migrate = $event->getArguments()[0];

        if (YII_ENV_PROD) {
            $command = 'php yii migrate/up 0 --interactive=0 ' . $migrate;
        } else {
            $command = 'php yii migrate/up 0 --interactive=0 ' . $migrate;
            $command = $command . ';php yii_test migrate/up 0 --interactive=0 ' . $migrate;
        }

        return self::executeCommand($command);
    }

    public static function migrateDown(Event $event)
    {
        self::autoload($event);

        if (YII_ENV_PROD) {
            $command = 'php yii migrate/down all --interactive=0';
        } else {
            $command = 'php yii migrate/down all --interactive=0';
            $command = $command . ';php yii_test migrate/down all --interactive=0';
        }

        return self::executeCommand($command);
    }

    public static function fixtureLoad(Event $event)
    {
        self::autoload($event);
        $fixture = (empty($event->getArguments())) ? '' : ' "' . $event->getArguments()[0] . '"';

        if (YII_ENV_PROD) {
            $command = 'php yii fixture/load "*" --interactive=0' . $fixture;
        } else {
            $command = 'php yii fixture/load "*" --interactive=0' . $fixture;
            $command = $command . ';php yii_test fixture/load "*" --interactive=0' . $fixture;
        }

        return self::executeCommand($command);
    }

    public static function runTest(Event $event)
    {
        self::autoload($event);

        if (YII_ENV_PROD) {
            return self::msgCyan('Test is not possible on production systems');
        }

        if (empty($event->getArguments())) {
            $arg = '##';
        } else {
            $arg = implode(' ', $event->getArguments());
        }

        $command = 'php vendor/bin/codecept run --steps ' . $arg;

        return self::executeCommand($command);
    }

    protected static function executeCommand($command)
    {
        return self::msgCyan($command) . self::msg(system($command));
    }

    protected static function msg($message)
    {
        return Console::output(Console::ansiFormat($message));
    }

    protected static function msgCyan($message)
    {
        return Console::output(Console::ansiFormat($message, [Console::FG_CYAN]));
    }
}
