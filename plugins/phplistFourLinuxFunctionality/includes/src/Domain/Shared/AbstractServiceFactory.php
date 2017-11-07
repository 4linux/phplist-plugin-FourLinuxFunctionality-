<?php

namespace phplist\FourLinux\Functionality\Domain\Shared;

use phplist\FourLinux\Functionality\Domain\MessageDataService;
use phplist\FourLinux\Functionality\Domain\UserImportService;
use phplist\FourLinux\Functionality\Domain\UserMessageService;
use phplist\FourLinux\Functionality\Infrastructure\DB\DAO\CaixaDAO;
use phplist\FourLinux\Functionality\Infrastructure\DB\DAO\PHPListDAO;
use phplist\FourLinux\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use phplist\FourLinux\Functionality\Infrastructure\Shared\PHPList;

/**
 * Class AbstractServiceFactory
 *
 * @package phplist\FourLinux\Functionality\Domain\Shared
 */
abstract class AbstractServiceFactory
{
    public static function get($clazz)
    {
        static $factories;

        if (!isset($factories)) {
            $factories = [
                MessageDataService::class => function () {
                    $phpList = new PHPList();
                    return new MessageDataService($phpList);
                },
                UserMessageService::class => function () {
                    $phpList = new PHPList();
                    $phpListDAO = AbstractDAOFactory::get(PHPListDAO::class);
                    return new UserMessageService($phpList, $phpListDAO);
                },
                UserImportService::class => function () {
                    $phpListDAO = AbstractDAOFactory::get(PHPListDAO::class);
                    $caixaDAO = AbstractDAOFactory::get(CaixaDAO::class);
                    return new UserImportService($caixaDAO, $phpListDAO);
                },
            ];
        }

        $factory = null;
        if (array_key_exists($clazz, $factories)) {
            $factory = $factories[$clazz];
        }

        return is_callable($factory) ? $factory() : null;
    }
}
