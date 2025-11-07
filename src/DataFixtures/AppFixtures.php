<?php

namespace App\DataFixtures;

use App\Entity\Ecurie;
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
        // Créer un utilisateur ADMIN pour tester
        $admin = new User();
        $admin->setEmail('admin@f1.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Créer un utilisateur normal
        $user = new User();
        $user->setEmail('user@f1.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $manager->persist($user);

        // Créer les moteurs
        $moteurFerrari = new Moteur();
        $moteurFerrari->setMarque('Ferrari');
        $manager->persist($moteurFerrari);

        $moteurHonda = new Moteur();
        $moteurHonda->setMarque('Honda RBPT');
        $manager->persist($moteurHonda);

        $moteurMercedes = new Moteur();
        $moteurMercedes->setMarque('Mercedes');
        $manager->persist($moteurMercedes);

        // Créer les écuries
        $ferrari = new Ecurie();
        $ferrari->setNom('Scuderia Ferrari');
        $ferrari->setMoteur($moteurFerrari);
        $manager->persist($ferrari);

        $redBull = new Ecurie();
        $redBull->setNom('Red Bull Racing');
        $redBull->setMoteur($moteurHonda);
        $manager->persist($redBull);

        $mercedes = new Ecurie();
        $mercedes->setNom('Mercedes-AMG Petronas');
        $mercedes->setMoteur($moteurMercedes);
        $manager->persist($mercedes);

        // Pilotes Ferrari
        $leclerc = new Pilote();
        $leclerc->setPrenom('Charles');
        $leclerc->setNom('Leclerc');
        $leclerc->setPointsLicence(12);
        $leclerc->setDateDebutF1(new \DateTime('2018-03-25'));
        $leclerc->setStatut('titulaire');
        $leclerc->setEcurie($ferrari);
        $manager->persist($leclerc);

        $sainz = new Pilote();
        $sainz->setPrenom('Carlos');
        $sainz->setNom('Sainz');
        $sainz->setPointsLicence(12);
        $sainz->setDateDebutF1(new \DateTime('2015-03-15'));
        $sainz->setStatut('titulaire');
        $sainz->setEcurie($ferrari);
        $manager->persist($sainz);

        $bearman = new Pilote();
        $bearman->setPrenom('Oliver');
        $bearman->setNom('Bearman');
        $bearman->setPointsLicence(12);
        $bearman->setDateDebutF1(new \DateTime('2024-03-02'));
        $bearman->setStatut('reserviste');
        $bearman->setEcurie($ferrari);
        $manager->persist($bearman);

        // Pilotes Red Bull
        $verstappen = new Pilote();
        $verstappen->setPrenom('Max');
        $verstappen->setNom('Verstappen');
        $verstappen->setPointsLicence(12);
        $verstappen->setDateDebutF1(new \DateTime('2015-03-15'));
        $verstappen->setStatut('titulaire');
        $verstappen->setEcurie($redBull);
        $manager->persist($verstappen);

        $perez = new Pilote();
        $perez->setPrenom('Sergio');
        $perez->setNom('Perez');
        $perez->setPointsLicence(12);
        $perez->setDateDebutF1(new \DateTime('2011-03-13'));
        $perez->setStatut('titulaire');
        $perez->setEcurie($redBull);
        $manager->persist($perez);

        $lawson = new Pilote();
        $lawson->setPrenom('Liam');
        $lawson->setNom('Lawson');
        $lawson->setPointsLicence(12);
        $lawson->setDateDebutF1(new \DateTime('2023-10-06'));
        $lawson->setStatut('reserviste');
        $lawson->setEcurie($redBull);
        $manager->persist($lawson);

        // Pilotes Mercedes
        $hamilton = new Pilote();
        $hamilton->setPrenom('Lewis');
        $hamilton->setNom('Hamilton');
        $hamilton->setPointsLicence(12);
        $hamilton->setDateDebutF1(new \DateTime('2007-03-18'));
        $hamilton->setStatut('titulaire');
        $hamilton->setEcurie($mercedes);
        $manager->persist($hamilton);

        $russell = new Pilote();
        $russell->setPrenom('George');
        $russell->setNom('Russell');
        $russell->setPointsLicence(12);
        $russell->setDateDebutF1(new \DateTime('2019-03-17'));
        $russell->setStatut('titulaire');
        $russell->setEcurie($mercedes);
        $manager->persist($russell);

        $antonelli = new Pilote();
        $antonelli->setPrenom('Andrea Kimi');
        $antonelli->setNom('Antonelli');
        $antonelli->setPointsLicence(12);
        $antonelli->setDateDebutF1(new \DateTime('2024-08-30'));
        $antonelli->setStatut('reserviste');
        $antonelli->setEcurie($mercedes);
        $manager->persist($antonelli);

        $manager->flush();
    }
}
