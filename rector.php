<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector;
use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\CodingStyle\Rector\ClassMethod\ReturnArrayClassMethodToYieldRector;
use Rector\Core\Configuration\Option;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php80\Rector\Class_\DoctrineAnnotationClassToAttributeRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $parameters = $containerConfigurator->parameters();

    // paths to refactor; solid alternative to CLI arguments
    $parameters->set(Option::PATHS, [__DIR__.'/src', __DIR__.'/tests']);

    // do you need to include constants, class aliases or custom autoloader? files listed will be executed
    $parameters->set(Option::BOOTSTRAP_FILES, [
        __DIR__.'/vendor/autoload.php',
    ]);

    // auto import fully qualified class names? [default: false]
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    // skip root namespace classes, like \DateTime or \Exception [default: true]
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    $rules = [
        SetList::FRAMEWORK_EXTRA_BUNDLE_40,
        //SetList::FRAMEWORK_EXTRA_BUNDLE_50,
        SetList::MONOLOG_20,
        SetList::PSR_4,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::PHP_74,
        //SetList::PHP_80,
        SetList::TYPE_DECLARATION,
        //SetList::ORDER,
        //SetList::PRIVATIZATION,

        // Symfony
        //SymfonySetList::SYMFONY_52,
        //SymfonySetList::SYMFONY_50_TYPES,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,

        // Doctrine
        DoctrineSetList::DOCTRINE_DBAL_30,
        DoctrineSetList::DOCTRINE_25,
        DoctrineSetList::DOCTRINE_COMMON_20,
        DoctrineSetList::DOCTRINE_ORM_29,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,

        // PHPUnit
        PHPUnitSetList::PHPUNIT_90,
        PHPUnitSetList::PHPUNIT_91,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::PHPUNIT_EXCEPTION,
        PHPUnitSetList::PHPUNIT_MOCK,
        PHPUnitSetList::PHPUNIT_SPECIFIC_METHOD,
    ];

    foreach ($rules as $rule) {
        $containerConfigurator->import($rule);
    }

    $parameters->set(Option::SKIP, [
        __DIR__.'/src/Domain/POPO/*', // Too much flexibility on parameters type
        __DIR__.'/src/Domain/DTO/*', // Too much flexibility on parameters type
        //__DIR__.'/src/Domain/Entity/*', // Too much flexibility on parameters type
        __DIR__.'/src/Domain/Document/*', // Too much flexibility on parameters type
        __DIR__.'/src/Domain/Model/*', // Too much flexibility on parameters type
        // true === in_array(...)

        SimplifyBoolIdenticalTrueRector::class,
        // DateTime to DateTimeInterface
        DateTimeToDateTimeInterfaceRector::class,
        ReturnArrayClassMethodToYieldRector::class,
    ]);

    // Symfony services
    $parameters->set(
        Option::SYMFONY_CONTAINER_XML_PATH_PARAMETER,
        __DIR__.'/var/cache/dev/srcApp_KernelDevDebugContainer.xml'
    );

    $services
        ->set(DoctrineAnnotationClassToAttributeRector::class)
        ->call('configure', [[
            DoctrineAnnotationClassToAttributeRector::REMOVE_ANNOTATIONS => true,
        ]])
    ;
};
