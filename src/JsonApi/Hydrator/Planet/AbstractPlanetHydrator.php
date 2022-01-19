<?php

namespace App\JsonApi\Hydrator\Planet;

use App\Entity\Planet;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;

/**
 * Abstract Planet Hydrator.
 */
abstract class AbstractPlanetHydrator extends AbstractHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getClass(): string
    {
        return Planet::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAcceptedTypes(): array
    {
        return ['planets'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($planet): array
    {
        return [
        ];
    }
}
