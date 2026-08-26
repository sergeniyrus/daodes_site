# DAODES API Rules
## File: ai/rules/08_API_RULES.md

Version: 1.0

Status: Permanent

Priority: Critical (System Integration Layer)

---

# PURPOSE

This document defines API architecture rules for DAODES.

API is the ONLY communication layer between frontend, backend, IPFS and external systems.

---

# CORE PRINCIPLE

API = SINGLE CONTRACT LAYER

All interactions MUST go through API.

No direct access to:

- database
- IPFS
- services
- internal storage

---

# ARCHITECTURE FLOW

Frontend
→ API Request
→ Controller
→ Service
→ IPFS / Database
→ Response

Response
→ API
→ Frontend

---

# API DESIGN PRINCIPLE

APIs must be:

- predictable
- versioned
- stateless
- secure
- consistent

---

# VERSIONING RULE

All APIs MUST use versioning:

/api/v1/
/api/v2/

Never break existing API contracts.

Always extend, never rewrite silently.

---

# RESPONSE FORMAT RULE

All API responses MUST follow structure:

```json
{
  "status": "success|error",
  "message": "human readable message",
  "data": {},
  "meta": {}
}
```

---

# MESSAGE API RULE (DAODES CORE)

Messages are NOT stored as text.

API MUST:

1. Return CID from database
2. Resolve CID via service
3. Return sanitized content

Never expose raw database structure.

---

# IPFS INTEGRATION RULE

API MUST NOT interact with IPFS directly.

Correct flow:

API → Service → IPFS → Service → API

Never:

API → IPFS (direct)

---

# CONTROLLER RULE

Controllers in API layer:

- must be thin
- must not contain business logic
- must delegate to services
- must return standardized responses

---

# SERVICE LAYER RULE

All logic MUST be inside services:

- MessageService
- IPFSService
- WalletService
- AuthService

API MUST NOT contain logic.

---

# AUTHENTICATION RULE

All protected endpoints MUST:

- validate user
- check token/session
- verify permissions

Unauthorized requests MUST be rejected.

---

# AUTHORIZATION RULE

Every API action MUST validate ownership:

Examples:

- user can only access own messages
- user can only access own wallet
- CID access MUST be permission-based

---

# RATE LIMITING RULE

All API endpoints MUST be rate limited:

- public endpoints: strict limit
- auth endpoints: stricter limit
- IPFS upload endpoints: very strict limit

---

# ERROR HANDLING RULE

API errors MUST:

- never expose internal stack traces
- never expose server structure
- return safe messages

Example:

❌ BAD:
"SQLSTATE error in users table"

✔ GOOD:
"Something went wrong. Please try again later."

---

# SECURITY RULE

API MUST protect against:

- SQL injection
- CID injection
- XSS
- CSRF
- brute force attacks
- unauthorized IPFS access

---

# CID RULE (CRITICAL)

CID is sensitive identifier.

Rules:

- must be validated before use
- must belong to authenticated user
- must never be guessed or enumerated
- must never be exposed without control

---

# PAGINATION RULE

All list endpoints MUST use pagination:

- messages
- transactions
- logs
- notifications

Never return unlimited datasets.

---

# REAL-TIME RULE

For messaging system:

- updates MUST use WebSockets or event system
- API MUST NOT poll continuously

Preferred:

- Laravel Echo
- WebSockets
- Event broadcasting

---

# FILE UPLOAD RULE

All uploads:

- must be validated
- must be processed via service
- must be stored in IPFS only after validation

API MUST NOT store raw files locally permanently.

---

# CONSISTENCY RULE

All endpoints MUST:

- return consistent structure
- use same naming conventions
- follow same error handling rules

---

# LOGGING RULE

API must log:

- authentication failures
- critical actions
- system errors

Never log sensitive data.

---

# CACHE RULE

API SHOULD cache:

- frequent reads
- static metadata
- non-sensitive data

Cache must be invalidated properly.

---

# ANTI-PATTERNS

Never:

- call IPFS directly from controller
- bypass service layer
- return raw DB models
- expose internal system structure
- skip validation
- skip authorization

---

# EXTENSIBILITY RULE

API must allow:

- future version upgrades
- new modules without breaking old ones
- extension without rewriting core logic

---

# AI RULE FOR API

When generating API code:

1. Check existing endpoints
2. Preserve contracts
3. Avoid breaking changes
4. Use services
5. Ensure CID safety
6. Ensure validation & authorization

---

# FINAL RULE

API is the contract of the system.

If API breaks → entire system breaks.

Therefore stability is mandatory.

---

END OF DOCUMENT