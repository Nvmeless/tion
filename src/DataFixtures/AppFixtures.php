<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\History;
use App\Entity\Identity;
use App\Entity\HistoryContext;
use App\Entity\HistoryContextType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {}
    public function load(ObjectManager $manager): void
    {
        $entitiesName = [
            "User",
            "Identity"
        ];
        $actionsName = [
            "generate"
        ];

        $entities = [];
        $actions = [];

        foreach ($entitiesName as  $entityName) {
            $historyContext = new HistoryContext();
            $historyContext->setName($entityName);

            $manager->persist($historyContext);
            $entities[$entityName] = $historyContext;
        }

        foreach ($actionsName as $actionName) {

            $historyContextType  = new HistoryContextType();
            $historyContextType->setName($actionName);

            $manager->persist($historyContextType);
            $actions[$actionName] = $historyContextType;
        }
        $manager->flush();


        $publicUser = new User();
        $publicUser->setRoles(["ROLE_PUBLIC"]);
        $publicUser->setPassword($this->userPasswordHasher->hashPassword($publicUser, "public"));
        $history = new History();
        $history->setContext($entities['User']);
        $history->setSubContext($actions['generate']);
        $manager->persist($history);
        $manager->persist($publicUser);
        $manager->flush();

        $publicIdentity = new Identity();
        $publicIdentity->setEmail("acme@corp.com")
            ->setCreatedBy($publicUser)
            ->setUpdatedBy($publicUser)
            ->setStatus("on");
        $history = new History();
        $history->setContext($entities['Identity']);
        $history->setSubContext($actions['generate']);
        $manager->persist($history);
        $manager->persist($publicIdentity);
        $manager->flush();
    }
}
