<?php

namespace App\Controller;

use App\Repository\IdentityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/identity', name: 'identity')]

final class IdentityController extends AbstractController
{
    #[Route('/', name: 'getAll')]
    public function getAll(IdentityRepository $repository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        $jsonData = $cache->get("getAll.identity", function ($item) use ($repository, $serializer) {
            $item->tag("identity");
            $items = $repository->findAll();
            return $serializer->serialize($items, 'json');
            // return $serializer->serialize($items, 'json', ['groups' => "identity"]);
        });

        return new JsonResponse($jsonData, JsonResponse::HTTP_OK, [], true);
    }
}
