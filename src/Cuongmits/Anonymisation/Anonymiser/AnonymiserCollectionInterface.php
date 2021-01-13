<?php

namespace Cuongmits\Anonymisation\Anonymiser;

interface AnonymiserCollectionInterface
{
    public function process(int $id): int;
}
