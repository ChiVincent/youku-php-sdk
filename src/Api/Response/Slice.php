<?php

namespace Chivincent\Youku\Api\Response;

abstract class Slice
{
    /**
     * @var int
     */
    protected $sliceTaskId;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var int
     */
    protected $transferred;

    /**
     * @var bool
     */
    protected $finished;

    /**
     * Slice constructor.
     *
     * @param int $sliceTaskId
     * @param int $offset
     * @param int $length
     * @param int $transferred
     * @param bool $finished
     */
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
