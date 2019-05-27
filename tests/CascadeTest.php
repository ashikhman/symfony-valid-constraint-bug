<?php

use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Symfony\Component\Validator\Tests\Fixtures\Entity;
use Symfony\Component\Validator\Tests\Fixtures\Reference;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Tests\Validator\AbstractTest;

class CascadeTest extends AbstractTest
{
    protected function createValidator(MetadataFactoryInterface $metadataFactory, array $objectInitializers = []): ValidatorInterface
    {
        $translator = new IdentityTranslator();
        $translator->setLocale('en');

        $contextFactory = new ExecutionContextFactory($translator);
        $validatorFactory = new ConstraintValidatorFactory();

        return new RecursiveValidator($contextFactory, $metadataFactory, $validatorFactory, $objectInitializers);
    }

    /**
     * Working as expected on 4.2.5 and 4.2.8
     */
    public function testValidArrayPropertyTypeExecuted(): void
    {
        $entity = new Entity();
        $entity->reference = "invalid";

        $this->metadata->setGroupSequence(new GroupSequence([['First'], 'Entity', ['Last']]));

        $this->metadata->addPropertyConstraint('reference', new Type(['value' => 'array', 'groups' => ['First']]));
        $this->metadata->addPropertyConstraint('reference', new Valid(['groups' => ['Last']]));

        $this->referenceMetadata->addPropertyConstraint('value', new NotBlank());

        $violations = $this->validate($entity);

        $this->assertCount(1, $violations);
        $this->assertSame('reference', $violations->get(0)->getPropertyPath());
        $this->assertSame('This value should be of type array.', $violations->get(0)->getMessage());
    }

    /**
     * Working on 4.2.5, failed on >=4.2.6
     */
    public function testValidArrayPropertyValidExecuted(): void
    {
        $entity = new Entity();
        $entity->reference = [new Reference()];

        $this->metadata->setGroupSequence(new GroupSequence([['First'], 'Entity', ['Last']]));

        $this->metadata->addPropertyConstraint('reference', new Type(['value' => 'array', 'groups' => ['First']]));
        $this->metadata->addPropertyConstraint('reference', new Valid(['groups' => ['Last']]));

        $this->referenceMetadata->addPropertyConstraint('value', new NotBlank());

        $violations = $this->validate($entity);

        $this->assertCount(1, $violations);
        $this->assertSame('reference[0].value', $violations->get(0)->getPropertyPath());
        $this->assertSame('This value should not be blank.', $violations->get(0)->getMessage());
    }
}