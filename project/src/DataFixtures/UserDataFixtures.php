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
        $admin = new User();
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setBasicCost(rand(5000, 50000));
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, 'toor'));
        $manager->persist($admin);

        $responsible1 = new User();
        $responsible1->setFirstName('Yamina');
        $responsible1->setLastName('OUADAH');
        $responsible1->setEmail('yamina@example.com');
        $responsible1->setRoles(['ROLE_RESPONSABLE']);
        $responsible1->setBasicCost(rand(5000, 50000));
        $responsible1->setPassword($this->passwordEncoder->encodePassword($responsible1, 'toor'));
        $manager->persist($responsible1);

        $responsible2 = new User();
        $responsible2->setFirstName('Hajar');
        $responsible2->setLastName('BENNIS');
        $responsible2->setEmail('hajar@example.com');
        $responsible2->setRoles(['ROLE_RESPONSABLE']);
        $responsible2->setBasicCost(rand(5000, 50000));
        $responsible2->setPassword($this->passwordEncoder->encodePassword($responsible2, 'toor'));
        $manager->persist($responsible2);

        $firstNames = ['Yamina', 'Hajar', 'Ridvan', 'Furkan', 'Sofiane', 'David', 'Eva', 'Paul', 'Julie', 'Manon'];
        $lastNames = ['Bouaziz', 'Benchekroun', 'Kerbouche', 'Lefevre', 'Renard', 'Boudjemaa', 'Dupont', 'Moreau', 'Lamrani', 'Bensaïd'];

        $usedFirstNames = [];
        $usedLastNames = [];

        for ($i = 2; $i < 12; $i++) {
            $firstName = $this->getUniqueValue($firstNames, $usedFirstNames);
            $lastName = $this->getUniqueValue($lastNames, $usedLastNames);

            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail('user' . $i . '@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setBasicCost(rand(5000, 50000));
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));

            if (rand(0, 1) == 1) {
                $user->setResponsable($responsible1);
            } else {
                $user->setResponsable($responsible2);
            }

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