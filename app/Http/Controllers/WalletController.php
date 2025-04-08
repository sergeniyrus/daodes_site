<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Seed;
use App\Models\HistoryPay;
use App\Models\UserProfile;
use App\Services\WalletService;
use App\Services\EncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $UserProfile = $user->profile;

        if ($wallet->wasRecentlyCreated) {
            session()->flash('message', __('message.wallet_created'));
        }

        return view('wallet.wallet', compact('wallet', 'UserProfile'));
    }

    public function showTransferForm()
    {
        $user = Auth::user();
        $UserProfile = $user->profile;

        if (!$UserProfile) {
            session()->flash('error', __('wallet.profile_not_found'));
        }

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
        $seedPhraseInput = strtolower(trim($validatedData['seed_phrase']));

        $fromWallet = Wallet::where('user_id', $user->id)->firstOrFail();

        $recipientUser = User::where('name', $recipientName)->first();
        if (!$recipientUser) {
            session()->flash('error', __('wallet.invalid_recipient'));
            return redirect()->back()->withErrors(['recipient' => __('wallet.invalid_recipient')]);
        }

        $toWallet = Wallet::where('user_id', $recipientUser->id)->firstOrFail();
        $totalAmount = $amount * 1.01;

        if ($fromWallet->balance < $totalAmount) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('wallet.insufficient_funds'));
        }
        

        $seed = Seed::where('user_id', $user->id)->first();
        if (!$seed) {
            session()->flash('error', __('wallet.seed_phrase_not_found'));
            return redirect()->back()->withErrors(['seed_phrase' => __('wallet.seed_phrase_not_found')]);
        }

        $encryptionService = app(EncryptionService::class);
        $decryptedWords = [];

        for ($i = 0; $i <= 23; $i++) {
            $wordField = "word{$i}";
            if (!isset($seed->$wordField)) {
                session()->flash('error', __('wallet.seed_phrase_corrupted'));
                return redirect()->back()->withErrors(['seed_phrase' => __('wallet.seed_phrase_corrupted')]);
            }

            try {
                $decryptedWords[] = strtolower(trim($encryptionService->decrypt($seed->$wordField)));
            } catch (\Exception $e) {
                session()->flash('error', __('wallet.seed_phrase_decryption_failed'));
                return redirect()->back()->withErrors(['seed_phrase' => __('wallet.seed_phrase_decryption_failed')]);
            }
        }

        $decryptedSeedPhrase = implode(' ', $decryptedWords);

        if ($seedPhraseInput !== $decryptedSeedPhrase) {
            session()->flash('error', __('wallet.invalid_seed_phrase'));
            return redirect()->back()->withErrors(['seed_phrase' => __('wallet.invalid_seed_phrase')]);
        }

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

        session()->flash('message', __('wallet.transfer_success'));
        return redirect()->route('wallet.index');
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
