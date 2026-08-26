# DAODES Core Rules
## File: ai/rules/01_CORE_RULES.md

Version: 1.0

Status: Permanent

Priority: Highest

---

# PURPOSE

This document defines the permanent development rules of the DAODES project.

These rules are mandatory for every AI model and every developer working on the project.

If another document conflicts with this file, this file has priority unless explicitly overridden by the project owner.

---

# PROJECT PHILOSOPHY

DAODES is a production project.

It is not a demonstration project.

It is not a tutorial.

It is not a code playground.

Every generated line of code must be suitable for production.

Every architectural decision must improve the project.

The project must remain maintainable for many years.

---

# PRIMARY DEVELOPMENT PRINCIPLES

Always preserve:

• Stability

• Security

• Readability

• Scalability

• Extensibility

• Performance

• Maintainability

Never sacrifice architecture for a quick solution.

---

# LARAVEL FRAMEWORK

The project uses Laravel 11.

Every solution must be compatible with Laravel 11.

Always prefer Laravel-native solutions over custom implementations.

Preferred order:

Laravel Service

↓

Laravel Event

↓

Laravel Job

↓

Laravel Middleware

↓

Laravel Notification

↓

Laravel Queue

↓

Laravel Scheduler

↓

Custom implementation

---

# ARCHITECTURE

Use layered architecture.

User

↓

Route

↓

Controller

↓

Service

↓

Model

↓

Database / IPFS

Controllers must remain thin.

Business logic belongs inside Services.

Models represent data.

Views never contain business logic.

---

# SERVICE LAYER

Every business operation belongs inside a Service.

Controllers must not contain complex logic.

Controllers must never communicate directly with IPFS.

Controllers must never contain encryption logic.

Controllers must never contain wallet logic.

---

# CODE REUSE

Before creating new code:

Search for an existing implementation.

If similar functionality exists:

Extend it.

Reuse it.

Improve it.

Never duplicate logic.

---

# FILE MODIFICATION RULES

When modifying code:

Preserve existing architecture.

Preserve existing naming.

Preserve compatibility.

Never remove existing functionality unless explicitly requested.

Never rewrite an entire subsystem if a small change solves the problem.

---

# FULL FILE POLICY

When generating code:

Prefer returning the complete file.

If only a fragment is required:

Specify:

- file path

- insertion location

- surrounding code

Never require the user to guess where code belongs.

---

# ROUTES

Never rename routes.

Never delete routes.

Never change route names without explicit request.

Preserve route compatibility.

---

# VARIABLES

Never rename variables unnecessarily.

Never rename public methods.

Never rename classes.

Never rename namespaces unless required.

---

# DATABASE

Database schema is valuable.

Never remove tables.

Never remove columns.

Never remove indexes.

Never remove foreign keys.

Never change primary keys.

Always create migrations for structural changes.

Never edit old migrations on production projects.

---

# DATA SAFETY

User data is critical.

Never recommend destructive operations.

Never delete user information.

Always preserve backward compatibility.

---

# DEPENDENCY INJECTION

Always use Dependency Injection.

Never instantiate Services manually if Laravel can inject them.

Avoid static helpers when Services are available.

---

# CONFIGURATION

Never hardcode:

Passwords

Tokens

Secrets

API Keys

Domains

Paths

Use configuration files and environment variables.

---

# VALIDATION

Always validate input.

Prefer FormRequest.

Never trust client data.

Never skip validation.

---

# AUTHORIZATION

Never bypass authorization.

Respect middleware.

Respect policies.

Respect guards.

---

# ERROR HANDLING

Never ignore exceptions.

Handle expected failures.

Return meaningful responses.

Log important errors.

Do not expose sensitive information.

---

# LOGGING

Log only useful information.

Never log:

Passwords

Private keys

Seed phrases

Access tokens

Sensitive personal information

---

# PERFORMANCE

Avoid unnecessary database queries.

Avoid N+1 problems.

Prefer eager loading.

Cache expensive operations.

Avoid duplicate work.

---

# CACHING

Use Laravel Cache when appropriate.

Invalidate cache correctly.

Never cache sensitive user information without proper protection.

---

# FILE STORAGE

Always use Laravel Storage abstraction unless project architecture requires another approach.

Never hardcode filesystem paths.

---

# TESTABILITY

Generated code must be testable.

Avoid tightly coupled components.

Use dependency injection.

Separate responsibilities.

---

# READABILITY

Code is read more often than written.

Write code that another developer can understand quickly.

Avoid unnecessary complexity.

---

# DOCUMENTATION

Complex decisions should be documented.

Architecture changes should be explained.

Generated code should remain understandable.

---

# REFACTORING

Refactoring must preserve behaviour.

Never introduce breaking changes.

Small improvements are preferred over complete rewrites.

---

# SECURITY

Security has priority over convenience.

Never weaken authentication.

Never weaken authorization.

Never expose internal implementation details.

Never expose secrets.

---

# AI RESPONSE FORMAT

Preferred response structure:

1. Problem analysis

2. Existing architecture impact

3. Proposed solution

4. Complete implementation

5. File locations

6. Why this solution is safe

---

# PROJECT OWNER PREFERENCES

The project owner prefers:

• Complete files whenever possible.

• Russian explanations.

• English code.

• Exact insertion locations.

• Laravel-native architecture.

• Existing routes preserved.

• Existing variable names preserved.

• Existing method names preserved.

• Minimal breaking changes.

These preferences must always be respected.

---

# PROHIBITED ACTIONS

Never generate:

Python scripts

Pseudo code

Placeholder implementations

Unfinished classes

"... remaining code ..."

"... implement yourself ..."

Incomplete files

Dummy examples instead of production code

---

# QUALITY CHECKLIST

Before producing any answer verify:

✓ Architecture preserved

✓ Laravel 11 compatible

✓ Existing functionality preserved

✓ Security preserved

✓ Performance acceptable

✓ Naming preserved

✓ No duplicated logic

✓ Production-ready implementation

✓ Complete answer

Only after all checks produce the final response.

---

END OF DOCUMENT