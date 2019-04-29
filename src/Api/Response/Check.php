<?php

namespace Chivincent\Youku\Api\Response;

use Chivincent\Youku\Contracts\JsonResponse;
use Chivincent\Youku\Exception\UploadException;

class Check extends BaseResponse implements JsonResponse
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var int|null
     */
    private $transferredPercent;

    /**
     * @var int|null
     */
    private $confirmedPercent;

    /**
     * @var int|null
     */
    private $emptyTasks;

    /**
     * @var bool
     */
    private $finished;

    /**
     * @var string
     */
    private $uploadServerIp;

    /**
     * Make Check Response by json.
     *
     * @param string $json
     * @return Check|null
     */
    public static function json(string $json): ?BaseResponse
    {
        $response = json_decode($json);

        if (isset($response->error)) {
            throw new UploadException(Error::json($json));
        }

        $properties = [
            'status', 'finished', 'upload_server_ip',
        ];

        foreach ($properties as $property) {
            if (!property_exists($response, $property)) {
                return null;
            }
        }

        return new self(
            $response->status,
            $response->transferred_percent ?? null,
            $response->confirmed_percent ?? null,
            $response->empty_tasks ?? null,
            $response->finished,
            $response->upload_server_ip
        );
    }

    /**
     * Check constructor.
     *
     * @param int $status
     * @param int|null $transferredPercent
     * @param int|null $confirmedPercent
     * @param int|null $emptyTasks
     * @param bool $finished
     * @param string $uploadServerIp
     */
    public function __construct(int $status, ?int $transferredPercent, ?int $confirmedPercent, ?int $emptyTasks, bool $finished, string $uploadServerIp)
    {
        $this->status = $status;
        $this->transferredPercent = $transferredPercent;
        $this->confirmedPercent = $confirmedPercent;
        $this->emptyTasks = $emptyTasks;
        $this->finished = $finished;
        $this->uploadServerIp = $uploadServerIp;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return int|null
     */
    public function getTransferredPercent(): ?int
    {
        return $this->transferredPercent;
    }

    /**
     * @return int|null
     */
    public function getConfirmedPercent(): ?int
    {
        return $this->confirmedPercent;
    }

    /**
     * @return int|null
     */
    public function getEmptyTasks(): ?int
    {
        return $this->emptyTasks;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->finished;
    }

    /**
     * @return string
     */
    public function getUploadServerIp(): string
    {
        return $this->uploadServerIp;
    }
}
