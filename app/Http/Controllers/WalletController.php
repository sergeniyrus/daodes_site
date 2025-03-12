<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Seed;
use App\Models\HistoryPay;
use App\Models\UserProfile;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function wallet()
    {
        $user = Auth::user();

        // Получаем или создаем кошелек для пользователя
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 100]
        );

        // Получаем профиль пользователя
        $UserProfile = $user->profile;

        if ($wallet->wasRecentlyCreated) {
            // Сообщение о создании кошелька и начислении 100 монет
            session()->flash('message', __('message.wallet_created'));
        }

        // Передаем кошелек и профиль пользователя в представление
        return view('wallet.wallet', compact('wallet', 'UserProfile'));
    }

    public function showTransferForm()
    {
        $user = Auth::user();
        $UserProfile = $user->profile;

        // Проверяем, есть ли аватар в профиле, и передаем правильный URL
        $avatarUrl = $UserProfile ? $UserProfile->avatar_url : '/img/main/img_avatar.jpg';

        return view('wallet.transfer', compact('avatarUrl', 'UserProfile'));
    }

    public function transfer(Request $request)
    {
        $validatedData = $request->validate([
            'recipient' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'seed_phrase' => 'required|string'
        ]);

        $user = Auth::user();
        $amount = $validatedData['amount'];
        $recipientName = $validatedData['recipient'];
        $seedPhrase = $validatedData['seed_phrase'];

        // Получаем кошелек отправителя
        $fromWallet = Wallet::where('user_id', $user->id)->firstOrFail();

        // Находим получателя
        $recipientUser = User::where('name', $recipientName)->first();

        if (!$recipientUser) {
            return redirect()->back()->withErrors(['recipient' => __('message.recipient_not_found')]);
        }

        // Получаем кошелек получателя
        $toWallet = Wallet::where('user_id', $recipientUser->id)->firstOrFail();

        // Добавляем комиссию
        $totalAmount = $amount * 1.01;

        // Проверяем, есть ли достаточно средств
        if ($fromWallet->balance < $totalAmount) {
            return redirect()->back()->withErrors(['amount' => __('message.insufficient_funds')]);
        }

        // Проверка сид-фразы
        $storedSeed = Seed::where('user_id', $user->id)->first();
        if (!$storedSeed) {
            return redirect()->back()->withErrors(['seed_phrase' => __('message.seed_phrase_not_found')]);
        }

        $storedSeedPhrase = implode(' ', array_slice($storedSeed->toArray(), 2));

        // Убираем пробелы и приводим к нижнему регистру
        $seedPhrase = strtolower(trim($seedPhrase));
        $storedSeedPhrase = strtolower(trim($storedSeedPhrase));

        // Логируем длину строк
        Log::info('Length of entered seed phrase: ' . strlen($seedPhrase));
        Log::info('Length of stored seed phrase: ' . strlen($storedSeedPhrase));

        // Логируем строки
        Log::info('Seed phrase entered by user: ' . $seedPhrase);
        Log::info('Stored seed phrase: ' . $storedSeedPhrase);

        // Сравнение сид-фраз
        if ($seedPhrase !== $storedSeedPhrase) {
            return redirect()->back()->withErrors(['seed_phrase' => __('message.invalid_seed_phrase')]);
        }

        // Транзакция
        DB::transaction(function () use ($fromWallet, $toWallet, $amount, $totalAmount) {
            $fromWallet->balance -= $totalAmount;
            $fromWallet->save();

            $toWallet->balance += $amount;
            $toWallet->save();

            HistoryPay::create([
                'from_wallet_id' => $fromWallet->id,
                'to_wallet_id' => $toWallet->id,
                'amount' => $amount,
                'fee' => $totalAmount - $amount
            ]);

            $systemWallet = Wallet::where('user_id', 1)->first();
            $systemWallet->balance += $totalAmount - $amount;
            $systemWallet->save();
        });

        return redirect()->route('wallet.index')->with('success', __('message.transfer_success'));
    }

    public function history()
    {
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

        $historyPays = HistoryPay::where('from_wallet_id', $wallet->id)
            ->orWhere('to_wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('wallet.history', compact('historyPays'));
    }
}
