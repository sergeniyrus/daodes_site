<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Seed;
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
    $validatedData = $request->validate([
        'recipient' => 'required|string',
        'amount' => 'required|numeric|min:0.01',
        'seed_phrase' => 'required|string'
    ]);

    $user = Auth::user();
    $amount = $validatedData['amount'];
    $recipientName = $validatedData['recipient'];
    $seedPhrase = $validatedData['seed_phrase'];

    $fromWallet = Wallet::where('user_id', $user->id)->firstOrFail();

    $recipientUser = User::where('name', $recipientName)->first();

    if (!$recipientUser) {
        return redirect()->back()->withErrors(['recipient' => 'Пользователь с таким именем не найден.']);
    }

    $toWallet = Wallet::where('user_id', $recipientUser->id)->firstOrFail();

    $storedSeed = Seed::where('user_id', $user->id)->first();
    $storedSeedPhrase = implode(' ', array_slice($storedSeed->toArray(), 2));

    if ($seedPhrase !== $storedSeedPhrase) {
        return redirect()->back()->withErrors(['seed_phrase' => 'Неверная сид фраза.']);
    }

    $totalAmount = $amount * 1.01;

    if ($fromWallet->balance < $totalAmount) {
        return redirect()->back()->withErrors(['amount' => 'Недостаточно средств для перевода.']);
    }

    DB::transaction(function() use ($fromWallet, $toWallet, $amount, $totalAmount) {
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

    return redirect()->route('wallet.wallet')->with('success', 'Перевод успешно выполнен.');
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
