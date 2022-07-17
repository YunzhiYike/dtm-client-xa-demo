<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace HyperfTest\Cases;

use App\Client\HiClient;
use DtmClient\XA;
use Grpc\Book;
use Grpc\Reply;
use Hyperf\DbConnection\Db;
use HyperfTest\HttpTestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 * @coversNothing
 */
class SjTest extends HttpTestCase
{
    public function testSj()
    {
        $xa = make(XA::class);
        $gid = $xa->generateGid();
        $xa->globalTransaction($gid, function () use ($xa) {
            /** @var ResponseInterface $res */
            $res =  $xa->callBranch('http://localhost:9507/transIn', ['id' =>1,'num' =>10]);
//            var_dump($res->getBody()->getContents());
            $xa->callBranch('http://127.0.0.1:9507/transOut', ['id' =>1,'num' =>99]);
            // php 调 go服务
//            $xa->callBranch('http://127.0.0.1:8082/api/busi_start/TransIn', ['num' => 80]);
            // 异常测试
            $xa->callBranch('http://127.0.0.1:9507/excpt', ['num' => 1000, 'id' => 1]);
        });
    }

    public function testRpc()
    {
        $xa = make(XA::class);
        $gid = $xa->generateGid();
        $xa->globalTransaction($gid, function () use ($xa) {
            $book = new Book();
            $book->setNum(1)->setId(100);
            [$reply, $status] = $xa->callBranch('127.0.0.1:9503/grpc.hi/transIn', $book, [Reply::class,'decode']);
            /** @var Reply $reply */
            $reply && print_r($reply);
            var_dump($reply->getMsg(), $reply->getId(), $status);
            [$reply, $status]= $xa->callBranch('127.0.0.1:9503/grpc.hi/transOut', $book,[Reply::class,'decode']);
            $reply && print_r($reply);
            var_dump($reply->getMsg(), $reply->getId(), $status);
        });
        // 这个client是协程安全的，可以复用
//        $client = new HiClient('127.0.0.1:9503', [
//            'credentials' => null,
//        ]);
//
//        $book = new Book();
//        $book->setId(100)->setNum(88);
//       /** @var Reply $reply */
//        [$reply, $status] = $client->transIn($book);
//
//        var_dump($reply->getMsg());
    }

    public function testRollback()
    {
        $res = Db::select('xa recover');
        foreach ($res as $trans) {
            var_dump($trans->data);
            var_dump(Db::statement('xa rollback \''.$trans->data.'\''));
        }
        var_dump(Db::select('xa recover'));
    }
}
