<?php

namespace Chivincent\Youku\Api\Response;

use stdClass;

class Error
{
    /**
     * @var stdClass
     */
    private $error;

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

    public static function json(string $json): ?Error
    {
        $response = json_decode($json);

        $properties = [
            'error', 'code', 'type', 'description',
        ];

        foreach ($properties as $property) {
            if (!property_exists($response, $property)) {
                return null;
            }
        }

        return new Error(
            $response->error,
            $response->code,
            $response->type,
            $response->description,
        );
    }

    public function __construct(stdClass $error, int $code, string $type, string $description)
    {
        $this->error = $error;
        $this->code = $code;
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * @return stdClass
     */
    public function getError(): stdClass
    {
        return $this->error;
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
