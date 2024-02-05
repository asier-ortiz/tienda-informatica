<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitee43d49038b41b6c944d0f4c27afb839
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Php80\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'P' => 
        array (
            'PhpOption\\' => 10,
        ),
        'G' => 
        array (
            'GrahamCampbell\\ResultType\\' => 26,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
        'B' => 
        array (
            'Backend\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'PhpOption\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption',
        ),
        'GrahamCampbell\\ResultType\\' => 
        array (
            0 => __DIR__ . '/..' . '/graham-campbell/result-type/src',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
        'Backend\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Backend',
        ),
    );

    public static $classMap = array (
        'Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        'ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
        'XMLSchema' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'nusoap_base' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'nusoap_client' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'nusoap_fault' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'nusoap_parser' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'nusoap_server' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'nusoap_wsdlcache' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'nusoap_xmlschema' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'soap_fault' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'soap_parser' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'soap_server' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'soap_transport_http' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'soapclient' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'soapval' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'wsdl' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
        'wsdlcache' => __DIR__ . '/..' . '/econea/nusoap/src/nusoap.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitee43d49038b41b6c944d0f4c27afb839::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitee43d49038b41b6c944d0f4c27afb839::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitee43d49038b41b6c944d0f4c27afb839::$classMap;

        }, null, ClassLoader::class);
    }
}
