<?php

# Les données de fixtures sont générées par IA car c'est très long et redondant à faire à la main ...

namespace App\DataFixtures;

use App\Entity\Bac;
use App\Entity\Library;
use App\Entity\Member;
use App\Entity\Vinyl;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Données de l'application : vinyles et bacs.
 * Les membres et leurs bibliothèques sont créés par UserFixtures.
 */
class AppFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * UserFixtures doit être chargée avant : on a besoin de ses références
     * (membres par email, bibliothèques par UserFixtures::XXX_LIBRARY)
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    /**
     * Pochettes des vinyles : titre du vinyle => fichier image dans fixtures/covers/
     * (photos des vinyles d'Antoine, nettoyées de leurs métadonnées)
     * Les vinyles absents de cette liste n'ont pas de pochette.
     */
    private const COVERS = [
        'Thriller' => 'michael-jackson-thriller.jpg',
        'Thriller (maxi)' => 'michael-jackson-thriller-maxi.jpg',
        'Bad' => 'michael-jackson-bad.jpg',
        'Dangerous' => 'michael-jackson-dangerous.jpg',
        'High Voltage' => 'acdc-high-voltage.jpg',
        'Highway to Hell' => 'acdc-highway-to-hell.jpg',
        'Live' => 'acdc-live.jpg',
        'Master of Puppets' => 'metallica-master-of-puppets.jpg',
        'Ride the Lightning' => 'metallica-ride-the-lightning.jpg',
        'Appetite for Destruction' => 'guns-n-roses-appetite-for-destruction.jpg',
        'Led Zeppelin IV' => 'led-zeppelin-iv.jpg',
        'Rammstein' => 'rammstein.jpg',
        "Never Mind the Bollocks, Here's the Sex Pistols" => 'sex-pistols-never-mind-the-bollocks.jpg',
        'Woodstock 1994' => 'green-day-woodstock-1994.jpg',
        'The Kids Are Alright' => 'the-who-the-kids-are-alright.jpg',
        'Battlefield V' => 'battlefield-v.jpg',
        'Star Wars : Les Derniers Jedi' => 'star-wars-the-last-jedi.jpg',
        "C'est la belle nuit de Noël" => 'tino-rossi-belle-nuit-de-noel.jpg',
    ];

    /**
     * Generates initialization data for vinyls
     * [Library reference, title, artist, year, type, label, production country, production year, signed, description]
     * @return \Generator
     */
    private static function vinylGenerator()
    {
        // Antoine
        yield [UserFixtures::ANTOINE_LIBRARY, "Thriller", "Michael Jackson", 1982, "33 tours", "Epic", "États-Unis", 1982, false, "Le best-seller de tous les temps, pressage original."];
        yield [UserFixtures::ANTOINE_LIBRARY, "L.A Woman", "The Doors", 1971, "33 tours", "Elektra", "États-Unis", 1971, false, "Dernier album avec Jim Morrison."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Abbey Road", "The Beatles", 1969, "33 tours", "Apple Records", "Royaume-Uni", 1969, false, "Pochette culte du passage piéton."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Rumours", "Fleetwood Mac", 1977, "33 tours", "Warner Bros.", "États-Unis", 1977, false, null];
        yield [UserFixtures::ANTOINE_LIBRARY, "Nevermind", "Nirvana", 1991, "33 tours", "DGC", "États-Unis", 1991, true, "Exemplaire dédicacé lors d'une exposition."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Random Access Memories", "Daft Punk", 2013, "Double LP", "Columbia", "France", 2013, false, "Double vinyle avec pochette gatefold."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Wish You Were Here", "Pink Floyd", 1975, "33 tours", "Harvest", "Royaume-Uni", 1975, false, null];

        // Rod
        yield [UserFixtures::ROD_LIBRARY, "(What's the story) Morning Glory ?", "Oasis", 1995, "Double LP", "Creation Records", "Royaume-Uni", 1995, false, "Pressage britannique d'origine."];
        yield [UserFixtures::ROD_LIBRARY, "Definitely Maybe", "Oasis", 1994, "33 tours", "Creation Records", "Royaume-Uni", 1994, false, null];
        yield [UserFixtures::ROD_LIBRARY, "OK Computer", "Radiohead", 1997, "Double LP", "Parlophone", "Royaume-Uni", 1997, false, "Réédition 2016."];
        yield [UserFixtures::ROD_LIBRARY, "Is This It", "The Strokes", 2001, "33 tours", "RCA", "États-Unis", 2001, false, null];
        yield [UserFixtures::ROD_LIBRARY, "Whatever People Say I Am, That's What I'm Not", "Arctic Monkeys", 2006, "33 tours", "Domino", "Royaume-Uni", 2006, true, "Signé par le groupe après un concert à Paris."];
        yield [UserFixtures::ROD_LIBRARY, "The Queen Is Dead", "The Smiths", 1986, "33 tours", "Rough Trade", "Royaume-Uni", 1986, false, null];
        yield [UserFixtures::ROD_LIBRARY, "Parklife", "Blur", 1994, "33 tours", "Food Records", "Royaume-Uni", 1994, false, "Quelques craquements sur la face B."];

        // Clara
        yield [UserFixtures::CLARA_LIBRARY, "Kind of Blue", "Miles Davis", 1959, "33 tours", "Columbia", "États-Unis", 1959, false, "Pressage mono, état proche du neuf."];
        yield [UserFixtures::CLARA_LIBRARY, "A Love Supreme", "John Coltrane", 1965, "33 tours", "Impulse!", "États-Unis", 1965, false, null];
        yield [UserFixtures::CLARA_LIBRARY, "Time Out", "The Dave Brubeck Quartet", 1959, "33 tours", "Columbia", "États-Unis", 1959, false, "Contient Take Five."];
        yield [UserFixtures::CLARA_LIBRARY, "Blue Train", "John Coltrane", 1958, "33 tours", "Blue Note", "États-Unis", 1958, false, null];
        yield [UserFixtures::CLARA_LIBRARY, "Ella and Louis", "Ella Fitzgerald & Louis Armstrong", 1956, "33 tours", "Verve", "États-Unis", 1956, false, "Duo mythique."];
        yield [UserFixtures::CLARA_LIBRARY, "Head Hunters", "Herbie Hancock", 1973, "33 tours", "Columbia", "États-Unis", 1973, true, "Signé par Herbie Hancock."];
        yield [UserFixtures::CLARA_LIBRARY, "Getz/Gilberto", "Stan Getz & João Gilberto", 1964, "33 tours", "Verve", "États-Unis", 1964, false, null];

        // Lucas
        yield [UserFixtures::LUCAS_LIBRARY, "To Pimp a Butterfly", "Kendrick Lamar", 2015, "Double LP", "Top Dawg Entertainment", "États-Unis", 2015, false, null];
        yield [UserFixtures::LUCAS_LIBRARY, "Illmatic", "Nas", 1994, "33 tours", "Columbia", "États-Unis", 1994, false, "Réédition colorée."];
        yield [UserFixtures::LUCAS_LIBRARY, "The Low End Theory", "A Tribe Called Quest", 1991, "33 tours", "Jive", "États-Unis", 1991, false, null];
        yield [UserFixtures::LUCAS_LIBRARY, "Dark Side of the Moon", "Pink Floyd", 1973, "33 tours", "Harvest", "Royaume-Uni", 1973, false, "Avec posters et autocollants d'origine."];
        yield [UserFixtures::LUCAS_LIBRARY, "Ready to Die", "The Notorious B.I.G.", 1994, "Double LP", "Bad Boy", "États-Unis", 1994, false, null];
        yield [UserFixtures::LUCAS_LIBRARY, "Discovery", "Daft Punk", 2001, "Double LP", "Virgin", "France", 2001, false, "Premier pressage français."];
        yield [UserFixtures::LUCAS_LIBRARY, "Wu-Tang Forever", "Wu-Tang Clan", 1997, "Double LP", "Loud", "États-Unis", 1997, false, null];

        // Emma
        yield [UserFixtures::EMMA_LIBRARY, "Rubber Soul", "The Beatles", 1965, "33 tours", "Parlophone", "Royaume-Uni", 1965, false, null];
        yield [UserFixtures::EMMA_LIBRARY, "Pet Sounds", "The Beach Boys", 1966, "33 tours", "Capitol", "États-Unis", 1966, false, "Pressage mono."];
        yield [UserFixtures::EMMA_LIBRARY, "Blue", "Joni Mitchell", 1971, "33 tours", "Reprise", "États-Unis", 1971, false, null];
        yield [UserFixtures::EMMA_LIBRARY, "Rid of Me", "PJ Harvey", 1993, "33 tours", "Island", "Royaume-Uni", 1993, true, "Dédicacé à la sortie de la réédition."];
        yield [UserFixtures::EMMA_LIBRARY, "Bossanova", "Pixies", 1990, "33 tours", "4AD", "Royaume-Uni", 1990, false, null];
        yield [UserFixtures::EMMA_LIBRARY, "Debut", "Björk", 1993, "33 tours", "One Little Indian", "Royaume-Uni", 1993, false, "Pochette légèrement usée."];
        yield [UserFixtures::EMMA_LIBRARY, "Tapestry", "Carole King", 1971, "33 tours", "Ode Records", "États-Unis", 1971, false, null];

        // Hugo
        yield [UserFixtures::HUGO_LIBRARY, "Master of Puppets", "Metallica", 1986, "33 tours", "Elektra", "États-Unis", 1986, false, "Pressage européen."];
        yield [UserFixtures::HUGO_LIBRARY, "Paranoid", "Black Sabbath", 1970, "33 tours", "Vertigo", "Royaume-Uni", 1970, false, null];
        yield [UserFixtures::HUGO_LIBRARY, "Back in Black", "AC/DC", 1980, "33 tours", "Atlantic", "Australie", 1980, false, null];
        yield [UserFixtures::HUGO_LIBRARY, "Powerslave", "Iron Maiden", 1984, "33 tours", "EMI", "Royaume-Uni", 1984, true, "Signé par Bruce Dickinson."];
        yield [UserFixtures::HUGO_LIBRARY, "Led Zeppelin IV", "Led Zeppelin", 1971, "33 tours", "Atlantic", "Royaume-Uni", 1971, false, "Pochette sans titre, comme à l'origine."];
        yield [UserFixtures::HUGO_LIBRARY, "Appetite for Destruction", "Guns N' Roses", 1987, "33 tours", "Geffen", "États-Unis", 1987, false, null];
        yield [UserFixtures::HUGO_LIBRARY, "Holy Diver", "Dio", 1983, "33 tours", "Vertigo", "Royaume-Uni", 1983, false, null];

        // Singles
        yield [UserFixtures::ANTOINE_LIBRARY, "Billie Jean", "Michael Jackson", 1983, "45 tours", "Epic", "États-Unis", 1983, false, "Single original."];
        yield [UserFixtures::ROD_LIBRARY, "Wonderwall", "Oasis", 1995, "Maxi 45 tours", "Creation Records", "Royaume-Uni", 1995, false, null];
        yield [UserFixtures::LUCAS_LIBRARY, "Around the World", "Daft Punk", 1997, "Maxi 45 tours", "Virgin", "France", 1997, false, "Maxi avec remixes."];
        yield [UserFixtures::EMMA_LIBRARY, "Hey Jude", "The Beatles", 1968, "45 tours", "Apple Records", "Royaume-Uni", 1968, false, null];

        // Vinyles d'Antoine photographiés (pochettes dans fixtures/covers/)
        yield [UserFixtures::ANTOINE_LIBRARY, "High Voltage", "AC/DC", 1976, "33 tours", "Atlantic", "Royaume-Uni", 1976, false, "Premier album international du groupe."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Highway to Hell", "AC/DC", 1979, "33 tours", "Atlantic", "Royaume-Uni", 1979, false, "Dernier album avec Bon Scott."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Live", "AC/DC", 1992, "Double LP", "Atco", "États-Unis", 1992, false, "Enregistré pendant la tournée The Razors Edge."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Bad", "Michael Jackson", 1987, "33 tours", "Epic", "États-Unis", 1987, false, null];
        yield [UserFixtures::ANTOINE_LIBRARY, "Dangerous", "Michael Jackson", 1991, "Double LP", "Epic", "États-Unis", 1991, false, "Pochette illustrée par Mark Ryden."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Thriller (maxi)", "Michael Jackson", 1983, "Maxi 45 tours", "Epic", "États-Unis", 1983, false, "Version longue du single."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Ride the Lightning", "Metallica", 1984, "33 tours", "Megaforce", "États-Unis", 1984, false, null];
        yield [UserFixtures::ANTOINE_LIBRARY, "Rammstein", "Rammstein", 2019, "Double LP", "Universal", "Allemagne", 2019, false, "L'album sans titre, à la pochette blanche et son allumette."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Never Mind the Bollocks, Here's the Sex Pistols", "Sex Pistols", 1977, "33 tours", "Virgin", "Royaume-Uni", 1977, false, "Le manifeste du punk anglais."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Woodstock 1994", "Green Day", 1994, "33 tours", "Reprise", "États-Unis", 2019, false, "Le concert de Woodstock 94, édité pour le Record Store Day."];
        yield [UserFixtures::ANTOINE_LIBRARY, "The Kids Are Alright", "The Who", 1979, "Double LP", "Polydor", "Royaume-Uni", 1979, false, "Bande originale du documentaire sur le groupe."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Battlefield V", "Johan Söderqvist & Patrik Andrén", 2018, "Picture disc", null, null, 2018, false, "Bande originale du jeu vidéo, en picture disc."];
        yield [UserFixtures::ANTOINE_LIBRARY, "Star Wars : Les Derniers Jedi", "John Williams", 2017, "Double LP", "Walt Disney Records", "États-Unis", 2018, false, "Bande originale de l'épisode VIII."];
        yield [UserFixtures::ANTOINE_LIBRARY, "C'est la belle nuit de Noël", "Tino Rossi", 1946, "33 tours", "Columbia", "France", null, false, "Petit Papa Noël, Douce nuit, Mon beau sapin…"];
    }

    /**
     * Generates initialization data for bacs (collections of vinyls from the owner's library)
     * [Owner email, name, public, description, [vinyl titles]]
     * @return \Generator
     */
    private static function bacGenerator()
    {
        yield ['antoine@tsp.fr', "Classiques des années 70", true, "Les incontournables de la décennie.", ["L.A Woman", "Wish You Were Here", "Abbey Road", "Rumours"]];
        yield ['antoine@tsp.fr', "Singles", false, null, ["Billie Jean"]];
        yield ['antoine@tsp.fr', "Pop & Électro", true, "Du roi de la pop aux robots de la french touch.", ["Thriller", "Random Access Memories", "Billie Jean"]];

        yield ['rod@tsp.fr', "Britpop", true, "Oasis, Blur et compagnie.", ["(What's the story) Morning Glory ?", "Definitely Maybe", "Parklife", "Wonderwall"]];
        yield ['rod@tsp.fr', "Indie des années 2000", true, null, ["Is This It", "Whatever People Say I Am, That's What I'm Not"]];
        yield ['rod@tsp.fr', "À réécouter", false, "Mes albums du moment.", ["OK Computer", "The Queen Is Dead"]];

        yield ['clara@tsp.fr', "Jazz modal", true, "Miles, Coltrane et les autres.", ["Kind of Blue", "A Love Supreme", "Blue Train"]];
        yield ['clara@tsp.fr', "Soirée détente", true, "Pour un dîner entre amis.", ["Getz/Gilberto", "Ella and Louis", "Time Out"]];
        yield ['clara@tsp.fr', "Dédicacés", false, "Pièces signées de ma collection.", ["Head Hunters"]];

        yield ['lucas@tsp.fr', "Hip-hop essentiel", true, "Les classiques du rap américain.", ["Illmatic", "The Low End Theory", "Ready to Die", "Wu-Tang Forever", "To Pimp a Butterfly"]];
        yield ['lucas@tsp.fr', "Électro française", true, null, ["Discovery", "Around the World"]];
        yield ['lucas@tsp.fr', "Rock progressif", false, null, ["Dark Side of the Moon"]];

        yield ['emma@tsp.fr', "Années 60", true, "Beatles et Beach Boys.", ["Rubber Soul", "Pet Sounds", "Hey Jude"]];
        yield ['emma@tsp.fr', "Voix féminines", true, "Joni, PJ, Björk et Carole.", ["Blue", "Rid of Me", "Debut", "Tapestry"]];
        yield ['emma@tsp.fr', "Alternatif", false, null, ["Bossanova", "Rid of Me"]];

        yield ['hugo@tsp.fr', "Heavy metal", true, "Du riff qui fait mal.", ["Master of Puppets", "Paranoid", "Powerslave", "Holy Diver"]];
        yield ['hugo@tsp.fr', "Hard rock", true, null, ["Back in Black", "Led Zeppelin IV", "Appetite for Destruction"]];
        yield ['hugo@tsp.fr', "Coups de coeur", false, "Mes pépites personnelles.", ["Powerslave", "Led Zeppelin IV"]];
    }

    public function load(ObjectManager $manager): void
    {
        $fileSystem = new Filesystem();
        $root = dirname(__DIR__, 2);
        foreach (self::vinylGenerator() as [$library_ref, $title, $artist, $year, $type, $label, $prodCountry, $prodYear, $signed, $description ]) {
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
            $isInCovers = array_key_exists($title, self::COVERS);
            if ($isInCovers) {
                $coverFile = self::COVERS[$title];
                $fileSystem->copy($root . '/fixtures/covers/' . $coverFile, $root . '/public/images/covers/' . $coverFile);
                $vinyl->setImageName($coverFile);
            }
        }
        $manager->flush();

        foreach (self::bacGenerator() as [$ownerEmail, $name, $public, $description, $vinylTitles]) {
            $owner = $this->getReference($ownerEmail, Member::class);
            $library = $owner->getLibrary();

            $bac = new Bac();
            $bac->setName($name);
            $bac->setPublic($public);
            $bac->setDescription($description);
            // Member::addBac() appelle setOwner() : le propriétaire suffit
            $owner->addBac($bac);

            // Les vinyles d'un bac sont cherchés par titre dans la bibliothèque du propriétaire
            foreach ($vinylTitles as $title) {
                $vinyl = $library->getVinyls()->findFirst(fn ($key, Vinyl $v) => $v->getTitle() === $title);
                if (null === $vinyl) {
                    throw new \LogicException(sprintf('Vinyle "%s" introuvable dans la bibliothèque de %s', $title, $ownerEmail));
                }
                $bac->addVinyl($vinyl);
            }
            $manager->persist($bac);
        }
        $manager->flush();
    }
}
