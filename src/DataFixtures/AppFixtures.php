<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Vinyl;
use App\Entity\Library;
use App\Entity\Member;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    // defines reference names for instance Library
    private const ANTOINE_LIBRARY = "antoine-inventory";
    private const ROD_LIBRARY = "rod-library";
    // Symfony Auth system interface
    private UserPasswordHasherInterface $hasher;

    // Add a hasher to the object
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    /**
     * Generates initialization data for members + their library
     * [email, password, library title, library reference]
     */
    private static function memberGenerator()
    {
        yield ['antoine@tsp.fr', 'tonio', 'password', "Tony's Library", self::ANTOINE_LIBRARY];
        yield ['rod@tsp.fr', 'rod', 'password', "Rod's Library", self::ROD_LIBRARY];
    }

    /**
     * Generates initialization data for libraries : [title, library reference]
     * @return \\Generator
     */
    private static function libraryGenerator()
    {
        yield ["antoine@tsp.fr","Tony's Library", self::ANTOINE_LIBRARY];
        yield ["rod@tsp.fr", "Rod's Library", self::ROD_LIBRARY];
    }

    /**
     * Generates initialization data for vinyls
     * [Library reference, title, artist]
     * @return \\Generator
     */
    private static function vinylGenerator()
    {
        yield [self::ROD_LIBRARY, "(What's the story) Morning Glory ?", "Oasis"];
        yield [self::ANTOINE_LIBRARY, "Thriller", "Michael Jackson"];
        yield [self::ANTOINE_LIBRARY, "L.A Woman", "The Doors"];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::memberGenerator() as [$email, $username, $password, $libraryName, $libraryRef]) {
            $library = new Library();
            $library->setName($libraryName);

            $member = new Member();
            $member->setEmail($email);
            $member->setUsername($username);
            $member->setPassword($this->hasher->hashPassword($member, $password));
            $member->setLibrary($library); // côté propriétaire, obligatoire

            // Grâce au cascade: ['persist', 'remove'] sur Member::$library,
            // persister $member persiste aussi $library automatiquement
            $manager->persist($member);

            $this->addReference($email, $member);
            $this->addReference($libraryRef, $library);
        }
        $manager->flush();

        foreach (self::vinylGenerator() as [$library_ref, $title, $artist]) {
            $library = $this->getReference($library_ref, Library::class);
            $vinyl = new Vinyl();
            $vinyl->setTitle($title);
            $vinyl->setArtist($artist);
            $library->addVinyl($vinyl);
            $manager->persist($vinyl);
        }
        $manager->flush();
    }
}
