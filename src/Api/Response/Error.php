<?php

namespace Chivincent\Youku\Api\Response;

use Chivincent\Youku\Contracts\JsonResponse;

class Error extends BaseResponse implements JsonResponse
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $description;

    /**
     * Make Error Response by json.
     *
     * @param string $json
     * @return Error|null
     */
    public static function json(string $json): ?BaseResponse
    {
        $response = json_decode($json);

        if (!property_exists($response, 'error')) {
            return null;
        }

        $properties = [
            'code' , 'type', 'description',
        ];

        foreach ($properties as $property) {
            if (!property_exists($response->error, $property)) {
                return null;
            }
        }

        return new self(
            $response->error->code,
            $response->error->type,
            $response->error->description
        );
    }

    /**
     * Error constructor.
     *
     * @param int $code
     * @param string $type
     * @param string $description
     */
    public function __construct(int $code, string $type, string $description)
    {
        $this->code = $code;
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
