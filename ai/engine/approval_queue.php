<?php

class ApprovalQueue
{
    private static string $queueFile = '/var/www/daodes/storage/ai_patch_queue.json';

    public static function add(array $patch): void
    {
        $queue = self::load();

        $queue[] = [
            'id' => uniqid('patch_'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'patch' => $patch
        ];

        file_put_contents(self::$queueFile, json_encode($queue, JSON_PRETTY_PRINT));
    }

    public static function load(): array
    {
        if (!file_exists(self::$queueFile)) {
            return [];
        }

        return json_decode(file_get_contents(self::$queueFile), true) ?? [];
    }

    public static function updateStatus(string $id, string $status): void
    {
        $queue = self::load();

        foreach ($queue as &$item) {
            if ($item['id'] === $id) {
                $item['status'] = $status;
            }
        }

        file_put_contents(self::$queueFile, json_encode($queue, JSON_PRETTY_PRINT));
    }
}