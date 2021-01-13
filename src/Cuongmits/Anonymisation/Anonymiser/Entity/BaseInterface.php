<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

interface BaseInterface
{
    public function process(int $id): int;
    public function getEntityName(): string;
}
