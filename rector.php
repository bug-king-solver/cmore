<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\Config\RectorConfig;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->fileExtensions(['php', 'phtml']);

    $rectorConfig->bootstrapFiles([
        __DIR__ . '/bootstrap/app.php',
    ]);

    $rectorConfig->autoloadPaths([
        __DIR__ . '/vendor',
    ]);

    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::PHP_82,
        SetList::TYPE_DECLARATION,
        LevelSetList::UP_TO_PHP_82,
    ]);

    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->rule(TypedPropertyFromAssignsRector::class);
    // $rectorConfig->rule(ReturnTypeDeclarationRector::class);

    $rectorConfig->skip([
        SimplifyIfReturnBoolRector::class,
        LongArrayToShortArrayRector::class,
    ]);

    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        // __DIR__ . '/test',
    ]);
};
