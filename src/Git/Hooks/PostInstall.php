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
        $source = $rootPath.'vendor'.DIRECTORY_SEPARATOR.'2latlantik'.DIRECTORY_SEPARATOR.'symfony-my-cs'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'git-hooks'.DIRECTORY_SEPARATOR.'pre-commit';
        $destination = $rootPath.'.git'.DIRECTORY_SEPARATOR.'hooks'.DIRECTORY_SEPARATOR.'pre-commit';

        copy($source, $destination);
        chmod($destination, 0777);
    }

    public static function phpcsXmlCopy($rootPath) {
        $source = $rootPath.'vendor'.DIRECTORY_SEPARATOR.'2latlantik'.DIRECTORY_SEPARATOR.'symfony-my-cs'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'phpcs-rules'.DIRECTORY_SEPARATOR.'phpcs.xml';
        $destination = $rootPath.'phpcs.xml';

        copy($source, $destination);
    }

    public static function getRootPath() {
        $rootPath = __DIR__;
        for($i = 1 ; $i <= 6 ; $i++) {
            $rootPath .= DIRECTORY_SEPARATOR . '..';
        }       
        return realpath($rootPath);
    }
}