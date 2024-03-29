<?php

namespace console;

use Composer\Script\Event;

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
        require dirname(__DIR__) . '/vendor/autoload.php';
    }

    public static function verifyDev(Event $event)
    {
        if ('dev' == getenv('environment')) {
            return;
        }

        $event->stopPropagation();
        echo "Script ", $event->getOriginatingEvent()->getName(),
            " cant be used in production as is: ";
        echo match ($event->getArguments()[0]) {
            'edit' => 'Edit the script to remove non-production processes',
            default => 'Delete script completely, never use in production',
        },
        "\n";
        exit(1);
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
}
