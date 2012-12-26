<?php
namespace Application\PlanningPokerBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueInCollection extends Constraint
{
    public $message = 'Duplicates are not allowed';
    // The property path used to check wether objects are equal
    // If none is specified, it will check that objects are equal
    public $propertyPath = null;
}
