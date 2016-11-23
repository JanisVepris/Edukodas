<?php

namespace Tests\Edukodas\Bundle\Helper;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\SchemaTool;
use UserBundle\DataFixture\ORM\LoadUserData;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DbHelper
{
    const ALL_SCHEMAS = [
        Users::class,
    ];

    /**
     * @param ContainerInterface $container
     * @param array              $schemaClassNames
     */
    public static function loadSchemas(ContainerInterface $container, array $schemaClassNames = self::ALL_SCHEMAS)
    {
        $connection = $container->get('doctrine.dbal.default_connection');
        $params = $connection->getParams();
        $databaseName = $params['dbname'];
        unset($params['dbname']);
        $connection = DriverManager::getConnection($params);
        $connection->getSchemaManager()->dropAndCreateDatabase($databaseName);

        $manager = $container->get('doctrine.orm.yoda_entity_manager');
        $fixtures = new LoadDefaultData();
        $schemaTool = new SchemaTool($manager);
        $classes = [];
        foreach ($schemaClassNames as $schemaClassName) {
            $classes[] = $manager->getClassMetadata($schemaClassName);
        }
        $schemaTool->createSchema($classes);
        $fixtures->load($manager);
    }
}
