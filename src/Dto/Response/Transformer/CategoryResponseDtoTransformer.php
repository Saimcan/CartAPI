<?php

declare(strict_types = 1);

namespace App\Dto\Response\Transformer;

use App\Dto\Exception\UnexpectedTypeException;
use App\Entity\Category;
use App\Dto\Response\CategoryResponseDto;

class CategoryResponseDtoTransformer extends AbstractResponseDtoTransformer
{

    /**
     * @param Category $category
     */
    public function transformFromObject($category): CategoryResponseDto
    {
        if(!$category instanceof Category){
            throw new UnexpectedTypeException('Excepted type of Category but got '. \get_class($category));
        }

        $dto = new CategoryResponseDto();
        $dto->name = $category->getName();

        return $dto;
    }
}
