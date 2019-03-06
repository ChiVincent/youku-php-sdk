<?php

namespace Chivincent\Youku\Api\Response;

class NewSlice
{
    /**
     * @var int
     */
    private $sliceTaskId;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $length;

    /**
     * @var int
     */
    private $transferred;

    /**
     * @var bool
     */
    private $finished;

    public static function json(string $json): ?NewSlice
    {
        $response = json_decode($json);

        $properties = [
            'slice_task_id', 'offset', 'length', 'transferred', 'finished',
        ];

        foreach ($properties as $property) {
            if (!property_exists($response, $property)) {
                return null;
            }
        }

        return new NewSlice(
            $response->slice_task_id,
            $response->offset,
            $response->length,
            $response->transferred,
            $response->finished,
        );
    }

    public function __construct(int $sliceTaskId, int $offset, int $length, int $transferred, bool $finished)
    {
        $this->sliceTaskId = $sliceTaskId;
        $this->offset = $offset;
        $this->length = $length;
        $this->transferred = $transferred;
        $this->finished = $finished;
    }

    /**
     * @return int
     */
    public function getSliceTaskId(): int
    {
        return $this->sliceTaskId;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return int
     */
    public function getTransferred(): int
    {
        return $this->transferred;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->finished;
    }
}
