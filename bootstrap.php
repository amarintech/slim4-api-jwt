<?php
/**
 * 這支檔案 for public/index.php 使用
 */

// bootstrap.php
use Doctrine\Common\Cache\ApcuCache;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
//use Doctrine\ORM\EntityRepository;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src/Entity"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
// Use Query Cache - ApcuCache
$config->setQueryCacheImpl(new ApcuCache());
// Use Result Cache - ApcuCache
//$config->setResultCacheImpl(new ApcuCache());

// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$dbParams = array(
    'host' => 'localhost',
    'driver' => $systemConfig['db']['driver'],
    'path' => __DIR__ . $systemConfig['db']['path']
);

// obtaining the entity manager
$entityManager = EntityManager::create($dbParams, $config);