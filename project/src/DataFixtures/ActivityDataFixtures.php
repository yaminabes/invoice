<?php
// src/DataFixtures/ActivityDataFixtures.php
namespace App\DataFixtures;

use App\Entity\Activity;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActivityDataFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    private $supplementReferences;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->supplementReferences = [
            'supplement_astreinte',
            'supplement_heures_supplementaires',
            'supplement_deplacement_sur_place',
            // Add other supplement references if necessary
        ];
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            // Generate a random number of activities for each user (between 1 and 5, for example)
            $activityCount = mt_rand(1, 5);

            for ($i = 0; $i < 1; $i++) {
                $activity = new Activity();
                $activity->setUser($user);
                $activity->setDate(new \DateTime());
                $activity->setStatus(mt_rand(0, 1)); // 0 or 1 randomly

                // Optionally add supplements for each activity
                $this->addSupplements($activity);

                $manager->persist($activity);
            }
        }

        $manager->flush();
    }

    private function addSupplements(Activity $activity)
    {
        // Pick random supplement references from the array
        $randomSupplementReferences = array_rand(array_flip($this->supplementReferences), mt_rand(1, count($this->supplementReferences)));

        foreach ((array) $randomSupplementReferences as $supplementReference) {
            // Get the reference and add the supplement to the activity
            $supplement = $this->getReference($supplementReference);
            $activity->addSupplement($supplement);
        }
    }

    public function getDependencies()
    {
        return [
            SupplementDataFixtures::class,
        ];
    }
}
