<?php

use App\Entities\Books;
use App\Services\BookService;
use App\Services\RestPagination;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use CodeIgniter\Pager\Pager;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\View\RendererInterface;
use CodeIgniter\View\View;

/**
 * @internal
 */
final class BookReaderTest extends CIUnitTestCase
{
    public function testCanReadFile(): void
    {
        $xmlFilePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'books.xml';;
        $bookReader = new BookService($xmlFilePath,true);
        $contents = $bookReader->getBooks();
        $this->assertInstanceOf(BookService::class, $bookReader);
        $this->assertInstanceOf(Books::class, $contents);
        $this->assertIsArray($contents->toArray());
    }

    public function testThrowsExceptionWhenWrongFile(): void
    {
        $this->expectException(FileNotFoundException::class);
        $xmlFilePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'bookz.xml';;
        new BookService($xmlFilePath,true);
    }

    public function testPagination(): void
    {
        $config = new \Config\Pager();
        $configView = new \Config\View();
        $view = new View($configView);

        $pager = $this->getMockBuilder(Pager::class)
            ->setConstructorArgs([$config, $view])
            ->getMock();


        // Set up a sample dataset
        $data = range(1, 20);

        // Set up the Pager mock to return specific values for the test
        $pager->expects($this->once())->method('getCurrentPage')->willReturn(2);

        // Create an instance of the RestPagination class
        $restPagination = new RestPagination($pager);

        // Perform the pagination
        $result = $restPagination->paginate($data);

        // Assert that the result has the expected structure
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pagination', $result);

        // Assert pagination values
        $this->assertCount(10, $result['data']);
        $this->assertEquals(count($data), $result['pagination']['total']);
        $this->assertEquals(10, $result['pagination']['per_page']);
        $this->assertEquals(2, $result['pagination']['current_page']);
        $this->assertEquals(2, $result['pagination']['last_page']);
    }

    public function testSearchReturnsArray(): void
    {
        $fileMock = $this->getMockBuilder(File::class)->disableOriginalConstructor()->getMock();
        $bookReader = new BookService($fileMock, false);
        $result = $bookReader->search([],'someQuery');
        $this->assertIsArray($result);
    }

}

