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
        ];
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {

            $activity = new Activity();
            $activity->setUser($user);
            $activity->setDate(new \DateTime());
            $activity->setStatus(mt_rand(0, 1));
            $this->addSupplements($activity);

            $manager->persist($activity);
        }

        $manager->flush();
    }

    private function addSupplements(Activity $activity)
    {
        $randomSupplementReferences = array_rand(array_flip($this->supplementReferences), mt_rand(1, count($this->supplementReferences)));

        foreach ((array) $randomSupplementReferences as $supplementReference) {
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
