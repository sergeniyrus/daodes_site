(function () {
    // === Base64 ⇄ Uint8Array ===
    function b64ToU8(b64) {
        return Uint8Array.from(atob(b64), (c) => c.charCodeAt(0));
    }
    function u8ToB64(u8) {
        return btoa(String.fromCharCode(...u8));
    }

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // === Проверяем приватный ключ ===
    let privKey = localStorage.getItem("userPrivateKey");

    if (!privKey) {
        console.log("🔑 Нет приватного ключа — генерируем новый");

        const pair = nacl.box.keyPair();
        privKey = u8ToB64(pair.secretKey);
        const pubKey = u8ToB64(pair.publicKey);

        localStorage.setItem("userPrivateKey", privKey);

        fetch("/profile/set-public-key", {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf,
            },
            body: JSON.stringify({ public_key: pubKey }),
        });

        return;
    }

    // === Проверяем публичный ключ на сервере ===
    fetch("/profile/has-public-key", {
        method: "GET",
        credentials: "include",
        headers: { Accept: "application/json" },
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.has_public_key) {
                console.log("🟢 Публичный ключ уже есть на сервере");
                return;
            }

            console.log(
                "🔴 Сервер не имеет публичного ключа — восстанавливаем",
            );

            const secret = b64ToU8(privKey);

            if (secret.length !== 32) {
                console.error("❌ Приватный ключ повреждён. Удаляю…");
                localStorage.removeItem("userPrivateKey");
                return;
            }

            const pair = nacl.box.keyPair.fromSecretKey(secret);
            const pubKey = u8ToB64(pair.publicKey);

            fetch("/profile/set-public-key", {
                method: "POST",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                },
                body: JSON.stringify({ public_key: pubKey }),
            }).then((res) => {
                if (res.ok) {
                    console.log(
                        "🟢 Публичный ключ успешно восстановлен и сохранён",
                    );
                } else {
                    console.error("❌ Ошибка при сохранении:", res.status);
                }
            });
        })
        .catch((err) => console.error("🚨 Ошибка запроса:", err));
})();
