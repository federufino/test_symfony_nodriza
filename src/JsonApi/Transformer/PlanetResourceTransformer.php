<?php

namespace App\JsonApi\Transformer;

use App\Entity\Planet;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Planet Resource Transformer.
 */
class PlanetResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($planet): string
    {
        return 'planets';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($planet): string
    {
        return (string) $planet->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($planet): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($planet): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/planets/'.$this->getId($planet)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($planet): array
    {
        return [
            'id' => function (Planet $planet) {
                return $planet->getId();
            },
            'name' => function (Planet $planet) {
                return $planet->getName();
            },
            'rotation_period' => function (Planet $planet) {
                return $planet->getRotationPeriod();
            },
            'orbital_period' => function (Planet $planet) {
                return $planet->getOrbitalPeriod();
            },
            'diameter' => function (Planet $planet) {
                return $planet->getDiameter();
            },
            'films_count' => function (Planet $planet) {
                return $planet->getFilmsCount();
            },
            'created' => function (Planet $planet) {
                return $planet->getCreated()->format(\DATE_ATOM);
            },
            'edited' => function (Planet $planet) {
                return $planet->getEdited()->format(\DATE_ATOM);
            },
            'url' => function (Planet $planet) {
                return $planet->getUrl();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($planet): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($planet): array
    {
        return [
        ];
    }
}
