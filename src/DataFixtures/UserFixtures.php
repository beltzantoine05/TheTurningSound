<?php

# Les données de fixtures sont générées par IA car c'est très long et redondant à faire à la main ...

namespace App\DataFixtures;

use App\Entity\Library;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Comptes utilisateurs (membres) et leur bibliothèque.
 * Chargée avant AppFixtures, qui en dépend (voir AppFixtures::getDependencies()).
 */
class UserFixtures extends Fixture
{
    // Noms des références des bibliothèques, utilisés aussi par AppFixtures
    public const ANTOINE_LIBRARY = "antoine-inventory";
    public const ROD_LIBRARY = "rod-library";
    public const CLARA_LIBRARY = "clara-library";
    public const LUCAS_LIBRARY = "lucas-library";
    public const EMMA_LIBRARY = "emma-library";
    public const HUGO_LIBRARY = "hugo-library";

    // Symfony Auth system interface
    private UserPasswordHasherInterface $hasher;

    // Add a hasher to the object
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Generates initialization data for members + their library
     * [email, username, password, roles, library title, library reference, biography]
     * ROLE_USER est ajouté automatiquement par Member::getRoles()
     */
    private static function memberGenerator()
    {
        yield ['antoine@tsp.fr', 'tonio', 'password', ['ROLE_ADMIN'], "Tony's Library", self::ANTOINE_LIBRARY, "Tombé dans la musique avec le Thriller de mes parents. Je chine en brocante tous les week-ends, du rock des années 70 à la French Touch."];
        yield ['rod@tsp.fr', 'rod', 'password', [], "Rod's Library", self::ROD_LIBRARY, "Fan inconditionnel de Britpop depuis le lycée. Mon rêve : trouver un pressage original de Definitely Maybe signé par les frères Gallagher."];
        yield ['clara@tsp.fr', 'clara', 'password', [], "Clara's Crates", self::CLARA_LIBRARY, "Saxophoniste amatrice, je collectionne les grands classiques du jazz des années 50 et 60, de préférence en pressage mono."];
        yield ['lucas@tsp.fr', 'lucas', 'password', [], "Lucas' Groove Vault", self::LUCAS_LIBRARY, "DJ le week-end, je cherche les classiques du hip-hop américain et de l'électro française pour mes sets."];
        yield ['emma@tsp.fr', 'emma', 'password', [], "Emma's Record Room", self::EMMA_LIBRARY, null];
        yield ['hugo@tsp.fr', 'hugo', 'password', [], "Hugo's Collection", self::HUGO_LIBRARY, "Metalleux depuis mon premier concert d'Iron Maiden. Chaque vinyle de ma collection a une histoire de concert derrière lui."];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::memberGenerator() as [$email, $username, $password, $roles, $libraryName, $libraryRef, $biography]) {
            $library = new Library();
            $library->setName($libraryName);

            $member = new Member();
            $member->setEmail($email);
            $member->setUsername($username);
            $member->setRoles($roles);
            $member->setBiography($biography);
            $member->setPassword($this->hasher->hashPassword($member, $password));
            $member->setLibrary($library); // côté propriétaire, obligatoire

            // Grâce au cascade: ['persist', 'remove'] sur Member::$library,
            // persister $member persiste aussi $library automatiquement
            $manager->persist($member);

            // Références réutilisées par AppFixtures
            $this->addReference($email, $member);
            $this->addReference($libraryRef, $library);
        }
        $manager->flush();
    }
}
