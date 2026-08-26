<?php

require_once __DIR__ . '/approval_queue.php';

class PatchApplier
{
    private static string $projectRoot = '/var/www/daodes';

    /**
     * Вместо прямого apply → отправляем в очередь на approval
     */
    public static function apply(string $patchFile): void
    {
        $patch = file_get_contents($patchFile);

        if (!$patch) {
            throw new Exception("Empty patch file");
        }

        // нормализуем патч
        $patchData = [
            'diff' => $patch,
            'project_root' => self::$projectRoot,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // 🔥 КЛЮЧЕВОЕ ИЗМЕНЕНИЕ: отправляем в очередь
        ApprovalQueue::add($patchData);

        echo "Patch sent to approval queue\n";
    }

    /**
     * ФАКТИЧЕСКОЕ ПРИМЕНЕНИЕ (вызывается ТОЛЬКО после approve UI)
     */
    public static function applyApproved(array $patch): void
    {
        self::backup();

        $tmp = self::$projectRoot . '/ai_last_patch.diff';

        file_put_contents($tmp, $patch['diff']);

        $cmd = "cd " . self::$projectRoot . " && git apply $tmp";

        exec($cmd, $output, $code);

        if ($code !== 0) {
            self::rollback();
            throw new Exception("Patch failed, rollback executed");
        }

        echo "Approved patch applied successfully\n";
    }

    /**
     * backup перед реальным изменением
     */
    private static function backup(): void
    {
        $time = date('Y-m-d_H-i-s');

        $cmd = "cd " . self::$projectRoot .
            " && git add . && git commit -m 'AUTO BACKUP $time'";

        exec($cmd);
    }

    /**
     * rollback при ошибке применения
     */
    private static function rollback(): void
    {
        $cmd = "cd " . self::$projectRoot . " && git reset --hard HEAD~1";

        exec($cmd);

        echo "Rollback executed\n";
    }
}