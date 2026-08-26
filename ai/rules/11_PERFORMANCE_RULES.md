# DAODES Performance Rules
## File: ai/rules/11_PERFORMANCE_RULES.md

Version: 1.0

Status: Permanent

Priority: High (Scalability & System Stability)

---

# PURPOSE

This document defines performance rules for DAODES.

System must remain fast, stable, and scalable under increasing load.

---

# CORE PRINCIPLE

Performance is not optimization.

Performance is architecture.

---

# DATABASE PERFORMANCE RULE

- Always use indexes for foreign keys
- Avoid N+1 queries
- Always use eager loading when needed
- Use pagination for all large datasets

Never load unbounded datasets.

---

# CACHING RULE

Use caching for:

- frequently accessed messages
- user profiles
- wallet balances (temporary)
- IPFS resolved content
- API responses (safe data only)

Rules:

- cache must be invalidated correctly
- never cache sensitive data (seed, keys)
- cache must not become source of truth

---

# IPFS PERFORMANCE RULE

- batch CID resolution when possible
- avoid repeated fetch of same CID
- cache resolved IPFS content
- use queue for heavy uploads

IPFS access must be optimized for latency.

---

# QUEUE SYSTEM RULE

All heavy operations MUST use queues:

- IPFS uploads
- message processing
- notifications
- indexing tasks
- AI processing tasks

Never block HTTP requests.

---

# JOB DESIGN RULE

Jobs must be:

- small
- single-purpose
- retry-safe
- idempotent

No monolithic jobs allowed.

---

# REAL-TIME PERFORMANCE RULE

Messaging system MUST:

- use WebSockets or event broadcasting
- avoid polling
- batch updates when possible
- throttle high-frequency events

---

# AI PERFORMANCE RULE

For Ollama models:

- only 1 active model per task when possible
- limit concurrent model loading
- avoid loading unnecessary large models
- prefer Qwen2.5-Coder for code tasks
- use Qwen3 for architecture reasoning only

---

# VRAM / GPU RULE (IMPORTANT)

For GTX 1660 SUPER:

- avoid loading multiple large models simultaneously
- enforce single active inference stream
- release unused models when idle
- limit context window if memory pressure appears

---

# MEMORY RULE

System must:

- monitor memory usage
- avoid memory leaks in long-running workers
- clear caches periodically
- release unused model contexts

---

# CONTEXT RULE (AI SYSTEM)

AI models MUST:

- use minimal necessary context
- avoid loading full codebase unnecessarily
- rely on indexed context when available
- prefer targeted retrieval

---

# INDEXING RULE

Codebase indexing MUST:

- be incremental
- avoid full reindex on small changes
- run in background
- not block system operations

---

# API PERFORMANCE RULE

API MUST:

- be stateless
- use caching for repeated queries
- paginate all lists
- avoid heavy computation in controllers

---

# MESSAGE SYSTEM PERFORMANCE RULE

Messenger MUST:

- paginate message history
- lazy load IPFS content
- cache recent conversations
- batch fetch CIDs
- use async processing for attachments

---

# FILE UPLOAD PERFORMANCE RULE

Uploads MUST:

- be streamed when possible
- be processed asynchronously
- avoid blocking request lifecycle
- be validated before IPFS upload

---

# SCHEDULER RULE

Use Laravel Scheduler for:

- cache cleanup
- old job cleanup
- indexing maintenance
- queue monitoring
- IPFS cache pruning

---

# BOTTLENECK PREVENTION RULE

Never allow:

- synchronous IPFS bulk requests
- blocking AI inference in request cycle
- large DB joins without pagination
- unbounded loops in services

---

# SCALABILITY RULE

System MUST be designed for:

- horizontal scaling
- distributed workers
- external storage (IPFS)
- independent AI inference layer

---

# OBSERVABILITY RULE

System SHOULD track:

- response time
- queue latency
- cache hit rate
- IPFS latency
- AI inference time

---

# ANTI-PATTERNS

Never:

- load all messages at once
- run AI inside request loop
- fetch IPFS repeatedly without cache
- block requests with heavy processing
- run multiple large models simultaneously

---

# AI PERFORMANCE RULE

AI must:

- avoid unnecessary reasoning loops
- prefer structured output
- minimize token usage when possible
- reuse context efficiently

---

# FINAL RULE

Performance is part of architecture design.

If system is slow → architecture is wrong.

---

END OF DOCUMENT