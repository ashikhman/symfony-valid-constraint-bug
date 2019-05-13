<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Tests\Fixtures\Entity;
use Symfony\Component\Validator\Tests\Fixtures\Reference;
use Symfony\Component\Validator\Tests\Validator\AbstractValidatorTest;
use Symfony\Component\Validator\Tests\Validator\RecursiveValidatorTest;

class CascadeTest extends RecursiveValidatorTest
{
    public function testCascade(): void
    {
        $entity = new Entity();
        $entity->reference = new Reference();

        $this->metadata->addPropertyConstraint('reference', new Valid(['groups' => ['Group']]));

        $this->referenceMetadata->addPropertyConstraint('value', new NotBlank());

        $violations = $this->validate($entity, null, 'Group');

        $this->assertCount(1, $violations);
    }
}