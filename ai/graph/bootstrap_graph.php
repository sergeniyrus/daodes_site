<?php

class GraphBootstrap
{
    public static function ensure(string $basePath): void
    {
        $dir = $basePath . '/ai/graph';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = $dir . '/graph.json';

        if (!file_exists($file)) {
            file_put_contents($file, json_encode([
                'nodes' => [],
                'edges' => [],
                'meta' => [
                    'status' => 'empty',
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ], JSON_PRETTY_PRINT));
        }
    }
}