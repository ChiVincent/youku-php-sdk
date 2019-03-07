<?php

namespace Chivincent\Youku\Api\Response;

use stdClass;

class Error
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

    public static function json(string $json): ?Error
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

        return new Error(
            $response->error->code,
            $response->error->type,
            $response->error->description
        );
    }

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
