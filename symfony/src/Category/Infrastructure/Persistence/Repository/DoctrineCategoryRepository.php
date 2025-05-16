<?php

namespace App\Category\Infrastructure\Persistence\Repository;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\Exception\CategoryNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\Criteria\CategoryCriteria;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Infrastructure\Persistence\Entity\DoctrineCategory;
use App\Category\Infrastructure\Persistence\Mapper\CategoryMapper;
use App\User\Domain\ValueObject\UserId;

class DoctrineCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryMapper $mapper
    ) {}

    public function findById(CategoryId $id): ?Category
    {
        $doctrineCategory = $this->em->getRepository(DoctrineCategory::class)->find($id->getUuid());
        if ($doctrineCategory) {
            return $this->mapper->toDomain($doctrineCategory);
        }
        return null;
    }

    /**
     * @return Category[]
     */
    public function findByCriteriaPaginated(CategoryCriteria $criteria, UserId $userId, int $page, int $limit): array
    {
        if ($page <= 0) {
            return [];
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('c')
            ->from(DoctrineCategory::class, 'c')
            ->orderBy('c.iconNumber', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->andWhere('c.userId = :userId')
            ->setParameter('userId', $userId->getUuid())
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if ($criteria->name) {
            $qb->andWhere('c.name LIKE :name')
                ->setParameter('name', ('%' . $criteria->name . '%'));
        }

        if ($criteria->iconNumber) {
            $qb->andWhere('c.iconNumber = :iconNumber')
                ->setParameter('iconNumber', $criteria->iconNumber);
        }

        $doctrineCategories = $qb->getQuery()->getResult();

        if (!$doctrineCategories) {
            return [];
        }

        return array_map(
            callback: fn(DoctrineCategory $doctrineCategory) => $this->mapper->toDomain($doctrineCategory),
            array: $doctrineCategories
        );
    }

    public function save(Category $category): void
    {
        $doctrineCategory = $this->em->getRepository(DoctrineCategory::class)->find($category->getId()->getUuid());
        $this->em->persist($this->mapper->toInfrastructure($category, $doctrineCategory));
        $this->em->flush();
    }

    public function delete(CategoryId $categoryId): void
    {
        $doctrineCategory = $this->em->getRepository(DoctrineCategory::class)->find($categoryId->getUuid());
        $this->em->remove($doctrineCategory);
        $this->em->flush();
    }
}
