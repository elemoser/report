<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function book_init(
        ManagerRegistry $doctrine,
        BookRepository $bookRepository
    ): Response
    {
        $predefinedBooks = [
            0 => [
                "ISBN" => "9781408855652",
                "title" => "Harry Potter and the Philosopher's Stone",
                "author" => "J.K. Rowling",
                "image" => ""
            ],
            1 => [
                "ISBN" => "9781408855669",
                "title" => "Harry Potter and the Chamber of Secrets",
                "author" => "J.K. Rowling",
                "image" => ""
            ],
            2 => [
                "ISBN" => "9781408855676",
                "title" => "Harry Potter and the Prisoner of Azkaban",
                "author" => "J.K. Rowling",
                "image" => ""
            ],
            3 => [
                "ISBN" => "9781408855683",
                "title" => "Harry Potter and the Goblet of Fire",
                "author" => "J.K. Rowling",
                "image" => ""
            ],
            4 => [
                "ISBN" => "9781408855690",
                "title" => "Harry Potter and the Order of the Phoenix",
                "author" => "J.K. Rowling",
                "image" => ""
            ],
            5 => [
                "ISBN" => "9781408855706",
                "title" => "Harry Potter and the Half-Blood Prince",
                "author" => "J.K. Rowling",
                "image" => ""
            ],
            6 => [
                "ISBN" => "9781408855713",
                "title" => "Harry Potter and the Deathly Hallows",
                "author" => "J.K. Rowling",
                "image" => ""
            ]
        ];

        $entityManager = $doctrine->getManager();

        foreach ($predefinedBooks as $item) {
            $book = new Book();

            $book->setISBN($item["ISBN"]);
            $book->setTitle($item["title"]);
            $book->setAuthor($item["author"]);
            $book->setImage($item["image"]);
            // save the book
            $entityManager->persist($book);
        }

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $allBooks = $bookRepository
            ->findAll();

        return $this->json($allBooks);
        // return $this->redirectToRoute('library');
    }

    #[Route('/book/reset', name: 'reset_book')]
    public function book_reset(
        BookRepository $bookRepository
    ): Response
    {
        $allBooks = $bookRepository
            ->findAll();

        foreach ($allBooks as $bookObj) {
            $id = $bookObj->getId();
            $book = $bookRepository->find($id);
    
            if ($book) {
                $bookRepository->remove($book, true);
            }
        }

        $allBooks = $bookRepository
        ->findAll();

        return $this->json($allBooks);
    }
}
