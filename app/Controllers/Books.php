<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Services\BookService;
use App\Services\RestPagination;
use CodeIgniter\HTTP\DownloadResponse;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;

final class Books extends ResourceController
{
    private RestPagination $pagination;
    private string $fileLocation = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'books.xml';

    public function __construct()
    {
        $this->pagination = new RestPagination(Services::pager());
    }

    final public function index(): ResponseInterface
    {
        $bookReader = new BookService($this->fileLocation);
        $books = $bookReader->getBooks()->toArray();
        $data = $this->pagination->paginate($books);
        return $this->respond($data, 200);
    }


    final public function show($id = null): ResponseInterface
    {
        $bookReader = new BookService($this->fileLocation);

        $books = $bookReader->getBooks()->toAssociativeArray();

        $book = $bookReader->getBook($books,$id);
        if ($book) {
            return $this->respond([
                'data' => $book,
            ], 200);
        }

        return $this->failNotFound('No book found with id ' . $id);
    }


    final public function statistics():ResponseInterface
    {
        $bookReader = new BookService($this->fileLocation);
        $books = $bookReader->getBooks()->toArray();
        $totalBooks = count($books);
        $averagePrice = $bookReader->getAveragePrice($books);
        $averageSize = $bookReader->getAverageBookSize();

        return $this->respond([
            'data' => [
                'totalBooks' => $totalBooks,
                'averagePrice' => $averagePrice,
                'averageBookSize' => $averageSize,
                'fileSize' => $bookReader->getSize(),
            ],
        ]);
    }


    final public function search(string $query): ResponseInterface
    {
        $bookReader = new BookService($this->fileLocation);
        $books = $bookReader->search($bookReader->getBooks()->toArray(), $query);
        $data = $this->pagination->paginate($books);
        if (count($data['data']) === 0) {
            return $this->failNotFound('No books found with query ' . $query);
        }
        return $this->respond($data, 200);
    }

    final public function download():DownloadResponse
    {
        return $this->response->download($this->fileLocation, null);
    }
}
