<?php

class Analyzer
{
    public static function analyze($context, $rules, $task, $config)
    {
        $prompt = "
You are DAODES AI ENGINE.

TASK:
$task

RULES:
$rules

PROJECT CONTEXT:
" . json_encode($context, JSON_PRETTY_PRINT) . "

STRICT OUTPUT:
Return ONLY:
1. Problem analysis
2. Risk assessment
3. Laravel-safe solution
4. File changes (if needed)
5. Patch plan (no execution)
";

        return self::callOllama($prompt, $config['models']['architecture'], $config);
    }

    private static function callOllama($prompt, $model, $config)
    {
        $ch = curl_init($config['ollama_url'] . '/api/generate');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $model,
                'prompt' => $prompt,
                'stream' => false
            ]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);

        return json_decode(curl_exec($ch), true);
    }
}