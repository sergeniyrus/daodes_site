# DAODES Refactor Rules
## File: ai/rules/12_REFACTOR_RULES.md

Version: 1.0

Status: Permanent

Priority: High (System Evolution Layer)

---

# PURPOSE

This document defines rules for refactoring DAODES system.

Refactoring MUST improve structure WITHOUT breaking existing behavior.

---

# CORE PRINCIPLE

Refactor = Improve, not Rewrite.

Never rewrite working systems unnecessarily.

---

# SAFETY FIRST RULE

Before any refactor:

1. Understand current behavior
2. Identify dependencies
3. Ensure backward compatibility
4. Verify IPFS integrity
5. Check API contracts

If any step is unclear → do not refactor.

---

# NO BREAKING CHANGES RULE

Refactoring MUST NOT:

- break existing routes
- break API contracts
- change CID logic
- modify database structure without migration
- alter wallet encryption logic
- change message flow behavior

---

# INCREMENTAL CHANGE RULE

Refactoring MUST be:

- small steps
- isolated changes
- testable increments
- reversible when possible

Never perform large uncontrolled rewrites.

---

# FILE MODIFICATION RULE

When modifying files:

- preserve existing structure
- preserve function names unless necessary
- preserve public interfaces
- preserve service boundaries

If change is large → split into phases.

---

# SERVICE PRESERVATION RULE

Services are core architecture.

Never:

- merge unrelated services
- remove service responsibility without replacement
- bypass service layer during refactor

---

# IPFS SAFETY RULE

During refactor:

- NEVER change CID generation logic without migration strategy
- NEVER alter message storage flow directly
- NEVER bypass IPFS layer

---

# DATABASE SAFETY RULE

Refactoring MUST:

- use migrations for schema changes
- never modify production migrations
- never delete columns without migration path
- preserve foreign keys

---

# API COMPATIBILITY RULE

API must remain stable:

- existing endpoints must work after refactor
- response structure must remain consistent
- versioning must be used for changes

---

# MESSENGER SAFETY RULE

During refactor of messaging system:

- preserve CID-based architecture
- preserve reply structure
- preserve message lifecycle
- preserve real-time flow

---

# WALLET SAFETY RULE

Wallet system refactor MUST:

- never expose seed phrases
- never change encryption logic without migration plan
- never break signing flow

---

# TESTING RULE

Before applying refactor:

- simulate changes mentally or in staging
- validate edge cases
- ensure rollback path exists

---

# DEPENDENCY RULE

Before refactor:

- check existing service usage
- identify hidden dependencies
- ensure no orphaned calls remain

---

# REFACTOR STRATEGY RULE

Preferred refactor order:

1. Add new implementation alongside old
2. Migrate usage gradually
3. Deprecate old logic
4. Remove only after full transition

---

# CODE QUALITY RULE

Refactor must improve:

- readability
- maintainability
- performance (if possible)
- security (never degrade)

---

# ANTI-PATTERNS

Never:

- rewrite entire modules in one step
- remove working functionality “for cleanup”
- change architecture during refactor
- ignore dependency chains
- refactor without understanding system impact

---

# AI REFACTOR RULE

AI MUST:

1. Analyze current structure
2. Identify risks
3. Propose minimal change set
4. Preserve compatibility
5. Avoid unnecessary abstraction

---

# VERSIONING RULE

Major refactors MUST:

- introduce versioning if needed
- preserve old behavior until migration complete
- document changes clearly

---

# FINAL RULE

Refactoring is evolution, not destruction.

System must always remain functional during refactor.

---

END OF DOCUMENT