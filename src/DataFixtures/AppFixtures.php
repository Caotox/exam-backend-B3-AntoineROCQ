<?php

namespace App\DataFixtures;

use App\Entity\Ecurie;
use App\Entity\Infraction;
use App\Entity\Moteur;
use App\Entity\Pilote;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin->setEmail('admin@f1.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin, 'admin123'));
        $manager->persist($userAdmin);

        $userNormal = new User();
        $userNormal->setEmail('user@f1.com');
        $userNormal->setRoles(['ROLE_USER']);
        $userNormal->setPassword($this->passwordHasher->hashPassword($userNormal, 'user123'));
        $manager->persist($userNormal);

        $moteurMercedes = new Moteur();
        $moteurMercedes->setMarque('Mercedes-AMG F1 M14');
        $manager->persist($moteurMercedes);

        $moteurFerrari = new Moteur();
        $moteurFerrari->setMarque('Ferrari 066/10');
        $manager->persist($moteurFerrari);

        $moteurHonda = new Moteur();
        $moteurHonda->setMarque('Honda RBPT');
        $manager->persist($moteurHonda);

        $moteurRenault = new Moteur();
        $moteurRenault->setMarque('Renault E-Tech');
        $manager->persist($moteurRenault);

        $ecurieMercedes = new Ecurie();
        $ecurieMercedes->setNom('Mercedes-AMG Petronas F1 Team');
        $ecurieMercedes->setMoteur($moteurMercedes);
        $manager->persist($ecurieMercedes);

        $ecurieFerrari = new Ecurie();
        $ecurieFerrari->setNom('Scuderia Ferrari');
        $ecurieFerrari->setMoteur($moteurFerrari);
        $manager->persist($ecurieFerrari);

        $ecurieRedBull = new Ecurie();
        $ecurieRedBull->setNom('Oracle Red Bull Racing');
        $ecurieRedBull->setMoteur($moteurHonda);
        $manager->persist($ecurieRedBull);

        $ecurieAlpine = new Ecurie();
        $ecurieAlpine->setNom('BWT Alpine F1 Team');
        $ecurieAlpine->setMoteur($moteurRenault);
        $manager->persist($ecurieAlpine);

        $pilote1 = new Pilote();
        $pilote1->setPrenom('Lewis');
        $pilote1->setNom('Hamilton');
        $pilote1->setPointsLicence(12);
        $pilote1->setDateDebutF1(new \DateTime('2007-03-18'));
        $pilote1->setStatut('titulaire');
        $pilote1->setEtat('actif');
        $pilote1->setEcurie($ecurieMercedes);
        $manager->persist($pilote1);

        $pilote2 = new Pilote();
        $pilote2->setPrenom('George');
        $pilote2->setNom('Russell');
        $pilote2->setPointsLicence(12);
        $pilote2->setDateDebutF1(new \DateTime('2019-03-17'));
        $pilote2->setStatut('titulaire');
        $pilote2->setEtat('actif');
        $pilote2->setEcurie($ecurieMercedes);
        $manager->persist($pilote2);

        $pilote3 = new Pilote();
        $pilote3->setPrenom('Frederik');
        $pilote3->setNom('Vesti');
        $pilote3->setPointsLicence(12);
        $pilote3->setDateDebutF1(new \DateTime('2023-05-01'));
        $pilote3->setStatut('reserviste');
        $pilote3->setEtat('actif');
        $pilote3->setEcurie($ecurieMercedes);
        $manager->persist($pilote3);

        $pilote4 = new Pilote();
        $pilote4->setPrenom('Charles');
        $pilote4->setNom('Leclerc');
        $pilote4->setPointsLicence(10);
        $pilote4->setDateDebutF1(new \DateTime('2018-03-25'));
        $pilote4->setStatut('titulaire');
        $pilote4->setEtat('actif');
        $pilote4->setEcurie($ecurieFerrari);
        $manager->persist($pilote4);

        $pilote5 = new Pilote();
        $pilote5->setPrenom('Carlos');
        $pilote5->setNom('Sainz');
        $pilote5->setPointsLicence(11);
        $pilote5->setDateDebutF1(new \DateTime('2015-03-15'));
        $pilote5->setStatut('titulaire');
        $pilote5->setEtat('actif');
        $pilote5->setEcurie($ecurieFerrari);
        $manager->persist($pilote5);

        $pilote6 = new Pilote();
        $pilote6->setPrenom('Antonio');
        $pilote6->setNom('Giovinazzi');
        $pilote6->setPointsLicence(12);
        $pilote6->setDateDebutF1(new \DateTime('2017-04-16'));
        $pilote6->setStatut('reserviste');
        $pilote6->setEtat('actif');
        $pilote6->setEcurie($ecurieFerrari);
        $manager->persist($pilote6);

        $pilote7 = new Pilote();
        $pilote7->setPrenom('Max');
        $pilote7->setNom('Verstappen');
        $pilote7->setPointsLicence(9);
        $pilote7->setDateDebutF1(new \DateTime('2015-03-15'));
        $pilote7->setStatut('titulaire');
        $pilote7->setEtat('actif');
        $pilote7->setEcurie($ecurieRedBull);
        $manager->persist($pilote7);

        $pilote8 = new Pilote();
        $pilote8->setPrenom('Sergio');
        $pilote8->setNom('Perez');
        $pilote8->setPointsLicence(10);
        $pilote8->setDateDebutF1(new \DateTime('2011-03-27'));
        $pilote8->setStatut('titulaire');
        $pilote8->setEtat('actif');
        $pilote8->setEcurie($ecurieRedBull);
        $manager->persist($pilote8);

        $pilote9 = new Pilote();
        $pilote9->setPrenom('Daniel');
        $pilote9->setNom('Ricciardo');
        $pilote9->setPointsLicence(12);
        $pilote9->setDateDebutF1(new \DateTime('2011-07-10'));
        $pilote9->setStatut('reserviste');
        $pilote9->setEtat('actif');
        $pilote9->setEcurie($ecurieRedBull);
        $manager->persist($pilote9);

        $pilote10 = new Pilote();
        $pilote10->setPrenom('Pierre');
        $pilote10->setNom('Gasly');
        $pilote10->setPointsLicence(12);
        $pilote10->setDateDebutF1(new \DateTime('2017-10-01'));
        $pilote10->setStatut('titulaire');
        $pilote10->setEtat('actif');
        $pilote10->setEcurie($ecurieAlpine);
        $manager->persist($pilote10);

        $pilote11 = new Pilote();
        $pilote11->setPrenom('Esteban');
        $pilote11->setNom('Ocon');
        $pilote11->setPointsLicence(12);
        $pilote11->setDateDebutF1(new \DateTime('2016-08-28'));
        $pilote11->setStatut('titulaire');
        $pilote11->setEtat('actif');
        $pilote11->setEcurie($ecurieAlpine);
        $manager->persist($pilote11);

        $pilote12 = new Pilote();
        $pilote12->setPrenom('Jack');
        $pilote12->setNom('Doohan');
        $pilote12->setPointsLicence(12);
        $pilote12->setDateDebutF1(new \DateTime('2024-01-15'));
        $pilote12->setStatut('reserviste');
        $pilote12->setEtat('actif');
        $pilote12->setEcurie($ecurieAlpine);
        $manager->persist($pilote12);

        $infraction1 = new Infraction();
        $infraction1->setDescription('Dépassement sous drapeaux jaunes');
        $infraction1->setPenalitePoints(2);
        $infraction1->setAmendeEuros(null);
        $infraction1->setNomCourse('Grand Prix de Monaco');
        $infraction1->setDateInfraction(new \DateTime('2024-05-26'));
        $infraction1->setPilote($pilote4);
        $manager->persist($infraction1);

        $infraction2 = new Infraction();
        $infraction2->setDescription('Contact avec un concurrent');
        $infraction2->setPenalitePoints(3);
        $infraction2->setAmendeEuros('5000.00');
        $infraction2->setNomCourse('Grand Prix de Silverstone');
        $infraction2->setDateInfraction(new \DateTime('2024-07-07'));
        $infraction2->setPilote($pilote7);
        $manager->persist($infraction2);

        $infraction3 = new Infraction();
        $infraction3->setDescription('Dépassement de la limite de vitesse dans les stands');
        $infraction3->setPenalitePoints(null);
        $infraction3->setAmendeEuros('10000.00');
        $infraction3->setNomCourse('Grand Prix de Spa-Francorchamps');
        $infraction3->setDateInfraction(new \DateTime('2024-07-28'));
        $infraction3->setEcurie($ecurieFerrari);
        $manager->persist($infraction3);

        $infraction4 = new Infraction();
        $infraction4->setDescription('Non-respect des consignes de sécurité');
        $infraction4->setPenalitePoints(1);
        $infraction4->setAmendeEuros(null);
        $infraction4->setNomCourse('Grand Prix de Monza');
        $infraction4->setDateInfraction(new \DateTime('2024-09-01'));
        $infraction4->setPilote($pilote5);
        $manager->persist($infraction4);

        $manager->flush();
    }
}
