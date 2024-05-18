<?php

namespace App\Application\UseCase\Book\CreateBook;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Domain\Book\ValueObject\Isbn;
use App\Domain\Book\ValueObject\Year;
use App\Infrastructure\Persistence\Entity\Author;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;

class CreateBookCommandHandler implements CommandHandlerInterface
{

    public function __construct(
      private readonly BookRepositoryInterface $repository
    ) {}

    /**
     * @param  CreateBookCommand  $command
     *
     * @throws \App\Domain\Book\Exception\BookException
     * @throws \App\Domain\Based\Exception\InvalidFormat
     */
    public function handle(CommandInterface $command): ?int
    {
        $isbn = Isbn::fromStringToInt($command->getIsbn());

        $this->ensureBookExists($isbn);

        $author = Author::create(
          FirstName::fromString($command->getAuthorFirstName()),
          LastName::fromString($command->getAuthorLastName())
        );

        $book = Book::create(
          title: $command->getTitle(),
          year: Year::fromStringToInt($command->getYear()),
          description: $command->getDescription(),
          imageId: $command->getImageId(),
          genre: $command->getGenres(),
          isbn: $isbn
        );
        $bookAuthor = BookAuthor::create($book, $author);

        $book->addBookAuthor($bookAuthor);
        $author->addBookAuthor($bookAuthor);

        $this->repository->save($bookAuthor);

        return $book->getId();
    }

    private function ensureBookExists(Isbn $isbn): void
    {
        if ($this->repository->isBookExist($isbn)) {
            throw new BookException('Book with this ISBN is already exists');
        }
    }

}