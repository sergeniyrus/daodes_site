# DAODES System Bootstrap Rules
## File: ai/rules/16_SYSTEM_BOOTSTRAP_RULES.md

Version: 1.0

Status: Permanent

Priority: Critical (System Initialization Layer)

---

# PURPOSE

This document defines how DAODES system starts from a cold state.

Bootstrap MUST ensure full system recovery without manual reconstruction.

---

# CORE PRINCIPLE

System MUST always be recoverable from zero state.

Bootstrap is deterministic and repeatable.

---

# BOOT SEQUENCE ORDER (STRICT)

System MUST start in this order:

1. Operating system services
2. Database service (MySQL/PostgreSQL)
3. IPFS node
4. Laravel application
5. Queue workers
6. WebSocket / Realtime layer
7. Ollama AI engine
8. Model loading (Qwen2.5-Coder → Qwen3)
9. Cache warm-up
10. Indexing system
11. Monitoring system

---

# OLLAMA BOOT RULE

On startup:

- start Ollama service via `service ollama start`
- verify API availability (127.0.0.1:11434)
- load minimal model first (qwen2.5-coder)
- load qwen3 only when needed

Never load all models simultaneously.

---

# IPFS BOOT RULE

On startup:

- verify IPFS daemon is running
- restore pinned data
- validate CID integrity
- rebuild local cache index if needed

If IPFS fails → retry restart until stable.

---

# LARAVEL BOOT RULE

Laravel MUST:

- clear expired cache safely
- restore queue state
- validate environment config
- ensure DB connection is active

---

# QUEUE BOOT RULE

On startup:

- restart queue workers
- clear stuck jobs (safe retry only)
- resume unfinished tasks

---

# CACHE BOOT RULE

System MUST:

- warm up critical caches
- rebuild frequently accessed data
- preload message metadata

---

# INDEXING BOOT RULE

Continue indexing system MUST:

- verify existing index
- resume indexing if incomplete
- avoid full reindex unless corruption detected

---

# MONITORING BOOT RULE

Monitoring system MUST:

- start last
- verify all services
- register health checks
- begin alerting only after stabilization delay

---

# HEALTH STABILIZATION RULE

System MUST wait until:

- all services are online
- no critical errors detected
- latency stabilizes

Only then system is considered READY.

---

# FAILURE RECOVERY RULE

If any service fails during bootstrap:

1. retry service start
2. log failure
3. escalate if repeated failure
4. attempt fallback recovery
5. rollback if system unstable

---

# PARTIAL START RULE

System MUST allow partial startup:

- if AI fails → system still works without AI
- if IPFS fails → system enters degraded mode
- if queue fails → API still accessible

No single service should block entire system.

---

# SAFE MODE RULE

If multiple failures detected:

System enters SAFE MODE:

- disable AI calls
- disable heavy queue tasks
- allow read-only API access
- keep monitoring active

---

# ORDER OF DEPENDENCIES RULE

Dependencies MUST be respected:

- Laravel depends on DB
- Messaging depends on IPFS + DB
- AI depends on Ollama
- Monitoring depends on all services

---

# AUTO-START RULE

All services MUST support:

- automatic restart on crash
- boot-time auto-launch
- persistent configuration

---

# LOGGING RULE

Bootstrap MUST log:

- service startup order
- success/failure states
- timing metrics
- recovery actions

Never log sensitive data.

---

# PERFORMANCE RULE

Bootstrap MUST:

- avoid loading all models at once
- stagger heavy services
- prevent CPU/GPU spikes at startup

---

# VALIDATION RULE

System is considered READY only if:

✓ DB is reachable  
✓ IPFS is stable  
✓ Laravel API responds  
✓ Queue workers active  
✓ Ollama is running  
✓ Monitoring active  

---

# ANTI-PATTERNS

Never:

- start all services simultaneously
- skip dependency checks
- ignore failed service state
- load full AI stack immediately
- bypass health validation

---

# AI BOOT RULE

AI system MUST:

- start in minimal mode first
- load models on demand
- avoid full context initialization at boot

---

# FINAL RULE

Bootstrap defines system existence.

If bootstrap is incorrect → system is unusable.

---

END OF DOCUMENT