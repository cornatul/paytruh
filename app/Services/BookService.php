<?php

namespace App\Services;
use App\Entities\Books;
use CodeIgniter\Files\File;

final class BookService extends File
{
    public function __construct(string $path, bool $checkFile = true)
    {
        parent::__construct($path, $checkFile);
    }

    final public function getBooks(): Books
    {
        if ($this->getSize() > 0) {
            $xml = simplexml_load_file($this->getRealPath());
            $json = json_encode($xml);
            $array = json_decode($json, true);
            return new Books($array['book']);
        }
        return new Books();
    }

    final public function getBook(array $books, string $bookID):array
    {
        if (isset($books[$bookID])) {
            return $books[$bookID];
        }
        return [];
    }

    final public function getAveragePrice(array $books):float
    {
        $total = 0;
        foreach ($books as $book) {
            $total += (float) $book['price'];
        }
        return $total / count($books);
    }

    final public function getAverageBookSize():float
    {
        $books = simplexml_load_file($this->getRealPath());;
        $total = 0;
        foreach ($books as $book) {
            $size = strlen($book->asXML());
            $total += $size;
        }
        return $total / count($books);
    }


    final public function search(array $books, string $query):array
    {
        $results = [];
        foreach ($books as $book) {
            if (stripos($book['title'], $query) !== false || stripos($book['author'], $query) !== false) {
                $results[] = $book;
            }
        }
        return $results;
    }
}