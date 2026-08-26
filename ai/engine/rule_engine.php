<?php

class RuleEngine
{
    public static function loadRules($rulesPath)
    {
        $rules = [];

        foreach (glob($rulesPath . '/*.md') as $file) {
            $rules[] = file_get_contents($file);
        }

        return implode("\n\n", $rules);
    }
}