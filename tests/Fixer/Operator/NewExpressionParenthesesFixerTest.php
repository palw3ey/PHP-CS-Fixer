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

namespace PhpCsFixer\Tests\Fixer\Operator;

use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

/**
 * @author Valentin Udaltsov <udaltsov.valentin@gmail.com>
 *
 * @internal
 *
 * @covers \PhpCsFixer\Fixer\Operator\NewExpressionParenthesesFixer
 *
 * @extends AbstractFixerTestCase<\PhpCsFixer\Fixer\Operator\NewExpressionParenthesesFixer>
 *
 * @phpstan-import-type _AutogeneratedInputConfiguration from \PhpCsFixer\Fixer\Operator\NewExpressionParenthesesFixer
 */
final class NewExpressionParenthesesFixerTest extends AbstractFixerTestCase
{
    /**
     * @param _AutogeneratedInputConfiguration $configuration
     *
     * @dataProvider provideFixCases
     *
     * @requires PHP 8.4
     */
    public function testFix(string $expected, ?string $input = null, array $configuration = []): void
    {
        $this->fixer->configure($configuration);
        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<int, array{0: non-empty-string, 1?: ?non-empty-string, 2?: _AutogeneratedInputConfiguration}>
     */
    public static function provideFixCases(): iterable
    {
        // anonymous class
        yield ['<?php (new class {});'];

        yield ['<?php ((new class {}))->bar;'];

        yield [
            '<?php new class {}->bar;',
            '<?php (new class {})->bar;',
        ];

        yield [
            '<?php  /**/ new /**/ class /**/ {} /**/ ->bar;',
            '<?php ( /**/ new /**/ class /**/ {} /**/ )->bar;',
        ];

        yield [
            '<?php new class ($x, (1 + 2) / 3) {}->bar;',
            '<?php (new class ($x, (1 + 2) / 3) {})->bar;',
        ];

        yield [
            '<?php new class (new class {}) {}->bar;',
            '<?php (new class (new class {}) {})->bar;',
        ];

        yield [
            '<?php new class ($x, (1 + 2) / 3) extends stdClass implements Throwable {}->bar;',
            '<?php (new class ($x, (1 + 2) / 3) extends stdClass implements Throwable {})->bar;',
        ];

        yield [
            '<?php new class { public function __construct() { new class {}->bar; } }->bar;',
            '<?php (new class { public function __construct() { (new class {})->bar; } })->bar;',
        ];

        yield ['<?php (new class {})->bar;', null, ['use_parentheses' => true]];

        yield [
            '<?php (new class {})->bar;',
            '<?php new class {}->bar;',
            ['use_parentheses' => true],
        ];

        // regular class name
        yield ['<?php (new stdClass());'];

        yield ['<?php ((new stdClass()));'];

        yield [
            '<?php new stdClass()->bar;',
            '<?php (new stdClass())->bar;',
        ];

        yield [
            '<?php new \stdClass()->bar;',
            '<?php (new \stdClass())->bar;',
        ];

        yield [
            '<?php new namespace\Foo()->bar;',
            '<?php (new namespace\Foo())->bar;',
        ];

        yield [
            '<?php new Name\Space\Foo()->bar;',
            '<?php (new Name\Space\Foo())->bar;',
        ];

        yield [
            '<?php new \Name\Space\Foo()->bar;',
            '<?php (new \Name\Space\Foo())->bar;',
        ];

        yield [
            '<?php  /**/ new /**/ \Name\Space\Foo /**/ () /**/ ->bar;',
            '<?php ( /**/ new /**/ \Name\Space\Foo /**/ () /**/ )->bar;',
        ];

        yield [
            '<?php new Foo($x, (1 + 2) / 3)->bar;',
            '<?php (new Foo($x, (1 + 2) / 3))->bar;',
        ];

        yield [
            '<?php new Foo(new Baz()->m())->bar;',
            '<?php (new Foo((new Baz())->m()))->bar;',
        ];

        yield ['<?php (new Foo)->bar;'];

        yield ['<?php (new Foo())->bar;', null, ['use_parentheses' => true]];

        yield [
            '<?php (new Foo())->bar;',
            '<?php new Foo()->bar;',
            ['use_parentheses' => true],
        ];

        // $variable class name
        yield [
            '<?php new $class()->bar;',
            '<?php (new $class())->bar;',
        ];

        yield [
            '<?php  /**/ new /**/ $class /**/ ( /**/ ) /**/ ->bar;',
            '<?php ( /**/ new /**/ $class /**/ ( /**/ ) /**/ )->bar;',
        ];

        yield [
            '<?php new $$var()->bar;',
            '<?php (new $$var())->bar;',
        ];

        yield [
            '<?php new ${"var"}()->bar;',
            '<?php (new ${"var"}())->bar;',
        ];

        yield [
            '<?php new $obj->prop()->bar;',
            '<?php (new $obj->prop())->bar;',
        ];

        yield [
            '<?php new $obj?->prop()->bar;',
            '<?php (new $obj?->prop())->bar;',
        ];

        yield [
            '<?php new $obj::$prop()->bar;',
            '<?php (new $obj::$prop())->bar;',
        ];

        yield [
            '<?php new SomeClass::$prop()->bar;',
            '<?php (new SomeClass::$prop())->bar;',
        ];

        yield [
            '<?php new $obj["x"]()->bar;',
            '<?php (new $obj["x"]())->bar;',
        ];

        yield [
            '<?php new $obj[1][2]()->bar;',
            '<?php (new $obj[1][2]())->bar;',
        ];

        yield ['<?php (new $class)->bar;', null, ['use_parentheses' => true]];

        yield [
            '<?php (new $class())->bar;',
            '<?php new $class()->bar;',
            ['use_parentheses' => true],
        ];

        // (expression) class name
        yield ['<?php (new (foo()));'];

        yield ['<?php ((new (foo())));'];

        yield [
            '<?php new (foo())()->bar;',
            '<?php (new (foo())())->bar;',
        ];

        yield [
            '<?php  /**/ new /**/ ( /**/ foo() /**/ ) /**/ ( /**/ ) /**/ ->bar;',
            '<?php ( /**/ new /**/ ( /**/ foo() /**/ ) /**/ ( /**/ ) /**/ )->bar;',
        ];

        yield ['<?php (new (foo()))->bar;'];

        yield ['<?php (new (foo()))->bar;', null, ['use_parentheses' => true]];

        yield [
            '<?php (new (foo())())->bar;',
            '<?php new (foo())()->bar;',
            ['use_parentheses' => true],
        ];
    }
}
