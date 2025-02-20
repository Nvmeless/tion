<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\History;
use App\Entity\HistoryContext;
use App\Entity\HistoryContextType;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {}
    public function load(ObjectManager $manager): void
    {
        $entitiesName = [
            "User"
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
    }
}
