<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Tests\Fixer\CastNotation;

use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

/**
 * @internal
 *
 * @covers \PhpCsFixer\Fixer\CastNotation\LowercaseCastFixer
 *
 * @extends AbstractFixerTestCase<\PhpCsFixer\Fixer\CastNotation\LowercaseCastFixer>
 */
final class LowercaseCastFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFix(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<int, array{0: non-empty-string, 1?: non-empty-string}>
     */
    public static function provideFixCases(): iterable
    {
        $types = ['boolean', 'bool', 'integer', 'int', 'double', 'float', 'string', 'array', 'object', 'binary'];

        foreach ($types as $from) {
            yield from self::createCasesFor($from);
        }
    }

    /**
     * @dataProvider provideFixPre80Cases
     *
     * @requires PHP <8.0
     */
    public function testFixPre80(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<int, array{0: non-empty-string, 1?: non-empty-string}>
     */
    public static function provideFixPre80Cases(): iterable
    {
        yield from self::createCasesFor('unset');
    }

    /**
     * @dataProvider provideFixDeprecatedCases
     *
     * @group legacy
     *
     * @requires PHP <8.0
     */
    public function testFixDeprecated(string $expected, ?string $input = null): void
    {
        $this->expectDeprecation('The (real) cast is deprecated, use (float) instead');

        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<int, array{0: non-empty-string, 1?: non-empty-string}>
     */
    public static function provideFixDeprecatedCases(): iterable
    {
        return self::createCasesFor('real');
    }

    /**
     * @return iterable<array{0: non-empty-string, 1?: non-empty-string}>
     */
    private static function createCasesFor(string $type): iterable
    {
        yield [
            \sprintf('<?php $b= (%s)$d;', $type),
            \sprintf('<?php $b= (%s)$d;', strtoupper($type)),
        ];

        yield [
            \sprintf('<?php $b=( %s) $d;', $type),
            \sprintf('<?php $b=( %s) $d;', ucfirst($type)),
        ];

        yield [
            \sprintf('<?php $b=(%s ) $d;', $type),
            \sprintf('<?php $b=(%s ) $d;', strtoupper($type)),
        ];

        yield [
            \sprintf('<?php $b=(  %s  ) $d;', $type),
            \sprintf('<?php $b=(  %s  ) $d;', ucfirst($type)),
        ];
    }
}
