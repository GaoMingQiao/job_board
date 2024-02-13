<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Offer;
use App\Entity\Contact;
use App\Entity\Profile;
use App\Entity\HomeSetting;
use App\Entity\ContractType;
use App\Entity\EntrepriseProfil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $tabImage = [
            'https://source.unsplash.com/random',
            'https://source.unsplash.com/user/wsanter',
            'https://source.unsplash.com/user/erondu',
            'https://source.unsplash.com/random/900×700/?fruit'
        ];
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i <= 5; $i++) {
            $homeSetting = new HomeSetting();
            $homeSetting->setImage($faker->randomElement($tabImage))
                ->setMessage($faker->paragraph())
                ->setCallToAction($faker->word());
            $manager->persist($homeSetting);
        }
        // Insertion des tags
        $tabTags = [
            'PHP',
            'Symfony',
            'Javascript',
            'React',
            'Angular',
            'VueJS',
            'NodeJS',
            'Python',
            'Java',
            'C#',
            'C++',
            'Ruby',
            'HTML',
            'CSS',
            'SQL',
            'NoSQL',
            'MongoDB',
            'MySQL',
            'PostgreSQL',
            'Oracle',
            'MariaDB',
            'SQLite',
            'Git',
            'GitHub',
            'GitLab',
            'BitBucket',
            'Docker',
            'Kubernetes',
            'Linux',
            'Windows',
            'MacOS',
            'Android',
            'iOS',
            'AWS',
            'Azure',
            'Google Cloud',
            'Heroku',
            'Digital Ocean',
            'Vultr',
            'OVH',
            '1&1',
            'GoDaddy',
            'Namecheap'
        ];
        foreach ($tabTags as $tag) {
            $tagEntity =  new Tag();
            $tagEntity->setName($tag);
            $manager->persist($tagEntity);
        }
        $tabContractType = [
            'CDI',
            'CDD',
            'Freelance',
            'Stage',
            'Alternance',
            'Intérim',
        ];
        foreach ($tabContractType as $contractType) {
            $contractTypeEntity = new ContractType();
            $contractTypeEntity->setName($contractType);
            $manager->persist($contractTypeEntity);
        }

        $tabRoles = ['Candidat', 'Professionnel'];

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $userRandomRole = $faker->randomElement($tabRoles);
            $user->setEmail($faker->email());
            $user->setPassword(password_hash('password', PASSWORD_DEFAULT));
            $user->setStatus($userRandomRole); // Correction de la méthode
            if ($userRandomRole == 'Professionnel') {
                $user->setUsername($faker->company());
                $user->setRoles(['ROLE_PRO']);
            } else {
                $user->setUsername($faker->userName());
            }
            $manager->persist($user);
        }

        $manager->flush();



        $users = $manager->getRepository(User::class)->findByStatus('Candidat');
        foreach ($users as $user) {
            $profil = new Profile();
            $profil->setUser($user);
            $profil->setFirstName($faker->firstName());
            $profil->setLastName($faker->lastName());
            $profil->setSlug($faker->slug());
            $profil->setAdresse($faker->streetAddress());
            $profil->setCity($faker->city());
            $profil->setZipCode($faker->postcode());
            $profil->setPhoneNumber($faker->phoneNumber());
            $profil->setCountry($faker->country());
            $profil->setJobSought($faker->jobTitle());
            $profil->setPresentation($faker->paragraph(mt_rand(1, 3)));
            $profil->setAvailability($faker->boolean());
            $profil->setWebsite($faker->url());
            $profil->setPicture('https://api.dicebear.com/7.x/initials/svg?seed=' . $user->getUsername() . '&background=%23fff&color=%23fff');

            $manager->persist($profil);
        }

        //creation des entrepriseProfils
        $entrepriseProfils = $manager->getRepository(User::class)->findByStatus('Professionnel');
        foreach ($entrepriseProfils as $entrepriseProfil) {

            $newEntreprise = new EntrepriseProfil();
            $newEntreprise->setUser($entrepriseProfil)
                ->setSlug($faker->slug())
                ->setAddress($faker->streetAddress())
                ->setCity($faker->city())
                ->setZipCode($faker->postcode())
                ->setPhoneNumber($faker->phoneNumber())
                ->setCountry($faker->country())
                ->setWebsite($faker->url())
                ->setEmail($faker->email())
                ->setDescription($faker->paragraph(mt_rand(1, 3)))
                ->setName($faker->company())
                ->setLogo('https://api.dicebear.com/7.x/initials/svg?seed=' . $newEntreprise->getName() . '&background=%23fff&color=%23fff')
                ->setActivityArea($faker->jobTitle());
            $manager->persist($newEntreprise);
        }

        $manager->flush();
        //création des offres des emploi
        $recruters = $manager->getRepository(EntrepriseProfil::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();
        $contractTypes = $manager->getRepository(ContractType::class)->findAll();
        for ($i = 0; $i <= 10; $i++) {
            $offer = new Offer();
            $offer->setTitle($faker->jobTitle());
            $offer->setShortDescription($faker->word(mt_rand(100, 255)));
            $offer->setContent($faker->paragraph(mt_rand(3, 6)));
            $offer->setSalary(mt_rand(30000, 100000));
            $offer->setLocation($faker->city());
            $offer->setContractType($faker->randomElement($contractTypes));
            $offer->setentrepriseProfil($faker->randomElement($recruters));
            $randomTags = $faker->randomElements($tags, mt_rand(3, 8));
            foreach ($randomTags as $tag) {
                $offer->addTag($tag);
            }
            $manager->persist($offer);
        }
        $user = new User();



        $manager->flush();
    }
}
