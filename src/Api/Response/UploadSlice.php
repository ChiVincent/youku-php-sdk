<?php

namespace Chivincent\Youku\Api\Response;

class UploadSlice extends Slice
{
    public function __construct(int $sliceTaskId, int $offset, int $length, int $transferred, bool $finished)
    {
        parent::__construct($sliceTaskId, $offset, $length, $transferred, $finished);
    }

    public static function json(string $json): ?UploadSlice
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

        return new UploadSlice(
            $response->slice_task_id,
            $response->offset,
            $response->length,
            $response->transferred,
            $response->finished,
        );
    }
}
