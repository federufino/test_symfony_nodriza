<?php

namespace App\Controller;

use App\Entity\Planet;
use App\JsonApi\Document\Planet\PlanetDocument;
use App\JsonApi\Document\Planet\PlanetsDocument;
use App\JsonApi\Hydrator\Planet\CreatePlanetHydrator;
use App\JsonApi\Hydrator\Planet\UpdatePlanetHydrator;
use App\JsonApi\Transformer\PlanetResourceTransformer;
use App\Repository\PlanetRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class PlanetController extends Controller
{

    /**
     * @Route("/planets", name="planets_index", methods="GET")
     */
    public function index(PlanetRepository $planetRepository, ResourceCollection $resourceCollection): Response
    {
        $resourceCollection->setRepository($planetRepository);

        $resourceCollection->handleIndexRequest();

        return $this->respondOk(
            new PlanetsDocument(new PlanetResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/planet/", name="planet_new", methods="POST")
     */
    public function new(): Response
    {
        $planet = $this->jsonApi()->hydrate(
            new CreatePlanetHydrator($this->entityManager, $this->jsonApi()->getExceptionFactory()),
            new Planet()
        );

        $this->validate($planet);

        $this->entityManager->persist($planet);
        $this->entityManager->flush();

        return $this->respondOk(
            new PlanetDocument(new PlanetResourceTransformer()),
            $planet
        );
    }

    /**
     * @Route("/planets/{idPlanet}", name="planets_show", methods="GET")
     */
    public function show(string $idPlanet): Response
    {

        try {
            
            $idPlanetInt = (int) $idPlanet;
            $validator = Validation::createValidator();
            $violations = $validator->validate($idPlanetInt, [
                new NotNull(),
                new Type('int'),
                new Positive()
            ]);

            if (0 !== count($violations)) {
                // there are errors
                $arrayErrors = array();
                foreach ($violations as $violation) {
                    array_push($arrayErrors,$violation->getMessage());
                }
                // Response with the errors

                return new JsonResponse([
                    'error' => $arrayErrors
                ], Response::HTTP_BAD_REQUEST);
            }

            // Get remote data
            $url = 'https://swapi.dev/api/planets/';
            $urlWithParam = $url . $idPlanetInt;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlWithParam); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            $content = json_decode(curl_exec($ch)); 
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch); 
            if ($statusCode !== 200) {
                return new JsonResponse([], Response::HTTP_NOT_FOUND);
            }

            $planet = new Planet();
            $planet->setId($idPlanetInt)
            ->setName($content->name)
            ->setRotationPeriod($content->rotation_period)
            ->setOrbitalPeriod($content->orbital_period)
            ->setDiameter($content->diameter)
            ->setFilmsCount(count($content->films))
            ->setCreated(new \DateTime($content->created))
            ->setEdited(new \DateTime($content->edited));
            
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];

            $serializer = new Serializer($normalizers, $encoders);
            $json = $serializer->serialize(
                $planet,
                'json'
            );
            return new Response($json);
        } catch (\Throwable $th) {
            return new JsonResponse([
                'error' => array($th->getMessage())
            ], Response::HTTP_PRECONDITION_FAILED);
        }
    }
}
