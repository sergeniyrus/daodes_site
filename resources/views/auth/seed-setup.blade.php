@extends('template')

@section('title_page', __('auth.setup_keys_title'))

@section('main')
<style>
.seed-setup-box {
    background: #0b0c18;
    border: 1px solid gold;
    border-radius: 15px;
    padding: 25px;
    max-width: 600px;
    margin: 30px auto;
    text-align: center;
    color: #fff;
}

.seed-setup-box h2 {
    color: gold;
    margin-bottom: 20px;
}

.seed-instruction {
    margin: 15px 0;
    line-height: 1.5;
    color: #a0ff08;
}

#seedPhraseInput {
    width: 100%;
    min-height: 120px;
    background: #1a1a1a;
    color: #fff;
    border: 1px solid gold;
    border-radius: 8px;
    padding: 12px;
    margin: 15px 0;
    font-size: 1.1rem;
    resize: vertical;
}

#seedPhraseInput:disabled {
    background: #333;
    color: #777;
}

.des-btn {
    display: inline-block;
    color: #ffffff;
    background: #0b0c18;
    padding: 10px 20px;
    font-size: 1.2rem;
    border: 1px solid gold;
    border-radius: 10px;
    text-decoration: none;
    margin: 10px;
    cursor: pointer;
}

.des-btn:hover:not(:disabled) {
    box-shadow: 0 0 20px goldenrod;
    transform: scale(1.05);
}

.des-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@section('main')
<div id="setup-keys-page" style="display:none;"></div>


<div class="seed-setup-box">
    <h2>🔑 {{ __('auth.setup_keys_title') }}</h2>
    
    {{-- Сообщение о новом устройстве --}}
    @if(request('new_device'))
        <div style="background: #222; border: 1px solid gold; border-radius: 8px; padding: 12px; margin-bottom: 20px; color: #a0ff08;">
            🔑 Вы зашли с нового устройства. Подтвердите владение сид-фразой для этого аккаунта.
        </div>
    @endif

    {{-- Сообщения из сессии --}}
    @if(session('error'))
        <div style="color: #ff6666; background: #222; padding: 12px; border-radius: 8px; margin: 15px 0; border: 1px solid #ff4444;">
            {{ session('error') }}
        </div>
    @endif

    <p class="seed-instruction">
        {{ __('auth.seed_setup_intro') }}
    </p>
    <p style="color: #ff6666; font-weight: bold;">
        {{ __('auth.seed_setup_warning') }}
    </p>

    <textarea id="seedPhraseInput" placeholder="{{ __('auth.enter_24_words') }}"></textarea>

    <br>
    <button onclick="confirmWithSeed()" id="confirmBtn" class="des-btn">
        {{ __('auth.confirm_seed') }}
    </button>
</div> 


<script>
// === Утилиты ===
function b64ToU8(b64) {
    return Uint8Array.from(atob(b64), c => c.charCodeAt(0));
}
function u8ToB64(u8) {
    return btoa(String.fromCharCode(...u8));
}

function deriveBoxKeyPairFromSeedPhrase(phrase) {
    const encoder = new TextEncoder();
    const utf8 = encoder.encode(phrase);
    const hash = nacl.hash(utf8);
    const seed32 = hash.subarray(0, 32);

    const signKeyPair = nacl.sign.keyPair.fromSeed(seed32);
    const boxSecretKey = signKeyPair.secretKey.subarray(0, 32);
    const boxPublicKey = nacl.scalarMult.base(boxSecretKey);

    return {
        public_key: u8ToB64(boxPublicKey),
        secret_key: u8ToB64(boxSecretKey)
    };
}

const CURRENT_USER_ID = {{ auth()->id() }};

// Очистка чужих ключей ------------ ВАЖНО В продакшен раскомментировать!!!
// Object.keys(localStorage).forEach(key => {
//     if (key.startsWith('userPrivateKey_') && key !== `userPrivateKey_${CURRENT_USER_ID}`) {
//         localStorage.removeItem(key);
//     }
// });

// Удаляет старые ошибки
function clearErrors() {
    document.querySelectorAll('.seed-error').forEach(el => el.remove());
}

// Показывает ошибку под заголовком
function showError(message) {
    clearErrors();
    const errorDiv = document.createElement('div');
    errorDiv.className = 'seed-error';
    errorDiv.innerHTML = `<div style="color:#ff6666; background:#222; padding:12px; border-radius:8px; margin:15px 0; border:1px solid #ff4444;">${message}</div>`;
    document.querySelector('.seed-setup-box h2').after(errorDiv);
}

async function confirmWithSeed() {
    clearErrors();
    const phrase = document.getElementById('seedPhraseInput').value.trim();
    if (!phrase) {
        showError('{{ __("auth.seed_required") }}');
        return;
    }

    try {
        const keys = deriveBoxKeyPairFromSeedPhrase(phrase);

        const res = await fetch('/setup-keys/verify', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ public_key: keys.public_key })
        });

        const data = await res.json();

        if (res.ok && data.status === 'success') {
            localStorage.setItem(`userPrivateKey_${CURRENT_USER_ID}`, keys.secret_key);
            const intended = '{{ session()->get("url.intended", "/chats") }}';
            window.location.href = intended;
        } else {
            // Показываем ошибку без перезагрузки
            showError(data.message || '{{ __("auth.invalid_seed_phrase") }}');
        }
    } catch (e) {
        console.error('Ошибка сети:', e);
        showError('{{ __("auth.network_error") }}');
    }
}
</script>
@endsection