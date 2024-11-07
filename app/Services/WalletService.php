<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\HistoryPay;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
    const FEE_PERCENTAGE = 0.01;
    const SYSTEM_PULL1_USER_ID = 1; // Замените на реальный user_id для Pull1

    public function transfer($fromUserId, $toUserId, $amount, $userSeedString)
    {
        return DB::transaction(function () use ($fromUserId, $toUserId, $amount, $userSeedString) {
            $fromWallet = Wallet::where('user_id', $fromUserId)->firstOrFail();
            $toWallet = Wallet::where('user_id', $toUserId)->firstOrFail();

            if (!$this->validateSeed($fromWallet->user_id, $userSeedString)) {
                throw new Exception('Invalid seed phrase');
            }

            $fee = $amount * self::FEE_PERCENTAGE;
            $totalAmount = $amount + $fee;

            if ($fromWallet->balance < $totalAmount) {
                throw new Exception('Insufficient funds');
            }

            $fromWallet->balance -= $totalAmount;
            $fromWallet->save();

            $toWallet->balance += $amount;
            $toWallet->save();

            $systemPullWallet = Wallet::where('user_id', self::SYSTEM_PULL1_USER_ID)->firstOrFail();

            $systemPullWallet->balance += $fee;
            $systemPullWallet->save();

            HistoryPay::create([
                'from_wallet_id' => $fromWallet->id,
                'to_wallet_id' => $toWallet->id,
                'amount' => $amount,
                'fee' => $fee
            ]);

            return true;
        });
    }

    public function validateSeed($userId, $userSeedString)
    {
        $userSeedWords = DB::table('seed')
            ->where('user_id', $userId)
            ->first();

        if (!$userSeedWords) {
            return false;
        }

        // Преобразуем объект в массив и затем в строку для удобного сравнения
        $seedArray = [];
        for ($i = 0; $i < 24; $i++) {
            $seedArray[] = trim($userSeedWords->{'word' . $i});
        }
        $seedString = implode(' ', $seedArray);

        // Преобразование вводимой сид-фразы в строку для сравнения
        $enteredSeedString = trim($userSeedString);

        // Проверка соответствия строк
        return $seedString === $enteredSeedString;
    }
}
