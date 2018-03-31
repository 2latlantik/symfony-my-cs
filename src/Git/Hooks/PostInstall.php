<?php 
namespace SymfonyMyCs\Git\Hooks;

use Composer\Script\Event;
use Exception;

class PostInstall {

    public static function installHooks(Event $event) {
        $rootPath = self::getRootPath();    
        
        if(!file_exists($rootPath.'.git')) {
            throw new Exception('Unable to find git repository');
        }

        $io = $event->getIO();

        $io->write('<info>install Git Hook pre-commit!</info>');
        self::gitHookCopy($rootPath);

        $io->write('<info>install php-cs rules!</info>');
        self::phpcsXmlCopy($rootPath);

        return true;
    }

    public static function gitHookCopy($rootPath) {
        $source = $rootPath.'vendor/2latlantik/bin/git-hooks/pre-commit';
        $destination = $rootPath.'.git/hooks/pre-commit';

        copy($source, $destination);
        chmod($destination, 0777);
    }

    public static function phpcsXmlCopy($rootPath) {
        $source = $rootPath.'vendor/2latlantik/bin/phpcs-rules/phpcs.xml';
        $destination = $rootPath.'phpcs.xml';

        copy($source, $destination);
    }

    public static function getRootPath() {
        $rootPath = __DIR__;
        for($i = 1 ; $i <= 5 ; $i++) {
            $rootPath .= DIRECTORY_SEPARATOR . '..';
        }       
        return realpath($rootPath);
    }
}