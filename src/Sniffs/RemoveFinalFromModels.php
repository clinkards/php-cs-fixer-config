<?php

declare(strict_types=1);

namespace Clinkards\PhpCsFixerConfig\Sniffs;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Tokens;

final class RemoveFinalFromModels extends AbstractFixer
{
    public function getDefinition(): FixerDefinition
    {
        return new FixerDefinition(
            'Removes the "final" keyword from classes within namespaces like ...\Domain\Model. This is to solve the Lazy Ghost issue with Doctrine',
            []
        );
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_FINAL);
    }

    public function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        for ($index = 0; $index < $tokens->count(); ++$index) {
            if ($tokens[$index]->isGivenKind(T_NAMESPACE)) {
                $namespace = $this->getNamespace($tokens, $index);

                // Check if the namespace contains \Domain\Model
                if ($this->appliesToNamespace($namespace)) {
                    $this->removeFinalFromClass($tokens);
                }
            }
        }
    }

    private function appliesToNamespace(string $namespace): bool
    {
        if (!preg_match('/\\\\Domain\\\\Model/', $namespace)) {
            return false;
        }

        if (preg_match('/\\\\Domain\\\\Model\\\\Event?/', $namespace)) {
            return false;
        }

        if (preg_match('/\\\\Test?/', $namespace)) {
            return false;
        }

        return true;
    }

    private function getNamespace(Tokens $tokens, int $index): string
    {
        $namespace = '';
        for ($i = $index + 1; $i < $tokens->count(); ++$i) {
            $token = $tokens[$i];
            if ($token->equals(';')) {
                break;
            }
            $namespace .= $token->getContent();
        }

        return trim($namespace);
    }

    private function removeFinalFromClass(Tokens $tokens): void
    {
        for ($index = 0; $index < $tokens->count(); ++$index) {
            if ($tokens[$index]->isGivenKind(T_FINAL)) {
                // Check the next token to see if it's whitespace and remove it if so
                if ($tokens[$index + 1]->isWhitespace()) {
                    $tokens->clearAt($index + 1);
                }
                $tokens->clearAt($index);
            }
        }
    }

    public function getName(): string
    {
        return 'Clinkards/remove_final_from_models';
    }

    public function getPriority(): int
    {
        return 0;
    }

    public function isRisky(): bool
    {
        return false;
    }
}
