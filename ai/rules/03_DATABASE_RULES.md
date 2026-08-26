# DAODES Database Rules
## File: ai/rules/03_DATABASE_RULES.md

Version: 1.0

Status: Permanent

Priority: Highest (Data Layer)

---

# PURPOSE

This document defines all database rules for the DAODES project.

It ensures data integrity, consistency, scalability and strict separation between database and IPFS storage.

---

# CORE PRINCIPLE

DATABASE IS NOT STORAGE FOR CONTENT.

Database stores only references (CID), metadata and relations.

All heavy or user-generated content MUST be stored in IPFS.

---

# CRITICAL RULE

Messages and large content:

- MUST NOT be stored in database
- MUST be stored ONLY in IPFS
- Database stores ONLY CID reference

Example:

✔ database:
- id
- user_id
- cid
- created_at

✖ forbidden:
- message_text
- full content
- large payloads

---

# DATA ARCHITECTURE

Database acts as:

- index layer
- relation layer
- metadata layer

IPFS acts as:

- content storage layer

Flow:

User Input
→ IPFS storage
→ CID returned
→ CID saved in DB
→ DB used for retrieval

---

# TABLE DESIGN RULES

All tables MUST follow:

- clear purpose
- single responsibility
- normalized structure
- no duplicated data

Each table MUST represent one domain concept.

---

# USER TABLE

Users table contains ONLY:

- id
- name
- email
- password
- wallet_address (optional)
- created_at
- updated_at

No sensitive data should be stored without encryption.

---

# MESSAGES TABLE

Messages table MUST NOT contain message text.

Allowed fields:

- id
- sender_id
- receiver_id
- cid
- reply_to (nullable)
- status
- created_at
- updated_at

Message content is retrieved from IPFS using CID.

---

# WALLET TABLE

Wallet table contains only metadata:

- id
- user_id
- address
- encrypted_seed (via EncryptionService)
- balance_cache (optional)
- created_at
- updated_at

NEVER store raw seed phrase.

---

# RELATIONSHIPS

Rules:

- Always use foreign keys
- Always define relationships in Eloquent
- Always index foreign keys

Examples:

- user_id → users.id
- message.sender_id → users.id

---

# MIGRATION RULES

- All schema changes MUST use migrations
- Never edit production migrations
- Never delete migrations in production
- Always create new migration for changes

---

# INDEXING RULES

Indexes MUST be created for:

- user_id
- message sender_id
- message receiver_id
- created_at timestamps
- frequently queried CID fields

Avoid unnecessary indexes.

---

# QUERY RULES

- Avoid N+1 queries
- Always use eager loading when needed
- Use pagination for large datasets
- Never load full dataset without limit

---

# DATA INTEGRITY

Rules:

- No orphan records
- Always enforce foreign keys
- Use cascading rules carefully
- Avoid accidental deletions

---

# SOFT DELETE POLICY

Use soft deletes ONLY when necessary.

Do not use soft delete for:

- messages stored in IPFS
- system logs

Use only for:

- user profiles
- reversible data

---

# IPFS INTEGRATION RULE

Database MUST NOT contain:

- message text
- file contents
- large payloads

Database MUST contain:

- CID reference only

---

# ENCRYPTION RULE

Sensitive fields MUST be encrypted via:

EncryptionService

Never store:

- raw seed phrases
- private keys
- tokens in plaintext

---

# PERFORMANCE RULES

- Use indexed queries
- Avoid full table scans
- Use caching for repeated reads
- Use pagination for large datasets

---

# SCALABILITY RULES

Database must be designed for:

- horizontal scaling
- high read volume
- distributed storage via IPFS

---

# ANTI-PATTERNS

Never:

- store large text fields in DB
- duplicate IPFS content in DB
- bypass relationships
- use unindexed foreign keys
- perform heavy joins without optimization

---

# SECURITY RULES

- Always sanitize input
- Always validate data
- Never trust client-side data
- Never expose internal schema

---

# BACKUP STRATEGY

- DB is metadata only → lightweight backups
- IPFS stores content → distributed persistence
- Both must be backed up independently

---

# VERSIONING RULE

Schema changes MUST be versioned via migrations.

Never overwrite existing structure silently.

---

# AI RULE FOR DATABASE

Before generating DB changes:

1. Check existing schema
2. Avoid duplication
3. Prefer extension over rewrite
4. Preserve relationships
5. Never break CID logic

---

# FINAL RULE

Database is NOT the system of record for content.

IPFS is the system of record for content.

Database is only an index layer.

---

END OF DOCUMENT