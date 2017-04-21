<?php

namespace console;

use Composer\Script\Event;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\FileHelper;

class FrameworkListener
{
    /**
     * @var string environment to be deployed
     */
    protected static $env = 'Development';

    /**
     * @var bool|null if the files should be overwritten by default. `null` will
     * prompt the user.
     */
    protected static $overwrite = null;

    private static $root;

    private static $callbacks = [
        'setCookieValidationKey',
        'setWritable',
        'setExecutable',
        'createSymlink',
    ];

    /**
     * Deploys the framework folders, files, permissions and symlinks.
     *
     * @param Event $event
     */
    public static function deploy(Event $event)
    {
        Console::output(
            Console::ansiFormat(
                'Yii2 ROA Application Initialization Tool v2.0',
                [Console::FG_CYAN]
            )
        );
        self::$root = dirname(__DIR__);
        $envs = require(self::$root . '/environments/index.php');
        $args = ComposerListener::parseArguments($event->getArguments());
        self::$env = ArrayHelper::getValue($args, 'env', static::$env);
        if (empty($envs[self::$env])) {
            Console::error(
                'Environment `'
                    . self::$env
                    . '` not found in /environments/index.php'
            );
            exit(1);
        } else {
            Console::output(
                'Deploying the `'
                    . Console::ansiFormat(self::$env, [Console::FG_GREEN])
                    . '` environment.'
            );
            $env = $envs[self::$env];
        }
        if (null !== ($over = ArrayHelper::getValue($args, 'overwrite'))) {
            self::$overwrite = in_array(
                $over,
                [1, "1", true, 'y', 'yes'],
                true
            );
        }

        FileHelper::copyDirectory(
            self::$root . "/environments/{$env['path']}",
            self::$root,
            [
                'except' => ArrayHelper::getValue($env, 'skipFiles', []),
                'filter' =>  [self::class, 'fileOverwrite'],
                'afterCopy' => function ($from, $to) {
                    if (is_file($to)) {
                        Console::output(
                            '    generated '
                                . Console::ansiFormat($to, [Console::FG_CYAN])
                        );
                    }
                }
            ]
        );

        foreach (self::$callbacks as $callback) {
            $paths = ArrayHelper::getValue($env, $callback, []);
            array_walk($paths, [self::class, $callback]);
        }
    }

    public static function fileOverwrite($path)
    {
        if (is_dir($path) || !file_exists($path)) {
            return true;
        }
        if (null !== self::$overwrite) {
            return self::$overwrite;
        }
        $answer = Console::prompt(
            '  Override ' . Console::ansiFormat($path, [Console::FG_RED])
                . '? [yes, no, all, none]'
        );
        if ($answer === 'all') {
            self::$overwrite = true;
            return true;
        }
        if ($answer === 'none') {
            self::$overwrite = false;
            return false;
        }
        return in_array($answer, ['y', 'yes'], true);
    }


    public static function setWritable($path)
    {
        if (is_dir(self::$root . "/$path")) {
            if (@chmod(self::$root . "/$path", 0777)) {
                Console::output("      chmod 0777 $path.");
            } else {
                Console::error(
                    "Operation chmod not permitted for directory $path."
               );
            }
        } else {
            Console::error("Directory $path does not exist.");
        }
    }

    public static function setExecutable($path)
    {
        if (file_exists(self::$root . "/$path")) {
            if (@chmod(self::$root . "/$path", 0755)) {
                Console::output("      chmod 0755 $path.");
            } else {
                Console::error("Operation chmod not permitted for $path.");
            }
        } else {
            Console::error("$path does not exist.");
        }
    }

    public static function setCookieValidationKey($file)
    {
        Console::output("   generate cookie validation key in $file.");
        $file = self::$root . '/' . $file;
        $length = 32;
        $bytes = openssl_random_pseudo_bytes($length);
        $key = strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
        $content = preg_replace('/(("|\')cookieValidationKey("|\')\s*=>\s*)(""|\'\')/', "\\1'$key'", file_get_contents($file));
        file_put_contents($file, $content);
    }

    public static function createSymlink($link, $target)
    {
        $link = self::$root . "/$link";
        $target = self::$root . "/$target";

        //first removing folders to avoid errors if the folder already exists
        @rmdir($link);
        //next removing existing symlink in order to update the target
        if (is_link($link)) {
            @unlink($link);
        }
        if (@symlink($target, $link)) {
            Console::output("      symlink $target $link.");
        } else {
            Console::error("Cannot create symlink $target $link.");
        }
    }
}
