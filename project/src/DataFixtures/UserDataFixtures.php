<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $firstNames = ['John', 'Jane', 'Alice', 'Bob', 'Charlie', 'David', 'Eva', 'Frank', 'Grace', 'Henry'];
        $lastNames = ['Doe', 'Smith', 'Johnson', 'Brown', 'Lee', 'Wilson', 'Evans', 'Moore', 'Wang', 'Baker'];

        $usedFirstNames = [];
        $usedLastNames = [];

        for ($i = 0; $i < 10; $i++) {
            $firstName = $this->getUniqueValue($firstNames, $usedFirstNames);
            $lastName = $this->getUniqueValue($lastNames, $usedLastNames);

            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail('user' . $i . '@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setBasicCost(rand(5000, 50000)); 

            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUniqueValue(array $sourceArray, array &$usedValues)
    {
        do {
            $value = $sourceArray[array_rand($sourceArray)];
        } while (in_array($value, $usedValues));

        $usedValues[] = $value;

        return $value;
    }
}
