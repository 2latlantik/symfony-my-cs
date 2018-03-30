<?php 
namespace SymfonyMyCs\Git\Hooks;

use Composer\Script\Event;

class PostInstall 
{
    public static function installHooks(Event $event) {
        $io = $event->getIO();
        $io->write('<info>installHooks!</info>');
        echo 'post cmd composer';
        return true;
    }
}