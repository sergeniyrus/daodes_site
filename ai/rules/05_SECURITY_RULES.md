# DAODES Security Rules
## File: ai/rules/05_SECURITY_RULES.md

Version: 1.0

Status: Permanent

Priority: Critical (Security Layer)

---

# PURPOSE

This document defines all security rules for the DAODES project.

Security has the highest priority after data integrity.

If any feature conflicts with security rules — security ALWAYS wins.

---

# CORE SECURITY PRINCIPLE

NEVER TRUST ANY INPUT.

All data is considered untrusted until validated, authorized, and sanitized.

---

# AUTHENTICATION RULES

- Every request MUST be authenticated where required
- Never bypass authentication middleware
- Never expose authentication tokens
- Never log credentials

Allowed mechanisms:

- Laravel Auth
- Sanctum / Passport (if used)
- Session-based auth

---

# AUTHORIZATION RULES

- Always verify user permissions before actions
- Never trust client-side role flags
- Use Policies and Gates
- Check ownership of resources (messages, wallets, CID access)

Example:

User MUST own message before accessing CID.

---

# IPFS SECURITY RULES

- CID access MUST be validated
- Never allow arbitrary CID fetch without permission check
- Decrypt content ONLY after authorization
- Do not expose raw IPFS endpoints directly to frontend

---

# ENCRYPTION RULES

All sensitive data MUST be encrypted using:

EncryptionService ONLY

Never implement custom encryption logic.

Never store:

- seed phrases
- private keys
- wallet recovery data
- tokens in plaintext
- sensitive metadata unencrypted

---

# WALLET SECURITY RULES

- Wallet seed phrases MUST NEVER be stored in plain text
- Wallet access MUST require authentication
- Wallet operations MUST be logged
- Signing operations MUST be server-verified when possible

---

# API SECURITY RULES

- All API endpoints MUST validate input
- Rate limiting MUST be enabled for public endpoints
- Never expose internal errors to clients
- Always return sanitized error messages

---

# INPUT VALIDATION RULES

- Always use FormRequest when possible
- Never trust request payloads
- Validate all fields strictly
- Reject unexpected fields

---

# LOGGING SECURITY RULES

Never log:

- passwords
- tokens
- encryption keys
- seed phrases
- private CID content
- sensitive user data

Logs must be safe for production exposure.

---

# IP EXPOSURE RULES

- Do not expose internal server IPFS nodes
- Do not expose internal service architecture
- Do not expose debug endpoints in production

---

# SESSION SECURITY RULES

- Sessions must be secure and HTTP-only
- Sessions must expire properly
- Session hijacking must be mitigated

---

# CSRF RULES

- CSRF protection MUST be enabled for web routes
- API routes must use token-based protection
- Never disable CSRF without explicit reason

---

# FILE UPLOAD SECURITY

- Validate all uploaded files
- Restrict file size
- Restrict file types
- Store files in IPFS only after validation

---

# ATTACK SURFACE REDUCTION

System must protect against:

- SQL Injection
- XSS
- CSRF
- Command Injection
- CID Injection
- Mass assignment
- Unauthorized IPFS access

---

# MASS ASSIGNMENT RULE

- Always use guarded or fillable models
- Never allow uncontrolled request->all() usage
- Validate before model assignment

---

# ERROR HANDLING SECURITY RULE

- Never expose stack traces to users
- Never expose internal system structure
- Log internally only

User-facing errors must be generic.

---

# RATE LIMITING RULE

- API endpoints MUST be rate limited
- IPFS upload endpoints MUST be protected
- Wallet operations MUST be strictly limited

---

# ADMIN SECURITY RULES

Admin access must:

- require elevated authentication
- be logged
- be protected by middleware
- never be exposed publicly without restriction

---

# DEPENDENCY SECURITY RULE

- Avoid unsafe or unmaintained packages
- Prefer Laravel native features
- Audit external dependencies before use

---

# AI SECURITY RULE

AI must never:

- suggest insecure bypasses
- weaken authentication
- disable validation
- expose sensitive logic
- recommend unsafe shortcuts

---

# CRYPTOGRAPHY RULE

- Use Laravel-supported encryption
- Never implement custom crypto algorithms
- Never store raw keys in code

---

# FRONTEND SECURITY RULE

- Never trust frontend validation alone
- Always validate on backend
- Never expose sensitive API keys in frontend

---

# CID SECURITY RULE

- CID must be treated as sensitive identifier
- CID access must always be authorized
- CID must never be guessed or enumerated

---

# SECURITY BY DESIGN

Security is not a feature.

Security is a foundation.

Every new feature MUST pass security review.

---

# FINAL RULE

If there is any uncertainty:

→ choose secure behavior
→ reject unsafe operation
→ log internally
→ fail safely

---

END OF DOCUMENT