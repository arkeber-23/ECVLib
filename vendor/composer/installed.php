<?php return array(
    'root' => array(
        'name' => 'arkeber-23/ecvlib',
        'pretty_version' => '0.0.2.x-dev',
        'version' => '0.0.2.9999999-dev',
        'reference' => 'd04c7109f22e1e87b85a40cf08de1590c60d9491',
        'type' => 'project',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'arkeber-23/ecvlib' => array(
            'pretty_version' => '0.0.2.x-dev',
            'version' => '0.0.2.9999999-dev',
            'reference' => 'd04c7109f22e1e87b85a40cf08de1590c60d9491',
            'type' => 'project',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'monolog/monolog' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'c412c2e0d6c98525e55746294dc40413f6ffebf3',
            'type' => 'library',
            'install_path' => __DIR__ . '/../monolog/monolog',
            'aliases' => array(
                0 => '3.x-dev',
            ),
            'dev_requirement' => false,
        ),
        'psr/log' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'fe5ea303b0887d5caefd3d431c3e61ad47037001',
            'type' => 'library',
            'install_path' => __DIR__ . '/../psr/log',
            'aliases' => array(
                0 => '3.x-dev',
            ),
            'dev_requirement' => false,
        ),
        'psr/log-implementation' => array(
            'dev_requirement' => false,
            'provided' => array(
                0 => '3.0.0',
            ),
        ),
    ),
);
