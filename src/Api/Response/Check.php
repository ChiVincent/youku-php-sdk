<?php

namespace Chivincent\Youku\Api\Response;

class Check
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

    public static function json(string $json): ?Check
    {
        $response = json_decode($json);

        $properties = [
            'status', 'finished', 'upload_server_ip',
        ];

        foreach ($properties as $property) {
            if (!property_exists($response, $property)) {
                return null;
            }
        }

        return new Check(
            $response->status,
            $response->transferred_percent,
            $response->confirmed_percent,
            $response->empty_tasks,
            $response->finished,
            $response->upload_server_ip,
        );
    }

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
