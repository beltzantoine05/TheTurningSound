<?php

# Les données de fixtures sont générées par IA car c'est très long et redondant à faire à la main ...

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Bac;
use App\Entity\Vinyl;
use App\Entity\Library;
use App\Entity\Member;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    // defines reference names for instance Library
    private const ANTOINE_LIBRARY = "antoine-inventory";
    private const ROD_LIBRARY = "rod-library";
    private const CLARA_LIBRARY = "clara-library";
    private const LUCAS_LIBRARY = "lucas-library";
    private const EMMA_LIBRARY = "emma-library";
    private const HUGO_LIBRARY = "hugo-library";
    // Symfony Auth system interface
    private UserPasswordHasherInterface $hasher;

    // Add a hasher to the object
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    /**
     * Generates initialization data for members + their library
     * [email, username, password, library title, library reference, biography]
     */
    private static function memberGenerator()
    {
        yield ['antoine@tsp.fr', 'tonio', 'password', "Tony's Library", self::ANTOINE_LIBRARY, "Tombé dans la musique avec le Thriller de mes parents. Je chine en brocante tous les week-ends, du rock des années 70 à la French Touch."];
        yield ['rod@tsp.fr', 'rod', 'password', "Rod's Library", self::ROD_LIBRARY, "Fan inconditionnel de Britpop depuis le lycée. Mon rêve : trouver un pressage original de Definitely Maybe signé par les frères Gallagher."];
        yield ['clara@tsp.fr', 'clara', 'password', "Clara's Crates", self::CLARA_LIBRARY, "Saxophoniste amatrice, je collectionne les grands classiques du jazz des années 50 et 60, de préférence en pressage mono."];
        yield ['lucas@tsp.fr', 'lucas', 'password', "Lucas' Groove Vault", self::LUCAS_LIBRARY, "DJ le week-end, je cherche les classiques du hip-hop américain et de l'électro française pour mes sets."];
        yield ['emma@tsp.fr', 'emma', 'password', "Emma's Record Room", self::EMMA_LIBRARY, null];
        yield ['hugo@tsp.fr', 'hugo', 'password', "Hugo's Collection", self::HUGO_LIBRARY, "Metalleux depuis mon premier concert d'Iron Maiden. Chaque vinyle de ma collection a une histoire de concert derrière lui."];
    }

    /**
     * Generates initialization data for vinyls
     * [Library reference, title, artist, year, type, label, production country, production year, signed, description]
     * @return \Generator
     */
    private static function vinylGenerator()
    {
        // Antoine
        yield [self::ANTOINE_LIBRARY, "Thriller", "Michael Jackson", 1982, "33 tours", "Epic", "États-Unis", 1982, false, "Le best-seller de tous les temps, pressage original."];
        yield [self::ANTOINE_LIBRARY, "L.A Woman", "The Doors", 1971, "33 tours", "Elektra", "États-Unis", 1971, false, "Dernier album avec Jim Morrison."];
        yield [self::ANTOINE_LIBRARY, "Abbey Road", "The Beatles", 1969, "33 tours", "Apple Records", "Royaume-Uni", 1969, false, "Pochette culte du passage piéton."];
        yield [self::ANTOINE_LIBRARY, "Rumours", "Fleetwood Mac", 1977, "33 tours", "Warner Bros.", "États-Unis", 1977, false, null];
        yield [self::ANTOINE_LIBRARY, "Nevermind", "Nirvana", 1991, "33 tours", "DGC", "États-Unis", 1991, true, "Exemplaire dédicacé lors d'une exposition."];
        yield [self::ANTOINE_LIBRARY, "Random Access Memories", "Daft Punk", 2013, "Double LP", "Columbia", "France", 2013, false, "Double vinyle avec pochette gatefold."];
        yield [self::ANTOINE_LIBRARY, "Wish You Were Here", "Pink Floyd", 1975, "33 tours", "Harvest", "Royaume-Uni", 1975, false, null];

        // Rod
        yield [self::ROD_LIBRARY, "(What's the story) Morning Glory ?", "Oasis", 1995, "Double LP", "Creation Records", "Royaume-Uni", 1995, false, "Pressage britannique d'origine."];
        yield [self::ROD_LIBRARY, "Definitely Maybe", "Oasis", 1994, "33 tours", "Creation Records", "Royaume-Uni", 1994, false, null];
        yield [self::ROD_LIBRARY, "OK Computer", "Radiohead", 1997, "Double LP", "Parlophone", "Royaume-Uni", 1997, false, "Réédition 2016."];
        yield [self::ROD_LIBRARY, "Is This It", "The Strokes", 2001, "33 tours", "RCA", "États-Unis", 2001, false, null];
        yield [self::ROD_LIBRARY, "Whatever People Say I Am, That's What I'm Not", "Arctic Monkeys", 2006, "33 tours", "Domino", "Royaume-Uni", 2006, true, "Signé par le groupe après un concert à Paris."];
        yield [self::ROD_LIBRARY, "The Queen Is Dead", "The Smiths", 1986, "33 tours", "Rough Trade", "Royaume-Uni", 1986, false, null];
        yield [self::ROD_LIBRARY, "Parklife", "Blur", 1994, "33 tours", "Food Records", "Royaume-Uni", 1994, false, "Quelques craquements sur la face B."];

        // Clara
        yield [self::CLARA_LIBRARY, "Kind of Blue", "Miles Davis", 1959, "33 tours", "Columbia", "États-Unis", 1959, false, "Pressage mono, état proche du neuf."];
        yield [self::CLARA_LIBRARY, "A Love Supreme", "John Coltrane", 1965, "33 tours", "Impulse!", "États-Unis", 1965, false, null];
        yield [self::CLARA_LIBRARY, "Time Out", "The Dave Brubeck Quartet", 1959, "33 tours", "Columbia", "États-Unis", 1959, false, "Contient Take Five."];
        yield [self::CLARA_LIBRARY, "Blue Train", "John Coltrane", 1958, "33 tours", "Blue Note", "États-Unis", 1958, false, null];
        yield [self::CLARA_LIBRARY, "Ella and Louis", "Ella Fitzgerald & Louis Armstrong", 1956, "33 tours", "Verve", "États-Unis", 1956, false, "Duo mythique."];
        yield [self::CLARA_LIBRARY, "Head Hunters", "Herbie Hancock", 1973, "33 tours", "Columbia", "États-Unis", 1973, true, "Signé par Herbie Hancock."];
        yield [self::CLARA_LIBRARY, "Getz/Gilberto", "Stan Getz & João Gilberto", 1964, "33 tours", "Verve", "États-Unis", 1964, false, null];

        // Lucas
        yield [self::LUCAS_LIBRARY, "To Pimp a Butterfly", "Kendrick Lamar", 2015, "Double LP", "Top Dawg Entertainment", "États-Unis", 2015, false, null];
        yield [self::LUCAS_LIBRARY, "Illmatic", "Nas", 1994, "33 tours", "Columbia", "États-Unis", 1994, false, "Réédition colorée."];
        yield [self::LUCAS_LIBRARY, "The Low End Theory", "A Tribe Called Quest", 1991, "33 tours", "Jive", "États-Unis", 1991, false, null];
        yield [self::LUCAS_LIBRARY, "Dark Side of the Moon", "Pink Floyd", 1973, "33 tours", "Harvest", "Royaume-Uni", 1973, false, "Avec posters et autocollants d'origine."];
        yield [self::LUCAS_LIBRARY, "Ready to Die", "The Notorious B.I.G.", 1994, "Double LP", "Bad Boy", "États-Unis", 1994, false, null];
        yield [self::LUCAS_LIBRARY, "Discovery", "Daft Punk", 2001, "Double LP", "Virgin", "France", 2001, false, "Premier pressage français."];
        yield [self::LUCAS_LIBRARY, "Wu-Tang Forever", "Wu-Tang Clan", 1997, "Double LP", "Loud", "États-Unis", 1997, false, null];

        // Emma
        yield [self::EMMA_LIBRARY, "Rubber Soul", "The Beatles", 1965, "33 tours", "Parlophone", "Royaume-Uni", 1965, false, null];
        yield [self::EMMA_LIBRARY, "Pet Sounds", "The Beach Boys", 1966, "33 tours", "Capitol", "États-Unis", 1966, false, "Pressage mono."];
        yield [self::EMMA_LIBRARY, "Blue", "Joni Mitchell", 1971, "33 tours", "Reprise", "États-Unis", 1971, false, null];
        yield [self::EMMA_LIBRARY, "Rid of Me", "PJ Harvey", 1993, "33 tours", "Island", "Royaume-Uni", 1993, true, "Dédicacé à la sortie de la réédition."];
        yield [self::EMMA_LIBRARY, "Bossanova", "Pixies", 1990, "33 tours", "4AD", "Royaume-Uni", 1990, false, null];
        yield [self::EMMA_LIBRARY, "Debut", "Björk", 1993, "33 tours", "One Little Indian", "Royaume-Uni", 1993, false, "Pochette légèrement usée."];
        yield [self::EMMA_LIBRARY, "Tapestry", "Carole King", 1971, "33 tours", "Ode Records", "États-Unis", 1971, false, null];

        // Hugo
        yield [self::HUGO_LIBRARY, "Master of Puppets", "Metallica", 1986, "33 tours", "Elektra", "États-Unis", 1986, false, "Pressage européen."];
        yield [self::HUGO_LIBRARY, "Paranoid", "Black Sabbath", 1970, "33 tours", "Vertigo", "Royaume-Uni", 1970, false, null];
        yield [self::HUGO_LIBRARY, "Back in Black", "AC/DC", 1980, "33 tours", "Atlantic", "Australie", 1980, false, null];
        yield [self::HUGO_LIBRARY, "Powerslave", "Iron Maiden", 1984, "33 tours", "EMI", "Royaume-Uni", 1984, true, "Signé par Bruce Dickinson."];
        yield [self::HUGO_LIBRARY, "Led Zeppelin IV", "Led Zeppelin", 1971, "33 tours", "Atlantic", "Royaume-Uni", 1971, false, "Pochette sans titre, comme à l'origine."];
        yield [self::HUGO_LIBRARY, "Appetite for Destruction", "Guns N' Roses", 1987, "33 tours", "Geffen", "États-Unis", 1987, false, null];
        yield [self::HUGO_LIBRARY, "Holy Diver", "Dio", 1983, "33 tours", "Vertigo", "Royaume-Uni", 1983, false, null];

        // Singles
        yield [self::ANTOINE_LIBRARY, "Billie Jean", "Michael Jackson", 1983, "45 tours", "Epic", "États-Unis", 1983, false, "Single original."];
        yield [self::ROD_LIBRARY, "Wonderwall", "Oasis", 1995, "Maxi 45 tours", "Creation Records", "Royaume-Uni", 1995, false, null];
        yield [self::LUCAS_LIBRARY, "Around the World", "Daft Punk", 1997, "Maxi 45 tours", "Virgin", "France", 1997, false, "Maxi avec remixes."];
        yield [self::EMMA_LIBRARY, "Hey Jude", "The Beatles", 1968, "45 tours", "Apple Records", "Royaume-Uni", 1968, false, null];
    }

    /**
     * Generates initialization data for bacs (collections of vinyls from a same library)
     * [Owner email, library reference, name, public, description, [vinyl titles]]
     * @return \Generator
     */
    private static function bacGenerator()
    {
        yield ['antoine@tsp.fr', self::ANTOINE_LIBRARY, "Classiques des années 70", true, "Les incontournables de la décennie.", ["L.A Woman", "Wish You Were Here", "Abbey Road", "Rumours"]];
        yield ['antoine@tsp.fr', self::ANTOINE_LIBRARY, "Singles", false, null, ["Billie Jean"]];
        yield ['antoine@tsp.fr', self::ANTOINE_LIBRARY, "Pop & Électro", true, "Du roi de la pop aux robots de la french touch.", ["Thriller", "Random Access Memories", "Billie Jean"]];

        yield ['rod@tsp.fr', self::ROD_LIBRARY, "Britpop", true, "Oasis, Blur et compagnie.", ["(What's the story) Morning Glory ?", "Definitely Maybe", "Parklife", "Wonderwall"]];
        yield ['rod@tsp.fr', self::ROD_LIBRARY, "Indie des années 2000", true, null, ["Is This It", "Whatever People Say I Am, That's What I'm Not"]];
        yield ['rod@tsp.fr', self::ROD_LIBRARY, "À réécouter", false, "Mes albums du moment.", ["OK Computer", "The Queen Is Dead"]];

        yield ['clara@tsp.fr', self::CLARA_LIBRARY, "Jazz modal", true, "Miles, Coltrane et les autres.", ["Kind of Blue", "A Love Supreme", "Blue Train"]];
        yield ['clara@tsp.fr', self::CLARA_LIBRARY, "Soirée détente", true, "Pour un dîner entre amis.", ["Getz/Gilberto", "Ella and Louis", "Time Out"]];
        yield ['clara@tsp.fr', self::CLARA_LIBRARY, "Dédicacés", false, "Pièces signées de ma collection.", ["Head Hunters"]];

        yield ['lucas@tsp.fr', self::LUCAS_LIBRARY, "Hip-hop essentiel", true, "Les classiques du rap américain.", ["Illmatic", "The Low End Theory", "Ready to Die", "Wu-Tang Forever", "To Pimp a Butterfly"]];
        yield ['lucas@tsp.fr', self::LUCAS_LIBRARY, "Électro française", true, null, ["Discovery", "Around the World"]];
        yield ['lucas@tsp.fr', self::LUCAS_LIBRARY, "Rock progressif", false, null, ["Dark Side of the Moon"]];

        yield ['emma@tsp.fr', self::EMMA_LIBRARY, "Années 60", true, "Beatles et Beach Boys.", ["Rubber Soul", "Pet Sounds", "Hey Jude"]];
        yield ['emma@tsp.fr', self::EMMA_LIBRARY, "Voix féminines", true, "Joni, PJ, Björk et Carole.", ["Blue", "Rid of Me", "Debut", "Tapestry"]];
        yield ['emma@tsp.fr', self::EMMA_LIBRARY, "Alternatif", false, null, ["Bossanova", "Rid of Me"]];

        yield ['hugo@tsp.fr', self::HUGO_LIBRARY, "Heavy metal", true, "Du riff qui fait mal.", ["Master of Puppets", "Paranoid", "Powerslave", "Holy Diver"]];
        yield ['hugo@tsp.fr', self::HUGO_LIBRARY, "Hard rock", true, null, ["Back in Black", "Led Zeppelin IV", "Appetite for Destruction"]];
        yield ['hugo@tsp.fr', self::HUGO_LIBRARY, "Coups de coeur", false, "Mes pépites personnelles.", ["Powerslave", "Led Zeppelin IV"]];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::memberGenerator() as [$email, $username, $password, $libraryName, $libraryRef, $biography]) {
            $library = new Library();
            $library->setName($libraryName);

            $member = new Member();
            $member->setEmail($email);
            $member->setUsername($username);
            $member->setBiography($biography);
            $member->setPassword($this->hasher->hashPassword($member, $password));
            $member->setLibrary($library); // côté propriétaire, obligatoire

            // Grâce au cascade: ['persist', 'remove'] sur Member::$library,
            // persister $member persiste aussi $library automatiquement
            $manager->persist($member);

            $this->addReference($email, $member);
            $this->addReference($libraryRef, $library);
        }
        $manager->flush();

        // Vinyls indexed by library reference then title, to attach them to bacs
        $vinyls = [];
        foreach (self::vinylGenerator() as [$library_ref, $title, $artist, $year, $type, $label, $prodCountry, $prodYear, $signed, $description]) {
            $library = $this->getReference($library_ref, Library::class);
            $vinyl = new Vinyl();
            $vinyl->setTitle($title);
            $vinyl->setArtist($artist);
            $vinyl->setYear($year);
            $vinyl->setType($type);
            $vinyl->setLabel($label);
            $vinyl->setProdCountry($prodCountry);
            $vinyl->setProdYear($prodYear);
            $vinyl->setSigned($signed);
            $vinyl->setDescription($description);
            $library->addVinyl($vinyl);
            $manager->persist($vinyl);

            $vinyls[$library_ref][$title] = $vinyl;
        }
        $manager->flush();

        foreach (self::bacGenerator() as [$ownerEmail, $library_ref, $name, $public, $description, $vinylTitles]) {
            $owner = $this->getReference($ownerEmail, Member::class);

            $bac = new Bac();
            $bac->setName($name);
            $bac->setPublic($public);
            $bac->setDescription($description);
            // Member::addBac() appelle setOwner() : le propriétaire suffit
            $owner->addBac($bac);

            foreach ($vinylTitles as $title) {
                $bac->addVinyl($vinyls[$library_ref][$title]);
            }
            $manager->persist($bac);
        }
        $manager->flush();
    }
}
