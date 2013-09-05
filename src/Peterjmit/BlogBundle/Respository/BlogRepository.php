<?php

namespace Peterjmit\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class BlogRepository extends EntityRepository
{
    public function __construct($em)
    {
        $entityName = 'Peterjmit\BlogBundle\Model\Blog';
        $class = new ClassMetadata($entityName);

        parent::__construct($em, $class);
    }
}