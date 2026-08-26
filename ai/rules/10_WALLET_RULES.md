# DAODES Wallet Rules
## File: ai/rules/10_WALLET_RULES.md

Version: 1.0

Status: Permanent

Priority: Critical (Financial & Security Core)

---

# PURPOSE

This document defines all rules for wallet system in DAODES.

Wallet system handles sensitive financial data and cryptographic material.

Security is ABSOLUTE priority.

---

# CORE PRINCIPLE

Wallet data MUST NEVER be exposed, leaked, or stored insecurely.

Encryption is mandatory.

No exceptions.

---

# SEED PHRASE RULE (CRITICAL)

Seed phrases:

- MUST NEVER be stored in plaintext
- MUST NEVER be logged
- MUST NEVER be transmitted unencrypted
- MUST be encrypted via EncryptionService ONLY

If encryption is not available → operation MUST be rejected.

---

# WALLET STORAGE RULE

Database MAY store ONLY:

- wallet_id
- user_id
- address
- encrypted_seed
- created_at
- updated_at

NEVER store:

- raw seed phrase
- private key
- mnemonic words unencrypted

---

# ENCRYPTION RULE

All sensitive wallet data MUST use:

EncryptionService

Rules:

- no custom encryption
- no inline crypto logic
- no third-party unsafe crypto wrappers
- centralized encryption only

---

# TRANSACTION RULE

Wallet transactions MUST:

- be validated
- be logged
- be signed securely
- never bypass service layer

Transactions are ALWAYS service-driven.

---

# SIGNING RULE

All signing operations:

- MUST occur server-side when possible
- MUST be verified before execution
- MUST NOT expose private keys

Frontend MUST NEVER handle signing logic directly.

---

# BALANCE RULE

Balance handling:

- can be cached
- MUST NOT be primary source of truth
- blockchain or external system is source of truth (if applicable)

Cache must always be synchronizable.

---

# SECURITY RULE (CRITICAL)

Wallet system MUST protect against:

- key leakage
- seed exposure
- unauthorized access
- brute-force attacks
- injection attacks
- CID-based exploitation (if linked)

---

# ACCESS CONTROL RULE

Wallet access MUST:

- require authentication
- require authorization
- validate ownership
- prevent cross-user access

User can only access OWN wallet.

---

# API RULE FOR WALLETS

Wallet API MUST:

- never expose private data
- always return sanitized responses
- never return seed phrase
- enforce strict rate limiting

---

# LOGGING RULE

NEVER log:

- seed phrases
- private keys
- encryption output
- signing payloads

Allowed logs:

- transaction IDs
- wallet addresses (partial masked if needed)
- system events

---

# IPFS RULE

Wallet data MUST NOT be stored in IPFS unless encrypted.

Even then:

- must be strictly controlled
- must not expose sensitive cryptographic material

---

# TRANSACTION SAFETY RULE

Every transaction MUST:

1. Validate user ownership
2. Validate balance or permissions
3. Apply encryption if needed
4. Execute via service layer
5. Log result safely

---

# ERROR HANDLING RULE

Wallet errors MUST:

- never expose cryptographic details
- never reveal internal logic
- return generic safe messages

Example:

❌ BAD:
"invalid private key format"

✔ GOOD:
"Transaction failed. Please try again."

---

# RATE LIMIT RULE

Wallet operations MUST be rate limited:

- transfers
- signing
- balance queries

Prevent brute-force attacks.

---

# MULTI-WALLET RULE (FUTURE)

System MUST support:

- multiple wallets per user
- wallet switching
- isolated wallet security domains

---

# RECOVERY RULE

Recovery processes MUST:

- require strong authentication
- require encryption validation
- never expose full seed phrase in UI
- be heavily logged internally

---

# ANTI-PATTERNS

Never:

- store seed in plaintext
- expose private keys
- handle crypto in frontend
- bypass EncryptionService
- skip authorization checks
- allow CID-based wallet guessing

---

# AI RULE FOR WALLET SYSTEM

When generating wallet-related code:

1. Ensure EncryptionService is used
2. Ensure no plaintext secrets exist
3. Ensure strict authorization
4. Ensure no frontend crypto logic
5. Ensure full audit logging
6. Ensure service-layer isolation

---

# FINAL RULE

Wallet system is the most sensitive part of DAODES.

Any compromise here compromises entire platform.

Security is non-negotiable.

---

END OF DOCUMENT