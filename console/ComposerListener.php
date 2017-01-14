<?php

namespace console;

use Composer\Script\Event;

class ComposerListener
{
    public static function init(Event $event)
    {
        $composer = $event->getComposer();
        if (version_compare($composer::VERSION, '1.3.0', 'le')) {
            $event->stopPropagation();
            echo "Please update your composer version to 1.3 or higher.\n";
            die();
        }
    }
}
