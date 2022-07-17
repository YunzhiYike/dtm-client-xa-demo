<?php

namespace App\Client;

use Grpc\Book;
use Grpc\Reply;
use Hyperf\GrpcClient\BaseClient;

class HiClient extends BaseClient
{
    public function transIn(Book $book)
    {
        return $this->_simpleRequest(
            '/grpc.hi/transIn',
            $book,
            [Reply::class, 'decode']
        );
    }

    public function transOut(Book $book)
    {
        return $this->_simpleRequest(
            '/grpc.hi/transOut',
            $book,
            [Reply::class, 'decode']
        );
    }
}