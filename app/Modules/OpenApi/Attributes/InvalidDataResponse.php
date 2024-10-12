<?php

namespace App\Modules\OpenApi\Attributes;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class InvalidDataResponse extends OAT\Response
{
    /**
     * @param array<\OpenApi\Attributes\Property> $properties
     */
    public function __construct(
        array $properties = [],
    ) {
        $properties = $properties ?: $this->getDefaultProperties();

        parent::__construct(
            response: 422,
            description: 'Invalid data response.',
            content: new OAT\JsonContent(
                properties: $properties
            )
        );
    }

    /**
     * @return array<\OpenApi\Attributes\Property>
     */
    private function getDefaultProperties(): array
    {
        return [
            new OAT\Property(
                property: 'message',
                type: 'string',
                example: 'The name field is required',
            ),
            new OAT\Property(
                property: 'errors',
                type: 'object',
                additionalProperties: new OAT\AdditionalProperties(
                    title: 'input field',
                    type: 'array',
                    items: new OAT\Items(type: 'string', title: 'input field error'),
                ),
            ),
        ];
    }
}
