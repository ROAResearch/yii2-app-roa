<?php

namespace console;

use Composer\Script\Event;
use yii\helpers\{ArrayHelper, Console, FileHelper};

class FrameworkListener
{
    /**
     * @var string environment to be deployed
     */
    protected static $env = 'dev';

    /**
     * @var bool|null if the files should be overwritten by default. `null` will
     * prompt the user.
     */
    protected static $overwrite = null;

    /**
     * @var string path to the folder containing this project.
     */
    private static $root;

    /**
     * @var string[] list of possible callbacks which can be defined in file
     * `@environments/index.php`
     */
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
        $envs = require self::$root . '/environments/index.php';
        $args = ComposerListener::parseArguments($event->getArguments());
        self::$env = getenv('environment');
        if (empty($envs[self::$env])) {
            Console::error(
                'Environment `'
                    . self::$env
                    . '` not found in /environments/index.php'
            );
            exit(1);
        }
        Console::output(
            'Deploying the `'
                . Console::ansiFormat(self::$env, [Console::FG_GREEN])
                . '` environment.'
        );
        $env = $envs[self::$env];
        if (null !== ($over = ArrayHelper::getValue($args, 'overwrite'))) {
            self::$overwrite = in_array(
                $over,
                [1, '1', true, 'y', 'yes'],
                true
            );
        }

        FileHelper::copyDirectory(
            self::$root . "/environments/{$env['path']}",
            self::$root,
            [
                'except' => ArrayHelper::getValue($env, 'skipFiles', []),
                'beforeCopy' => [self::class, 'fileOverwrite'],
                'afterCopy' => [self::class, 'copyFileConsoleOutput'],
                'copyEmptyDirectories' => false,
            ]
        );

        foreach (self::$callbacks as $callback) {
            $paths = ArrayHelper::getValue($env, $callback, []);
            array_walk($paths, [self::class, $callback]);
        }
    }

    /**
     * Outputs in console when a file is successfully copied.
     *
     * @param string $from
     * @param string $to
     */
    public static function copyFileConsoleOutput(string $from, string $to)
    {
        if (is_file($to)) {
            Console::output(
                '    generated '
                    . Console::ansiFormat($to, [Console::FG_CYAN])
            );
        }
    }

    /**
     * Determines if a file can be overwritten.
     *
     * @param string $from origin file to be copied.
     * @param string $to path where file will be copied.
     * @return bool whether to proceed copying the file.
     */
    public static function fileOverwrite(string $from, string $to)
    {
        if (is_dir($to) || !file_exists($to)) {
            return true;
        }
        if (null !== self::$overwrite) {
            return self::$overwrite;
        }
        $answer = Console::prompt(
            '  Override ' . Console::ansiFormat($to, [Console::FG_RED])
                . '? [yes, no, all, none]'
        );
        if ($answer === 'all') {
            return self::$overwrite = true;
        }
        if ($answer === 'none') {
            return self::$overwrite = false;
        }

        return in_array($answer, ['y', 'yes'], true);
    }

    /**
     * Assigns read/write permissions to a folder or file path.
     *
     * @param string $path file or folder path to assign permissions.
     */
    public static function setWritable(string $path)
    {
        static::chmod($path, 0777);
    }

    /**
     * Assigns execute permissions to a folder or file path.
     *
     * @param string $path file or folder path to assign permissions.
     */
    public static function setExecutable(string $path)
    {
        static::chmod($path, 0755);
    }

    /**
     * Assigns Permissions to a folder or file path.
     *
     * @param string $path file or folder path to assign permissions.
     * @param int $permission octal permission to be assigned.
     */
    protected static function chmod(string $path, $permission)
    {
        $fullPath = self::$root . "/$path";
        if (file_exists($fullPath)) {
            if (@chmod($fullPath, $permission)) {
                Console::output(
                    '      chmod '
                        . base_convert($permission, 10, 8) . ' '
                        . Console::ansiFormat($path, [Console::FG_CYAN])
                );
            } else {
                Console::error(
                    'Operation chmod not permitted for '
                        . Console::ansiFormat($path, [Console::FG_RED])
                );
            }
        } else {
            Console::error(
                Console::ansiFormat($path, [Console::FG_RED])
                    . ' does not exist.'
            );
        }
    }

    /**
     * Generates a random cookie validation key and inject it into a file
     *
     * @param string $file path to the file.
     */
    public static function setCookieValidationKey(string $file)
    {
        Console::output("   generate cookie validation key in $file.");
        $file = self::$root . '/' . $file;
        $length = 32;
        $bytes = openssl_random_pseudo_bytes($length);
        $key = strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
        $content = preg_replace(
            '/(("|\')cookieValidationKey("|\')\s*=>\s*)(""|\'\')/',
            "\\1'$key'",
            file_get_contents($file)
        );
        file_put_contents($file, $content);
    }

    /**
     * Creates a symbolic link
     *
     * @param string $link the path of the linked file or folder
     * @param string $target the path of the link
     */
    public static function createSymlink(string $target, string $link)
    {
        $link = self::$root . "/$link";
        $target = self::$root . "/$target";

        //next removing existing symlink in order to update the target
        if (is_link($link)) {
            unlink($link);
        }
        //first removing folders to avoid errors if the folder already exists
        self::rmdir($link);
        if (symlink($target, $link)) {
            Console::output("      symlink $target $link.");
        } else {
            Console::error("Cannot create symlink $target $link.");
        }
    }

    /**
     * @param string route of directory to delete
     */
    public static function rmdir(string $dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object == '.' || $object == '..') {
                    continue;
                }
                if (is_dir($dir . '/' . $object)) {
                    self::rmdir($dir . '/' . $object);
                } else {
                    unlink($dir . '/' . $object);
                }
            }
            rmdir($dir);
            reset($objects);
        }
    }
}
