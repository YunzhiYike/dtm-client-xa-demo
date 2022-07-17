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
namespace App\Controller;

use DtmClient\XA;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;

class IndexController extends AbstractController
{
    public function __call(string $name, array $arguments)
    {
        echo 'aaa';
        // TODO: Implement __call() method.
    }



    /**
     * @throws \DtmClient\Exception\XaTransactionException
     */
    public function transIn(RequestInterface $request)
    {
//        var_dump('request', $request->all(), '================================');
        $xa = make(XA::class);
        $res = $xa->localTransaction($request->getQueryParams(), function () use ($request) {
            var_dump(1122);
//            $book = make(Book::class);
//            $book->newQuery()->where(['id' => $request->post('id')])->update(['num' => $request->post('num')]);
//            Db::table('book')->where(['id' => $request->post('id')])->update(['num'=>$request->post('num')]);
        });
        return ['s' => 13333];
    }

    public function transOut(RequestInterface $request)
    {
        $xa = make(XA::class);
//        var_dump('request', $request->all(), '================================');
        $xa->localTransaction(function () use ($request) {
            var_dump(1111);
//            $mbook = make(MBook::class);
//            $mbook->newQuery()->where(['id' => $request->post('id')])->update(['num' => $request->post('num')]);
//            Db::table('mbook')->where(['id' => $request->post('id')])->update(['num'=>$request->post('num')]);
        });
        return ['s' => 1];
    }

    public function excpt(RequestInterface $request)
    {
        throw new \Exception('xa 分布式事物测试');
    }

    public function rpc()
    {
        return 22;
        $xa = make(XA::class);
        $gid = $xa->generateGid();
        $xa->globalTransaction($gid, function () use ($xa, $book) {
            $book->setId(1)->setNum(1);
            $xa->callBranch('http://localhost:9501/transIn', $book);
            $xa->callBranch('http://127.0.0.1:9501/transOut', $book);
            // php 调 go服务
//            $xa->callBranch('http://127.0.0.1:8082/api/busi_start/TransIn', ['num' => 80]);
            // 异常测试
//            $xa->callBranch('http://127.0.0.1:9501/excpt', ['num' => 1000, 'id' => 1]);
        });
    }
}
