<?php

$config = require __DIR__ . '/config.php';

require __DIR__ . '/context_builder.php';
require __DIR__ . '/rule_engine.php';
require __DIR__ . '/analyzer.php';
require __DIR__ . '/patch_generator.php';

$task = $argv[1] ?? 'Analyze project structure';

echo "Building context...\n";
$context = ContextBuilder::build($config['project_root'], $config);

echo "Loading rules...\n";
$rules = RuleEngine::loadRules($config['rules_path']);

echo "Running analysis...\n";
$analysis = Analyzer::analyze($context, $rules, $task, $config);

echo "Generating patch...\n";
$patch = PatchGenerator::generate($analysis, $config);

file_put_contents('ai_last_patch.txt', json_encode($patch, JSON_PRETTY_PRINT));

echo "DONE. Patch saved to ai_last_patch.txt\n";