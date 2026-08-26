<?php

class PatchGenerator
{
    public static function generate($analysis, $config)
    {
        $prompt = "
Convert this analysis into REAL Laravel code patch.

RULES:
- NEVER break architecture
- ONLY Laravel 11 code
- NO Python, NO external scripts
- Output must be unified diff style

ANALYSIS:
" . json_encode($analysis, JSON_PRETTY_PRINT);

        return self::callOllama($prompt, $config['models']['code'], $config);
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