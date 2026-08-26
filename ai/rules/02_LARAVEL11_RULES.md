# DAODES Laravel 11 Rules
## File: ai/rules/02_LARAVEL11_RULES.md

Version: 1.0

Status: Permanent

Priority: High

---

# PURPOSE

This document defines strict Laravel 11 development rules for the DAODES project.

It ensures consistent architecture, maintainability, and scalability.

All AI models and developers MUST follow these rules.

---

# FRAMEWORK VERSION

The project uses:

Laravel 11
PHP 8.2+

No compatibility with older Laravel versions is required.

---

# CORE ARCHITECTURE

The project follows layered architecture:

Request
→ Route
→ Controller
→ Service
→ Model
→ Database / IPFS

Rules:

- Controllers MUST be thin
- Business logic MUST be in Services
- Models MUST represent data only
- No logic in Blade templates

---

# CONTROLLERS

Controllers rules:

- Must not contain business logic
- Must not interact with IPFS directly
- Must not perform encryption
- Must delegate logic to Services

Allowed responsibilities:

- Request validation trigger
- Service calling
- Response formatting

Example structure:

- UserController
- MessageController
- WalletController

Controllers must remain small and readable.

---

# SERVICES

Services are the core of business logic.

Rules:

- Every feature MUST have a Service if logic exists
- Services MUST be reusable
- Services MUST NOT depend on HTTP layer
- Services MUST be injectable via constructor
- Services MUST be stateless when possible

Examples:

- MessageService
- IPFSService
- WalletService
- EncryptionService

---

# MODELS

Models represent database structure only.

Rules:

- No business logic inside models
- No external API calls
- No IPFS calls
- Only relationships and accessors/mutators

Allowed:

- relationships
- casts
- scopes (simple queries only)

---

# FORM REQUESTS

Always use FormRequest for validation.

Rules:

- Never validate inside controllers
- Always separate validation logic
- Keep rules reusable and clean

Example:

- StoreMessageRequest
- UpdateProfileRequest

---

# EVENTS & LISTENERS

Use events for decoupling logic.

Rules:

- Events MUST represent domain actions
- Listeners MUST handle side effects
- Never put business logic in controllers instead of events

Examples:

- MessageCreated
- WalletUpdated
- UserRegistered

---

# JOBS & QUEUES

Use Jobs for heavy operations.

Rules:

- IPFS operations MUST use Jobs if slow
- Notifications SHOULD use Jobs
- Long-running tasks MUST use queues

Never block HTTP request with heavy operations.

---

# MIDDLEWARE

Middleware is for request filtering only.

Rules:

- Authentication
- Authorization
- Rate limiting
- Logging

No business logic in middleware.

---

# API DESIGN

Rules:

- Use REST principles
- Keep endpoints predictable
- Do not break existing routes
- Use versioning if needed (v1, v2)

Responses must be consistent.

---

# DATABASE RULES

Rules:

- Use migrations for all changes
- Never modify production migrations
- Never drop columns without explicit request
- Use foreign keys where possible
- Index important query fields

---

# ELOQUENT RULES

Rules:

- Always eager load relationships when needed
- Avoid N+1 queries
- Use scopes for reusable queries
- Avoid raw SQL unless necessary

---

# IPFS INTEGRATION RULES

Critical rule:

- All message content is stored in IPFS
- Database stores ONLY CID reference

Never store full message text in database.

---

# ENCRYPTION RULES

- Encryption MUST be handled by EncryptionService
- Never implement custom encryption in controllers
- Never bypass encryption layer

---

# PERFORMANCE RULES

- Avoid unnecessary queries
- Use caching for heavy reads
- Use pagination for large datasets
- Use queue for heavy tasks

---

# SECURITY RULES

- Always validate input
- Always authorize actions
- Never trust frontend
- Never expose internal errors
- Never expose sensitive data

---

# ROUTING RULES

- Never rename routes without explicit request
- Never remove routes silently
- Keep API stable
- Group routes logically

---

# BLADE RULES

- No business logic in Blade
- No database queries in Blade
- Use components where possible
- Keep templates clean

---

# CODING STYLE

- Follow PSR-12
- Use strict typing
- Use dependency injection
- Prefer interfaces where possible

---

# NAMING CONVENTIONS

- Controllers: PascalCase + Controller
- Services: PascalCase + Service
- Models: PascalCase singular
- Methods: camelCase
- Variables: camelCase
- Database tables: snake_case plural

---

# ERROR HANDLING

- Always handle exceptions
- Never silence errors
- Log critical failures
- Return clean API responses

---

# TESTABILITY

All code must be testable.

Rules:

- Use DI
- Avoid static calls
- Separate concerns

---

# CACHE RULES

- Cache expensive queries
- Always invalidate properly
- Do not cache sensitive data

---

# FORBIDDEN PRACTICES

Never:

- Put logic in controllers
- Store messages in DB instead of IPFS
- Skip validation
- Bypass services
- Hardcode secrets
- Break existing routes
- Rewrite working systems unnecessarily

---

# AI DEVELOPMENT RULE

Before writing code:

1. Check existing implementation
2. Reuse services if possible
3. Avoid duplication
4. Respect architecture
5. Prefer extension over rewrite

---

# FINAL RULE

Laravel is the foundation of DAODES backend.

All features must align with Laravel architecture principles.

---

END OF DOCUMENT