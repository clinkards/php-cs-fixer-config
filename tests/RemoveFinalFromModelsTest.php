<?php

declare(strict_types=1);

namespace Clinkards\PhpCsFixerConfig\Tests;

use Clinkards\PhpCsFixerConfig\Sniffs\RemoveFinalFromModels;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;

final class RemoveFinalFromModelsTest extends TestCase
{
    public function testFixesFinalModels()
    {
        $fixer = new RemoveFinalFromModels();
        
        $expected = '<?php namespace Some\Random\Domain\Model; class Foo { public int $bar; }';
        $input = '<?php namespace Some\Random\Domain\Model; final class Foo { public int $bar; }';
        
        $tokens = Tokens::fromCode($input);
        $fixer->fix(new \SplFileInfo(__FILE__), $tokens);

        $this->assertSame($expected, $tokens->generateCode());
    }

    /**
     * @dataProvider invalidProvider
     */
    public function testDoesNotRemoveFinalFromOtherNamespaces(string $class)
    {
        $fixer = new RemoveFinalFromModels();
        
        $tokens = Tokens::fromCode($class);
        $fixer->fix(new \SplFileInfo(__FILE__), $tokens);

        $this->assertSame($class, $tokens->generateCode());
    }

    public static function invalidProvider(): \Generator
    {
        yield 'Generic Namespace' => ['<?php namespace Some\Random; final class Foo { public int $bar; }'];
        yield 'Event Namespace' => ['<?php namespace Stride\Service\Domain\Model\Event; final class Foo { public int $bar; }'];
        yield 'Events Namespace' => ['<?php namespace Stride\Service\Domain\Model\Events; final class Foo { public int $bar; }'];
        yield 'Test Namespace' => ['<?php namespace Stride\Test\Service\Domain\Model; final class Foo { public int $bar; }'];
        yield 'Tests Namespace' => ['<?php namespace Stride\Tests\Service\Domain\Model; final class Foo { public int $bar; }'];
    }
}
