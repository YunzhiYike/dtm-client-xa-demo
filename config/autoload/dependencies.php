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

use DtmClient\Api\ApiInterface;
use DtmClient\Api\HttpApi;
use DtmClient\Api\HttpApiFactory;
use DtmClient\ApiFactory;
use DtmClient\BranchIdGenerator;
use DtmClient\BranchIdGeneratorInterface;
use DtmClient\DBSpecial\DBSpecialInterface;
use DtmClient\DBSpecial\MySqlDBSpecial;
use DtmClient\DbTransaction\DBTransactionInterface;
use DtmClient\DbTransaction\HyperfDbTransaction;
use DtmClient\Grpc\GrpcClientManager;
use DtmClient\Grpc\GrpcClientManagerFactory;
use DtmClient\JsonRpc\DtmPatchGenerator;
use Hyperf\HttpServer\Response;
use Hyperf\JsonRpc\JsonRpcPoolTransporter;
use Hyperf\JsonRpc\JsonRpcTransporter;
use Hyperf\Rpc\Contract\PathGeneratorInterface;
use Psr\Http\Message\ResponseInterface;

return [
    ApiInterface::class => ApiFactory::class,
    HttpApi::class => HttpApiFactory::class,
    BranchIdGeneratorInterface::class => BranchIdGenerator::class,
    GrpcClientManager::class => GrpcClientManagerFactory::class,
    DBTransactionInterface::class => HyperfDbTransaction::class,
    DBSpecialInterface::class => MySqlDBSpecial::class,
    PathGeneratorInterface::class => DtmPatchGenerator::class,
    JsonRpcTransporter::class => JsonRpcPoolTransporter::class,
    ResponseInterface::class => Response::class,
];
