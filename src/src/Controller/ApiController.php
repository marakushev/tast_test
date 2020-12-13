<?php


namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ApiController
 * @package App\Controller
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @param Request $request
     * @param BookService $bookService
     * @param AuthorRepository $authorRepository
     * @param BookRepository $bookRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @Route({
     *     "en": "/en/book/create",
     *     "ru": "/ru/book/create"
     * }, name="book_create", methods={"POST"})
     */
    public function createBook(
        Request $request,
        BookService $bookService,
        AuthorRepository $authorRepository,
        BookRepository $bookRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        try {
            $bookService->validateRequest($request, $authorRepository, $bookRepository);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => explode('|', $e->getMessage())
            ], 422);
        }

        $bookService->createEntity($request, $authorRepository, $em);

        return new JsonResponse([
            'message' => 'Success create book'
        ]);
    }

    /**
     * @param Request $request
     * @param AuthorRepository $authorRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @Route("/author/create", name="author_create", methods={"POST"})
     */
    public function createAuthor(
        Request $request,
        AuthorRepository $authorRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        try {
            if (!$request){
                throw new \Exception('Bad request');
            }

            if (!$request->get('name')) {
                throw new \Exception('Name is required');
            }

            if (
                $request->get('name') &&
                $authorRepository->findOneBy([
                    'name' => $request->get('name')
                ])
            ) {
                throw new \Exception('An author with this name has already been created');
            }

            $author = new Author();
            $author->setName($request->get('name'));
            $em->persist($author);
            $em->flush();

            return new JsonResponse([
                'message' => 'Success create author'
            ]);
        } catch (\Exception $e){

            return new JsonResponse([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @param BookRepository $bookRepository
     * @param BookService $bookService
     * @return JsonResponse
     * @Route({
     *     "en": "/en/book/{id}",
     *     "ru": "/ru/book/{id}"
     * }, name="book_show", methods={"GET"})
     */
    public function getBook(int $id, Request $request, BookRepository $bookRepository, BookService $bookService): JsonResponse
    {
        $book = $bookRepository->findOneBy(['id' => $id]);

        if (!$book) {
            return new JsonResponse([
                'message' => 'Book not found'
            ], 422);
        }

        /** @var $book Book */
        return new JsonResponse($bookService->getBook($book, $request->getLocale()));
    }

    /**
     * @param string $name
     * @param BookRepository $bookRepository
     * @return JsonResponse
     * @Route("/search/book/{name}", name="search_book", methods={"GET"})
     */
    public function searchBooks(string $name, BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->search($name);

        return new JsonResponse($books);
    }
}