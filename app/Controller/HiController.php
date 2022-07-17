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

use DtmClient\Grpc\GPBMetadata\Dtm;
use DtmClient\Grpc\Message\DtmBranchRequest;
use DtmClient\Grpc\Message\DtmRequest;
use DtmClient\TransContext;
use DtmClient\XA;
use Exception;
use Google\Protobuf\GPBEmpty;
use Google\Protobuf\Internal\Message;
use Grpc\Book;
use Grpc\Reply;
use Hyperf\Context\Context;
use Hyperf\Grpc\Parser;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;
use Swoole\Http\Request;

class HiController
{
//    protected Request $request;

    private  $request;

    public function __construct(\Hyperf\HttpServer\Request $request)
    {

        $this->request = ApplicationContext::getContainer()->get(Request::class);
//        ApplicationContext::getContainer()->get(Request::class);
    }

    public function transIn(DtmBranchRequest $request)
    {
        $xa = make(XA::class);
        $xa->initToTransContext($request);
        $xa->localTransaction(function () {
            var_dump('调用transIn');
        });
        $reply = new Reply();
        $reply->setMsg('ok-in');
        $reply->setId(1999);
        return $reply;
    }

    public function transOut(DtmBranchRequest $request)
    {
        throw new Exception('transOut');
        $params = $request->getBusiPayload();
        var_dump($params);
        $xa = make(XA::class);
        $xa->initToTransContext($request);
        $xa->localTransaction(function () {
            var_dump('调用transOut');
        });
        $reply = new Reply();
        $reply->setMsg('ok-out');
        return $reply;
    }
}
