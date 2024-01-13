<?php
// src/DataFixtures/SupplementDataFixtures.php
namespace App\DataFixtures;

use App\Entity\Supplement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SupplementDataFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create and persist supplements
        $supplementsData = [
            ['name' => 'Astreinte', 'percentage' => mt_rand(10, 20)],
            ['name' => 'Heures supplementaires', 'percentage' => mt_rand(10, 20)],
            ['name' => 'Deplacement sur place', 'percentage' => mt_rand(10, 20)],
            // Add other supplements if necessary
        ];

        foreach ($supplementsData as $data) {
            $supplement = new Supplement();
            $supplement->setLabel($data['name']);
            $supplement->setPercentage($data['percentage']);

            $manager->persist($supplement);

            // Set the reference for each supplement
            $referenceName = 'supplement_' . strtolower(str_replace(' ', '_', $data['name']));
            $this->addReference($referenceName, $supplement);
        }

        // Flush the manager after all supplements are persisted
        $manager->flush();
    }
}
