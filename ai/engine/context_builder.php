<?php

class ContextBuilder
{
    public static function build($path, $config)
    {
        $files = [];

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );

        foreach ($iterator as $file) {

            if ($file->isDir()) continue;

            $filePath = $file->getPathname();

            foreach ($config['ignore_paths'] as $ignore) {
                if (str_contains($filePath, $ignore)) {
                    continue 2;
                }
            }

            $files[] = [
                'path' => $filePath,
                'content' => file_get_contents($filePath),
            ];

            if (count($files) >= $config['max_context_files']) {
                break;
            }
        }

        return $files;
    }
}