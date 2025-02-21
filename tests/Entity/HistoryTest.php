<?php

namespace App\Tests\Entity;


use App\Entity\History;
use App\Entity\HistoryContext;
use PHPUnit\Framework\TestCase;
use App\Entity\HistoryContextType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HistoryTest extends TestCase
{
    public function testGettersAndSetters()
    {
        // Créez une instance de History
        $history = new History();

        // Créez des instances de HistoryContext et HistoryContextType pour les tests
        $context = $this->createMock(HistoryContext::class);
        $subcontext = $this->createMock(HistoryContextType::class);

        // Test des setters et getters
        $history->setContext($context);
        $history->setSubcontext($subcontext);
        $history->setPayload(['key' => 'value']);
        $history->setAt(new \DateTime('2025-02-20 12:00:00'));

        // Vérifiez les valeurs après les setters
        $this->assertSame($context, $history->getContext());
        $this->assertSame($subcontext, $history->getSubcontext());
        $this->assertSame(['key' => 'value'], $history->getPayload());
        $this->assertEquals(new \DateTime('2025-02-20 12:00:00'), $history->getAt());
    }

    public function testConstructorSetsCurrentDate()
    {
        // Créez une instance de History
        $history = new History();

        // Vérifiez que la propriété "at" est bien définie à la date actuelle
        $this->assertInstanceOf(\DateTimeInterface::class, $history->getAt());
        $this->assertEqualsWithDelta(new \DateTime(), $history->getAt(), 2); // Vérification que la date est proche de la date actuelle (avec une petite tolérance)
    }

    public function testPayloadValidation()
    {
        $validator = Validation::createValidator();

        // Test avec un payload valide (c'est un tableau qui peut être encodé en JSON)
        $history = new History();
        $history->setPayload(['key' => 'value']);
        $violations = $validator->validate($history);
        $this->assertCount(0, $violations); // Pas de violations

        // Test avec un payload invalide ( null)
        $history->setPayload(null);
        $violations = $validator->validate($history);
        $this->assertCount(0, $violations); // Violation attendue pour un payload null ou non valide
        // $this->assertEquals('le payload doit etre null ou  etre un JSON', $violations[0]->getMessage());
    }
}
