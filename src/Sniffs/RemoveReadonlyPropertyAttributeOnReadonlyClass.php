<?php

declare(strict_types=1);

namespace Clinkards\PhpCsFixerConfig\Sniffs;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Tokens;

final class RemoveReadonlyPropertyAttributeOnReadonlyClass extends AbstractFixer
{
    public function getDefinition(): FixerDefinition
    {
        return new FixerDefinition(
            'Remove readonly attribute from properties if the class is readonly.',
            [new CodeSample('<?php readonly class Foo { readonly public int $bar; }')]
        );
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAllTokenKindsFound([T_CLASS, T_READONLY]);
    }

    public function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        $tokensCount = count($tokens);
        for ($index = 0; $index < $tokensCount; ++$index) {
            if ($tokens[$index]->isGivenKind(T_CLASS)) {
                $classIndex = $index;
                $classModifiers = $this->getClassModifiers($tokens, $classIndex);
                if (in_array(T_READONLY, $classModifiers)) {
                    $this->removeReadOnlyFromProperties($tokens, $classIndex);
                }
            }
        }
    }

    private function getClassModifiers(Tokens $tokens, $classIndex): array
    {
        $modifiers = [];
        for ($index = $classIndex - 1; $index > 0; --$index) {
            if ($tokens[$index]->isWhitespace()) {
                continue;
            }
            if ($tokens[$index]->isGivenKind([T_FINAL, T_ABSTRACT, T_READONLY])) {
                $modifiers[] = $tokens[$index]->getId();
            } else {
                break;
            }
        }

        return $modifiers;
    }

    private function removeReadOnlyFromProperties(Tokens $tokens, $classIndex): void
    {
        $classStart = $tokens->getNextTokenOfKind($classIndex, ['{']);
        $classEnd = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, $classStart);

        for ($index = $classStart + 1; $index < $classEnd; ++$index) {
            if ($tokens[$index]->isGivenKind(T_READONLY)) {
                // Check the next token to see if it's whitespace and remove it if so
                if ($tokens[$index + 1]->isWhitespace()) {
                    $tokens->clearAt($index + 1);
                }
                // Clear the 'readonly' token itself
                $tokens->clearAt($index);
            }
        }
    }

    public function getName(): string
    {
        return 'Clinkards/remove_readonly_property';
    }

    public function getPriority(): int
    {
        return 0;
    }

    public function isRisky(): bool
    {
        return false;
    }

    public function supports(\SplFileInfo $file): bool
    {
        return true;
    }
}
