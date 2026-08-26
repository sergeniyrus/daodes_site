# DAODES Monitoring Rules
## File: ai/rules/15_MONITORING_RULES.md

Version: 1.0

Status: Permanent

Priority: Critical (Observability & Self-Healing Layer)

---

# PURPOSE

This document defines monitoring rules for DAODES system.

System MUST be observable, measurable and self-healing.

---

# CORE PRINCIPLE

If system cannot be observed → it cannot be trusted.

Monitoring is mandatory for all critical components.

---

# MONITORED COMPONENTS

System MUST monitor:

- Laravel API
- IPFS node
- Ollama AI engine
- Queue workers
- Database
- Messaging system
- Wallet service
- Cache layer

---

# HEALTH CHECK RULE

Each component MUST expose:

- status (up/down)
- response time
- error rate
- last heartbeat

---

# OLLAMA MONITORING RULE

Track:

- model load status
- VRAM usage
- inference latency
- active model (qwen3 / coder)
- crash events

If model fails → auto-restart via service.

---

# IPFS MONITORING RULE

Track:

- node availability
- CID resolution time
- pinned data integrity
- disk usage
- sync status

If IPFS fails → restart service and verify data integrity.

---

# LARAVEL MONITORING RULE

Track:

- API response time
- error rate
- queue backlog
- database connectivity
- request throughput

---

# DATABASE MONITORING RULE

Track:

- slow queries
- connection pool usage
- deadlocks
- replication lag (if exists)

---

# QUEUE MONITORING RULE

Track:

- failed jobs
- retry count
- queue length
- processing time

If queue is stuck → restart worker service.

---

# MEMORY MONITORING RULE

Track:

- RAM usage
- swap usage
- memory leaks in long-running processes
- worker saturation

If memory threshold exceeded → restart affected service.

---

# VRAM MONITORING RULE

For GPU (GTX 1660 SUPER):

- monitor VRAM usage
- detect model overload
- prevent simultaneous heavy models
- trigger model unload when idle

---

# ALERT SYSTEM RULE

System MUST send alerts on:

- service down
- repeated failures
- memory overload
- IPFS failure
- wallet anomalies
- API degradation

---

# ALERT CHANNELS

Allowed channels:

- Telegram bot
- VK notifications
- server logs
- optional email (future)

---

# AUTO-RECOVERY RULE

On failure detection:

1. restart service
2. verify health
3. retry operation
4. escalate alert if failure persists

---

# SELF-HEALING RULE

System MUST attempt to:

- restart crashed services
- clear corrupted cache
- reload models
- reinitialize queues

Without manual intervention when safe.

---

# LOGGING RULE

Logs MUST include:

- timestamp
- service name
- error type
- severity level

Never log sensitive data:

- seeds
- private keys
- encryption output

---

# METRICS RULE

System SHOULD expose:

- uptime
- latency
- throughput
- error rate
- resource usage

---

# PERFORMANCE ALERT RULE

Trigger alert if:

- API latency > threshold
- queue backlog grows
- IPFS delay increases
- AI inference slows significantly

---

# SECURITY MONITORING RULE

Detect:

- unauthorized API access
- CID probing attempts
- wallet brute force attempts
- abnormal request patterns

---

# DASHBOARD RULE (FUTURE)

System SHOULD support:

- real-time monitoring dashboard
- service status overview
- AI model status panel
- messaging system health

---

# ANTI-PATTERNS

Never:

- run system without monitoring
- ignore service failures
- disable alerts silently
- log sensitive data
- rely only on manual checks

---

# AI MONITORING RULE

AI MUST:

1. interpret system health signals
2. detect anomalies
3. suggest recovery actions
4. avoid false-positive escalation

---

# FINAL RULE

Monitoring is not optional.

System without monitoring is considered broken by design.

---

END OF DOCUMENT