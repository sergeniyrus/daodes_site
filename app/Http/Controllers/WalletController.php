<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\HistoryPay;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

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
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 100]
        );

        if ($wallet->wasRecentlyCreated) {
            // Сообщение о создании кошелька и начислении 100 монет
            session()->flash('message', 'Ваш кошелек создан и на него начислено 100 descoin.');
        }

        return view('wallet.wallet', compact('wallet'));
    }

    public function showTransferForm()
    {
        return view('wallet.transfer');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'to_username' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
            'user_seed' => 'required|string'
        ]);

        try {
            $user = Auth::user();
            $fromWallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            // Получение пользователя-получателя по имени
            $toUser = User::where('name', $request->input('to_username'))->first();
            if (!$toUser) {
                throw new Exception('Нет такого пользователя');
            }

            // Создание кошелька для получателя, если его еще нет
            $toWallet = Wallet::firstOrCreate(
                ['user_id' => $toUser->id],
                ['balance' => 0]
            );

            // Получение сохраненной сид-фразы
            $savedSeed = DB::table('seed')
                ->where('user_id', $fromWallet->user_id)
                ->first();

            if (!$savedSeed) {
                throw new Exception('Сид-фраза не найдена');
            }

            // Преобразование вводимой сид-фразы в строку для сравнения
            $enteredSeedString = trim($request->input('user_seed'));

            // Проверка сид-фразы
            if (!$this->walletService->validateSeed($fromWallet->user_id, $enteredSeedString)) {
                return redirect()->back()->with('error', 'Неверная сид фраза');
            }

            // Проверка достаточности средств с учетом комиссии
            $amount = $request->input('amount');
            $fee = $amount * WalletService::FEE_PERCENTAGE;
            $totalAmount = $amount + $fee;

            if ($fromWallet->balance < $totalAmount) {
                throw new Exception('Не хватает средств для перевода');
            }

            // Выполнение перевода
            $this->walletService->transfer(
                $fromWallet->user_id,
                $toWallet->user_id,
                $amount,
                $enteredSeedString
            );

            return redirect()->route('wallet.wallet')->with('success', 'Перевод выполнен успешно');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function history()
    {
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

        $historyPays = HistoryPay::where('from_wallet_id', $wallet->id)
            ->orWhere('to_wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wallet.history', compact('historyPays'));
    }

    
}
