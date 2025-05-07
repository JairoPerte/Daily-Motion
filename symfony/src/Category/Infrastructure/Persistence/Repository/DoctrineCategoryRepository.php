<?php

namespace App\Category\Infrastructure\Persistence\Repository;

use App\Category\Domain\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\Criteria\CategoryCriteria;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Infrastructure\Persistence\Entity\DoctrineCategory;
use App\Category\Infrastructure\Persistence\Mapper\CategoryCriteriaMapper;
use App\Category\Infrastructure\Persistence\Mapper\CategoryMapper;

class DoctrineCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryMapper $mapper,
        private CategoryCriteriaMapper $criteriaMapper
    ) {}

    public function findById(CategoryId $id): ?Category
    {
        $doctrineCategory = $this->em->getRepository(DoctrineCategory::class)->find($id->getUuid());
        if (!$doctrineCategory) {
            return null;
        }
        return $this->mapper->toDomain($doctrineCategory);
    }

    public function findByCriteria(CategoryCriteria $criteria): ?array
    {
        $doctrineCategories = $this->em->getRepository(DoctrineCategory::class)->findBy($this->criteriaMapper->toArray($criteria));
        if (!$doctrineCategories) {
            return null;
        }
        return array_map(
            callback: fn(DoctrineCategory $doctrineCategory) => $this->mapper->toDomain($doctrineCategory),
            array: $doctrineCategories
        );
    }

    public function save(Category $category): void
    {
        $doctrineCategory = $this->em->getRepository(DoctrineCategory::class)->find($category->getId());
        $this->em->persist($this->mapper->toInfrastructure($category, $doctrineCategory));
        $this->em->flush();
    }

    public function delete(Category $category): void
    {
        $doctrineCategory = $this->em->getRepository(DoctrineCategory::class)->find($category->getId());
        $this->em->remove($doctrineCategory);
        $this->em->flush();
    }
}
