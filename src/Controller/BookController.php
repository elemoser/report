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

        // $allBooks = $bookRepository
        // ->findAll();

        // return $this->json($allBooks);
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

    #[Route('/book/show/{id}', name: 'book_by_id')]
    public function showPBookById(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);
        
        $data = [
            "id" => $book->getId(),
            "ISBN" => $book->getISBN(),
            "title" => $book->getTitle(),
            "author" => $book->getAuthor(),
            "image" => $book->getImage()
        ];

        return $this->render('book/read_one.html.twig', $data);
    }

    #[Route('/book/add', name: 'book_add')]
    public function addNewBook(): Response
    {
        return $this->render('book/add_one.html.twig');
    }

    #[Route('/book/create', name: 'book_create', methods: 'POST')]
    public function createBook(
        Request $request,
        ManagerRegistry $doctrine,
        BookRepository $bookRepository
    ): Response {
        $data = [
            "isbn" => $request->request->get('isbn'),
            "title" => $request->request->get('title'),
            "author" => $request->request->get('author'),
            "image" => $request->request->get('image')
        ];

        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setISBN($data["isbn"]);
        $book->setTitle($data["title"]);
        $book->setAuthor($data["author"]);

        if ($data["image"]) {
            $book->setImage($data["image"]);
        }

        $entityManager->persist($book);

        $entityManager->flush();

        $success = $bookRepository->findOneBy(
            ['ISBN' => $data["isbn"]]
        );

        if (!$success) {
            return new Response('Adding book failed.');
        }

        $data["id"] = $success->getId();

        // return new Response('Adding book succeeded.');
        return $this->redirectToRoute('book_by_id', ['id' => $data["id"]]);
    }
}
