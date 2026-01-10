<?php

return [
    // Messages after registration
'registration_complete_message' => '✅ Registration is completed! Log in using the LED phrase.',

    // LED phrase requirement at the entrance
    'seed_required_to_access' => 'To access, you must enter a seed phrase.',

    // Title of the key settings page
    'setup_keys_title' => 'Configuring Cryptographic keys',

    'new_device' => '🔑 You have logged in from a new device. Enter the seed phrase for this account.',

    // Instructions
'seed_setup_intro' => 'Enter the full seed phrase (24 words) to generate your encryption keys.',
    'seed_setup_warning' => '⚠️ Without a seed phrase, you will not be able to access your account.',

    // Placeholders and buttons
    'enter_24_words' => 'Word1 Word2 ... Word24',
    'confirm_seed' => 'Confirm the SEED phrase',

    // Input errors
    'seed_required' => 'Please enter the LED phrase',
    'invalid_seed_phrase' => 'Invalid seed phrase. Make sure that you have entered the same phrase that you received during registration.',

    // Success and errors of saving
    'keys_saved_success' => 'Keys have been successfully generated and saved!',
    'keys_save_failed' => "Couldn't save the keys. Try again.",
    'keys_already_set' => 'The keys are already configured. Access is open.',

    // Blocking after attempts
    'seed_blocked_after_attempts' => 'Too many failed attempts. Repeat later.',
    'seed_blocked_for_minutes' => 'The input is blocked for :minutes minutes due to many errors.',
'seed_blocked' => 'Access is temporarily blocked.',

];