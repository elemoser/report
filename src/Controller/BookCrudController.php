<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookCrudController extends BookController
{
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
        $isbn = strval($request->request->get('isbn'));
        $title = strval($request->request->get('title'));
        $author = strval($request->request->get('author'));
        $image = strval($request->request->get('image'));

        $entityManager = $doctrine->getManager();

        $book = new Book();

        if ($isbn) {
            $book->setISBN($isbn);
        }

        if ($title) {
            $book->setTitle($title);
        }

        if ($author) {
            $book->setAuthor($author);
        }

        if ($image) {
            $book->setImage($image);
        }

        $entityManager->persist($book);

        $entityManager->flush();

        $success = $bookRepository->findOneBy(
            ['ISBN' => $isbn]
        );

        if (!$success) {
            return new Response('Adding book failed.');
        }

        $isbn = $success->getId();

        // return new Response('Adding book succeeded.');
        return $this->redirectToRoute('book_by_id', ['idNumber' => $isbn]);
    }

    #[Route('/book/update/{idNumber}', name: 'book_update')]
    public function updateBook(
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

        return $this->render('book/update.html.twig', $data);
    }

    #[Route('/book/alter', name: 'book_alter', methods:'post')]
    public function alterBook(
        Request $request,
        BookRepository $bookRepository
    ): Response {
        $idNumber = strval($request->request->get("bookId"));
        $isbn = strval($request->request->get('isbn'));
        $title = strval($request->request->get('title'));
        $author = strval($request->request->get('author'));
        $image = strval($request->request->get('image'));

        $book = $bookRepository->find($idNumber);

        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$idNumber
            );
        }

        $book->setISBN($isbn);
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setImage($image);
        $bookRepository->save($book, true);

        return $this->redirectToRoute('book_by_id', ['idNumber' => $idNumber]);
    }

    #[Route('/book/delete/{idNumber}', name: 'book_delete', methods: ['get', 'post'])]
    public function deleteBook(
        Request $request,
        BookRepository $bookRepository,
        int $idNumber
    ): Response {
        $book = $bookRepository
            ->find($idNumber);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$idNumber
            );
        }

        $data = [
            "id" => $book->getId(),
            "ISBN" => $book->getISBN(),
            "title" => $book->getTitle(),
            "author" => $book->getAuthor(),
            "image" => $book->getImage()
        ];

        $bookToDelete = $request->request->get("bookId");

        if ($bookToDelete) {
            $bookRepository->remove($book, true);
            return $this->redirectToRoute('book_show_all');
        }

        return $this->render('book/delete.html.twig', $data);
    }
}
