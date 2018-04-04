<?php
namespace SymfonyMyCs\Git\Hooks;

use Composer\Script\Event;
use Exception;

/**
 * Class PostInstall
 *
 * @package SymfonyMyCs\Git\Hooks
 */
class PostInstall
{

    /**
     * @param Event $event
     * @return bool
     * @throws Exception
     */
    public static function installHooks(Event $event) :bool
    {
        $rootPath = self::getRootPath().DIRECTORY_SEPARATOR;
        
        if (!file_exists($rootPath.'.git')) {
            throw new Exception('Unable to find git repository');
        }

        $io = $event->getIO();

        $io->write('<info>install Git Hook pre-commit!</info>');
        self::gitHookCopy($rootPath);

        $io->write('<info>install php-cs rules!</info>');
        self::phpcsXmlCopy($rootPath);

        $io->write('<info>install phpmd rules!</info>');
        self::phpmdXmlCopy($rootPath);

        return true;
    }

    /**
     * @param $rootPath
     */
    public static function gitHookCopy($rootPath) :void
    {
        $source = $rootPath.'vendor'.DIRECTORY_SEPARATOR.'2latlantik'.DIRECTORY_SEPARATOR.'symfony-my-cs'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'git-hooks'.DIRECTORY_SEPARATOR.'pre-commit.sh';
        $gitHookFolder = $rootPath.'.git'.DIRECTORY_SEPARATOR.'hooks';
        if (!is_dir($gitHookFolder)) {
            mkdir($gitHookFolder);
        }
        $destination = $gitHookFolder.DIRECTORY_SEPARATOR.'pre-commit';
        
        copy($source, $destination);
        chmod($destination, 0777);
    }

    /**
     * @param $rootPath
     */
    public static function phpcsXmlCopy($rootPath) :void
    {
        $source = $rootPath.'vendor'.DIRECTORY_SEPARATOR.'2latlantik'.DIRECTORY_SEPARATOR.'symfony-my-cs'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'phpcs-rules'.DIRECTORY_SEPARATOR.'phpcs.xml';
        $destination = $rootPath.'phpcs.xml';

        copy($source, $destination);
    }

    /**
     * @param $rootPath
     */
    public static function phpmdXmlCopy($rootPath) :void
    {
        $source = $rootPath.'vendor'.DIRECTORY_SEPARATOR.'2latlantik'.DIRECTORY_SEPARATOR.'symfony-my-cs'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'phpmd-rules'.DIRECTORY_SEPARATOR.'phpmd.xml';
        $destination = $rootPath.'phpmd.xml';

        copy($source, $destination);
    }

    /**
     * @return bool|string
     */
    public static function getRootPath()
    {
        $rootPath = __DIR__;
        for ($i = 1; $i <= 6; $i++) {
            $rootPath .= DIRECTORY_SEPARATOR . '..';
        }
        return realpath($rootPath);
    }
}
