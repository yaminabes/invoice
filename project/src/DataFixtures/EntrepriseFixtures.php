<?php

namespace App\DataFixtures;

use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EntrepriseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création d'une entreprise courante
        $entrepriseCurrent = new Entreprise();
        $entrepriseCurrent->setDenominationSociale("Entreprise Courante");
        $entrepriseCurrent->setAdresse("10 Rue de la République, 90000 Belfort");
        $entrepriseCurrent->setAdresseLivraison("10 Rue de la République, 90000 Belfort");
        $entrepriseCurrent->setAdresseFacturation("10 Rue de la République, 90000 Belfort");
        $entrepriseCurrent->setSiren("123456789");
        $entrepriseCurrent->setIsClient(false);
        $entrepriseCurrent->setIsCurrent(true);
        $manager->persist($entrepriseCurrent);

        // Création d'une entreprise cliente
        $entrepriseClient = new Entreprise();
        $entrepriseClient->setDenominationSociale("Entreprise Cliente");
        $entrepriseClient->setAdresse("15 Avenue de la Liberté, 90000 Belfort");
        $entrepriseClient->setAdresseLivraison("15 Avenue de la Liberté, 90000 Belfort");
        $entrepriseClient->setAdresseFacturation("15 Avenue de la Liberté, 90000 Belfort");
        $entrepriseClient->setSiren("987654321");
        $entrepriseClient->setIsClient(true);
        $entrepriseClient->setIsCurrent(false);
        $manager->persist($entrepriseClient);

        $manager->flush();
    }
}