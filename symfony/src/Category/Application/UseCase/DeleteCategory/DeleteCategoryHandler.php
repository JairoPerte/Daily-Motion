<?php

namespace App\Category\Application\UseCase\DeleteCategory;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\ValueObject\CategoryId;

class DeleteCategoryHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function __invoke(string $id): Category
    {
        $category = $this->categoryRepository->findById(new CategoryId($id));
        //Aqui se debería comprobar si es el id del usuario correcto con un servicio, en versiones de la aplicación cuando se integre el JWT
        $this->categoryRepository->delete($category);
        return $category;
    }
}
