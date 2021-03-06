<?php

declare(strict_types=1);

namespace App\Swagger;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerDecorator implements NormalizerInterface
{
    private NormalizerInterface $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $docs['components']['schemas']['Token'] = [
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ];

        $docs['components']['schemas']['Credentials'] = [
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'api',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'api',
                ],
            ],
        ];

        $docs['paths']['/users/confirm/{confirmationToken}']['get'] = [
            'tags' => ['User'],
            'summary' => 'test',
            'parameters' => [
                [
                    'name' => 'confirmationToken',
                    'in' => 'path',
                    'schema' => [
                        'type' => 'string',
                    ],
                    'required' => true,
                ],
            ],
            'responses' => [
                Response::HTTP_OK => [
                    'description' => 'User account enabled',
                    'content' => [
                        "application/ld+json" => [
                            "schema" =>  [
                              '$ref' => "#/components/schemas/User:jsonld"
                            ]
                        ],
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/User',
                            ],
                        ],
                    ],
                ],
                Response::HTTP_BAD_REQUEST => [
                    'description' => 'Wrong token'
                ],
            ],
        ];

        $docs['paths']['/invitations/invite/{offerId}']['get'] = [
            'tags' => ['Invitation'],
            'summary' => 'invite',
            'parameters' => [
                [
                    'name' => 'offerId',
                    'in' => 'path',
                    'schema' => [
                        'type' => 'integer',
                    ],
                    'required' => true,
                ],
                [
                    'name' => 'userEmail',
                    'in' => 'query',
                    'schema' => [
                        'type' => 'string',
                    ],
                    'required' => true,
                ],
            ],
            'responses' => [
                Response::HTTP_OK => [
                    'description' => 'Ok',
                ],
                Response::HTTP_BAD_REQUEST => [
                    'description' => 'Wrong token'
                ],
            ],
        ];

        $tokenDocumentation = [
            'paths' => [
                '/authentication_token' => [
                    'post' => [
                        'tags' => ['Token'],
                        'operationId' => 'postCredentialsItem',
                        'summary' => 'Get JWT token to login.',
                        'requestBody' => [
                            'description' => 'Create new JWT Token',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Credentials',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get JWT token',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/Token',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return array_merge_recursive($docs, $tokenDocumentation);
    }
}
