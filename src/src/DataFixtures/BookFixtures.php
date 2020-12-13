<?php


namespace App\DataFixtures;


use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $authorRepository = $manager->getRepository(Author::class);

        for ($i = 0; $i <= 10000; $i++) {
            $book = new Book();
            $book->setName('Book '.$i.'|'.'Книга '.$i);
            /** @var $author Author */
            $author = $authorRepository->findOneBy(['id' => rand(1, 9999)]);
            $book->setAuthor($author);

            $manager->persist($book);
        }

        $manager->flush();
    }
}