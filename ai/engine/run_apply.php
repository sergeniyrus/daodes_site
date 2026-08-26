<?php

require __DIR__ . '/apply_patch.php';

$patchFile = $argv[1] ?? null;

if (!$patchFile) {
    die("No patch file provided\n");
}

PatchApplier::apply($patchFile);