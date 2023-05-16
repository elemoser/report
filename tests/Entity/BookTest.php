<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Entity class Book.
 */
class BookTest extends TestCase
{
    /**
     * @var Book $book
     */
    protected $book;

    /**
     * Set up for tests.
     */
    public function setUp(): void
    {
        $this->book = new Book();
    }

    /**
     * Construct object and verify the type.
     */
    public function testCreateObject(): void
    {
        $this->assertInstanceOf("\App\Entity\Book", $this->book);
        $this->assertNull($this->book->getId());
    }

    /**
     * Set book ISBN and test getter method.
     */
    public function testGetAndSetIsbn(): void
    {
        $isbn = strval(random_int(0, 9) * 17);

        $this->book->setISBN($isbn);
        $this->assertEquals($isbn, $this->book->getISBN());
    }

    /**
     * Set book title and test getter method.
     */
    public function testGetAndSetTitle(): void
    {
        $title = "Test";

        $this->book->setTitle($title);
        $this->assertEquals($title, $this->book->getTitle());
    }

    /**
     * Set book author and test getter method.
     */
    public function testGetAndSetAuthor(): void
    {
        $auhtor = "Jane Doe";

        $this->book->setAuthor($auhtor);
        $this->assertEquals($auhtor, $this->book->getAuthor());
    }

    /**
     * Set book author and test getter method.
     */
    public function testGetAndSetImage(): void
    {
        $image = "test.png";

        $this->book->setImage($image);
        $this->assertEquals($image, $this->book->getImage());
    }
}
