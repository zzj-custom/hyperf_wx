<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use Contract\Exceptions\LogicException;
use Contract\Exceptions\RemoteException;
use Contract\Exceptions\ValidationException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Arr;
use Hyperf\Validation\ValidationException as HyperfValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        // 捕获验证器异常
        if ($throwable instanceof HyperfValidationException) {
            return $this->formatResponse($response, ErrorCode::VALIDATION_ERROR, $throwable->validator->errors()->first(), $throwable->errors());
        }
        // 捕获用户验证异常
        if ($throwable instanceof ValidationException) {
            return $this->formatResponse($response, $throwable->getCode(), $throwable->getMessage());
        }

        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        // 捕获远程异常或者禁止异常
        if ($throwable instanceof RemoteException) {
            return $this->formatResponse($response, $throwable->getCode(), $throwable->getMessage());
        }
        // 捕获模型不存在异常
        if ($throwable instanceof ModelNotFoundException) {
            return $this->formatResponse($response, ErrorCode::E_NOT_FOUND_RECORD, ErrorCode::getMessage(ErrorCode::E_NOT_FOUND_RECORD));
        }

        // 捕获逻辑异常
        if ($throwable instanceof LogicException) {
            return $this->formatResponse($response, $throwable->getCode(), '系统内部错误, 请稍后再试');
        }
        return $response->withHeader('Server', 'Hyperf')
            ->withStatus(ErrorCode::SERVER_ERROR)
            ->withBody(new SwooleStream(ErrorCode::getMessage(ErrorCode::SERVER_ERROR)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

    protected function formatResponse(ResponseInterface $response, int $code, string $msg, ?array $data = []): ResponseInterface
    {
        $body = ['code' => $code, 'msg' => $msg];
        if (! empty($data)) {
            Arr::set($body, 'result', $data);
        }

        return $response->withBody(new SwooleStream(json_encode($body)))
            ->withAddedHeader('content-type', 'application/json; charset=utf-8');
    }
}
