<?php
namespace Application\PlanningPokerBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Util\PropertyPath;

class UniqueInCollectionValidator extends ConstraintValidator
{

    // We keep an array with the previously checked values of the collection
    private $collectionValues = array();

    // validate is new in Symfony 2.1, in Symfony 2.0 use "isValid" (see below)
    public function validate($value, Constraint $constraint)
    {
        // Apply the property path if specified
        if($constraint->propertyPath){
            $propertyPath = new PropertyPath($constraint->propertyPath);
            $value = $propertyPath->getValue($value);
        }

        // Check that the value is not in the array
        if(in_array($value, $this->collectionValues))
            $this->context->addViolation($constraint->message, array());

        // Add the value in the array for next items validation
        $this->collectionValues[] = $value;
    }
}