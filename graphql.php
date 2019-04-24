<?php
// Test this using following command
// php -S localhost:8080 ./graphql.php &
// curl http://localhost:8080 -d '{"query": "query { echo(message: \"Hello World\") }" }'
// curl http://localhost:8080 -d '{"query": "mutation { sum(x: 2, y: 2) }" }'
require_once __DIR__ . '/vendor/autoload.php';
use GraphQL\GraphQL;
use GraphQL\Utils\BuildSchema;
use GraphQL\Error\Debug;

$debug = Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE;
try {
    $schema = BuildSchema::build(file_get_contents(__DIR__ . '/schema.graphqls'));
    $rootValue = include __DIR__ . '/rootvalue.php';
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    $context = ['auth'=> true];
    $variableValues = isset($input['variables']) ? $input['variables'] : null;
    $result = GraphQL::executeQuery($schema, $query, $rootValue, $context, $variableValues);
} catch (\Exception $e) {
    $result = [
        'error' => [
            'message' => $e->getMessage()->debugMessage()
        ]
    ];
}
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($result);