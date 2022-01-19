<?php

namespace App\JsonApi\Hydrator\Planet;

use App\Entity\Planet;

/**
 * Create Planet Hydrator.
 */
class CreatePlanetHydrator extends AbstractPlanetHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($planet): array
    {
        return [
            'id' => function (Planet $planet, $attribute, $data, $attributeName) {
                $planet->setId($attribute);
            },
            'name' => function (Planet $planet, $attribute, $data, $attributeName) {
                $planet->setName($attribute);
            },
            'rotation_period' => function (Planet $planet, $attribute, $data, $attributeName) {
                $planet->setRotationPeriod($attribute);
            },
            'orbital_period' => function (Planet $planet, $attribute, $data, $attributeName) {
                $planet->setOrbitalPeriod($attribute);
            },
            'diameter' => function (Planet $planet, $attribute, $data, $attributeName) {
                $planet->setDiameter($attribute);
            },
            'films_count' => function (Planet $planet, $attribute, $data, $attributeName) {
                $planet->setFilmsCount($attribute);
            },
            'created' => function (Planet $planet, $attribute, $data, $attributeName) {
                $planet->setCreated(new \DateTime($attribute));
            },
            'edited' => function (Planet $planet, $attribute, $data, $attributeName) {
                $planet->setEdited(new \DateTime($attribute));
            },
        ];
    }
}
