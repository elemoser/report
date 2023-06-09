<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/init', name: 'init_book')]
    public function bookInit(
        ManagerRegistry $doctrine,
        BookRepository $bookRepository
    ): Response {
        $predefinedBooks = [
            0 => [
                "ISBN" => "9781408855652",
                "title" => "Harry Potter and the Philosopher's Stone",
                "author" => "J.K. Rowling",
                "image" => "harry-potter-and-the-philosophers-stone.jpg"
            ],
            1 => [
                "ISBN" => "9781408855669",
                "title" => "Harry Potter and the Chamber of Secrets",
                "author" => "J.K. Rowling",
                "image" => "harry-potter-and-the-chamber-of-secrets.jpg"
            ],
            2 => [
                "ISBN" => "9781408855676",
                "title" => "Harry Potter and the Prisoner of Azkaban",
                "author" => "J.K. Rowling",
                "image" => "harry-potter-and-the-prisoner-of-azkaban.jpg"
            ],
            3 => [
                "ISBN" => "9781408855683",
                "title" => "Harry Potter and the Goblet of Fire",
                "author" => "J.K. Rowling",
                "image" => "harry-potter-and-the-goblet-of-fire.jpg"
            ],
            4 => [
                "ISBN" => "9781408855690",
                "title" => "Harry Potter and the Order of the Phoenix",
                "author" => "J.K. Rowling",
                "image" => "harry-potter-and-the-order-of-the-phoenix.jpg"
            ],
            5 => [
                "ISBN" => "9781408855706",
                "title" => "Harry Potter and the Half-Blood Prince",
                "author" => "J.K. Rowling",
                "image" => "harry-potter-and-the-half-blood-prince.jpg"
            ],
            6 => [
                "ISBN" => "9781408855713",
                "title" => "Harry Potter and the Deathly Hallows",
                "author" => "J.K. Rowling",
                "image" => "harry-potter-and-the-deathly-hallows.jpg"
            ]
        ];

        $entityManager = $doctrine->getManager();

        foreach ($predefinedBooks as $item) {
            $bookExists = $bookRepository->findOneBy(
                ['ISBN' => $item["ISBN"]]
            );

            if (!$bookExists) {
                $book = new Book();

                $book->setISBN($item["ISBN"]);
                $book->setTitle($item["title"]);
                $book->setAuthor($item["author"]);
                $book->setImage($item["image"]);
                // save the book
                $entityManager->persist($book);
            }
        }

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        // $allBooks = $bookRepository
        //     ->findAll();

        // return $this->json($allBooks);
        return $this->redirectToRoute('library');
    }

    #[Route('/book/reset', name: 'reset_book')]
    public function bookReset(
        BookRepository $bookRepository
    ): Response {
        $allBooks = $bookRepository
            ->findAll();

        foreach ($allBooks as $bookObj) {
            $idNumber = $bookObj->getId();
            $book = $bookRepository->find($idNumber);

            if ($book) {
                $bookRepository->remove($book, true);
            }
        }

        return $this->redirectToRoute('init_book');
    }

    #[Route('/book/show', name: 'book_show_all')]
    public function showAllBooks(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        $bookArray = [];

        foreach ($books as $book) {
            $bookArray[] = [
                "id" => $book->getId(),
                "ISBN" => $book->getISBN(),
                "title" => $book->getTitle(),
                "author" => $book->getAuthor(),
                "image" => $book->getImage()
            ];
        }

        $data = [];
        $data["books"] = $bookArray;

        return $this->render('book/read_many.html.twig', $data);
    }

    #[Route('/book/show/{idNumber}', name: 'book_by_id')]
    public function showBookById(
        BookRepository $bookRepository,
        int $idNumber
    ): Response {
        $data = [
            "id" => "",
            "ISBN" => "",
            "title" => "",
            "author" => "",
            "image" => ""
        ];

        $book = $bookRepository
            ->find($idNumber);

        if ($book) {
            $data["id"] = $book->getId();
            $data["ISBN"] = $book->getISBN();
            $data["title"] = $book->getTitle();
            $data["author"] = $book->getAuthor();
            $data["image"] = $book->getImage();
        }

        return $this->render('book/read_one.html.twig', $data);
    }

    #[Route('/book/search', name: 'book_search', methods: 'get')]
    public function searchBook(
        Request $request,
        BookRepository $bookRepository
    ): Response {

        $search = $request->query->get('search');

        // var_dump($search);

        $success = $bookRepository->findOneBy(
            ['id' => intval($search)]
        );

        // var_dump($success);

        if (!$success) {
            return $this->redirectToRoute('library');
        }

        return $this->redirectToRoute('book_by_id', ['idNumber' => $search]);
        // return new Response('Search word: '.$search);
    }
}
