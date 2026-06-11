<?php

namespace MediaLibrary\Shared\Exceptions;

class NotFoundException extends DomainException
{
    public static function forEntity(string $entity, int|string $id): self
    {
        return new self("{$entity} with ID '{$id}' was not found.");
    }
}
