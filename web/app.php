<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
$symEnv = getenv('SYMFONY_ENV') ? getenv('SYMFONY_ENV') : 'prod';

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
if($symEnv == 'prod') 
{
    $apcLoader = new ApcClassLoader(sha1(str_replace('/', '_', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])), $loader);
    $loader->unregister();
    $apcLoader->register(true);
}

require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

$isDebug = ($symEnv == 'dev');
$kernel = new AppKernel($symEnv, $isDebug);
$kernel->loadClassCache();
if($kernel->isDebug())
	Debug::enable();

$kernel = new AppCache($kernel);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);