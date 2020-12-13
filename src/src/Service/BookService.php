<?php


namespace App\Service;


use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BookService
 * @package App\Service
 */
class BookService
{
    /**
     * @param Request $request
     * @param AuthorRepository $authorRepository
     * @param EntityManagerInterface $em
     * @return Book
     */
    public function createEntity(Request $request, AuthorRepository $authorRepository, EntityManagerInterface $em): Book
    {
        $book = new Book();

        $author = $authorRepository->findOneBy([
            'name' => $request->get('author')
        ]);

        $book->setName($request->get('name'));
        /** @var Author $author */
        $book->setAuthor($author);

        $em->beginTransaction();
        $em->persist($book);
        $em->flush();
        $em->commit();

        return $book;
    }

    /**
     * @param Request $request
     * @param AuthorRepository $authorRepository
     * @param BookRepository $bookRepository
     * @throws \Exception
     */
    public function validateRequest(
        Request $request,
        AuthorRepository $authorRepository,
        BookRepository $bookRepository
    )
    {
        $errors = [];

        if (!$request) {
            $errors[] = 'Bad request';
        }

        if (!$request->get('name')) {
            $errors[] = 'Name is required';
        }

        if (!$request->get('author')) {
            $errors[] = 'Author is required';
        }

        if (
            $request->get('author') &&
            !$authorRepository->findOneBy([
                'name' => $request->get('author')
            ])
        ) {
            $errors[] = 'Author is not found';
        }

        if (
            $request->get('name') &&
            $bookRepository->findOneBy([
                'name' => $request->get('name')
            ])
        ) {
            $errors[] = 'A book with this name has already been created';
        }

        if ($errors) {
            throw new \Exception(implode('|', $errors));
        }
    }

    /**
     * @param Book $book
     * @param string $lang
     * @return Book
     */
    public function getBook(Book $book, string $lang): Book
    {
        $name = explode('|', $book->getName());
        $book->setName($lang === 'en' ? $name[0] : $name[1]);

        return $book;
    }
}