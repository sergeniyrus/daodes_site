# DAODES Deployment Rules
## File: ai/rules/14_DEPLOYMENT_RULES.md

Version: 1.0

Status: Permanent

Priority: Critical (Production Stability Layer)

---

# PURPOSE

This document defines deployment rules for DAODES system.

Deployment MUST be:

- repeatable
- safe
- reversible
- observable

---

# CORE PRINCIPLE

Deployments MUST NEVER break production.

If deployment is unsafe → it is rejected.

---

# DEPLOYMENT FLOW RULE

Correct flow:

1. Build changes
2. Run code review
3. Validate dependencies
4. Backup current state
5. Deploy new version
6. Run health checks
7. Monitor logs

---

# ZERO DOWNTIME RULE

Deployment MUST NOT:

- stop messaging system
- break API availability
- interrupt IPFS access
- unload wallet service

System must remain operational during update.

---

# BACKUP RULE

Before deployment:

- database backup MUST be created
- config backup MUST be created
- critical services state MUST be saved

Backups MUST be restorable.

---

# ROLLBACK RULE

Every deployment MUST support rollback:

- previous version must remain available
- rollback must be single command/action
- rollback must restore full system state

---

# SERVICE MANAGEMENT RULE

IMPORTANT:

System does NOT use systemctl.

Use:

- service command
- start-stop-daemon
- custom scripts

All services MUST be restartable via service.

---

# OLLAMA DEPLOYMENT RULE

AI layer (Ollama):

- must auto-start on boot
- must restart on crash
- must not load all models at once

Models:

- qwen3:8b → architecture reasoning
- qwen2.5-coder → code generation

Only load required model per task.

---

# IPFS DEPLOYMENT RULE

IPFS node MUST:

- auto-restart on failure
- persist data volume
- maintain CID integrity after restart
- not lose pinned data during deploy

---

# LARAVEL DEPLOYMENT RULE

Laravel system MUST:

- clear cache safely
- run migrations only if validated
- never run destructive migrations automatically

---

# ENVIRONMENT RULE

All sensitive config:

- MUST be in .env
- MUST NOT be hardcoded
- MUST be version controlled carefully (excluding secrets)

---

# CONFIGURATION RULE

Config changes MUST:

- be validated before deployment
- not break API contracts
- not break wallet encryption
- not affect CID logic

---

# HEALTH CHECK RULE

After deployment system MUST verify:

- API responds
- database is reachable
- IPFS is accessible
- Ollama is running
- messaging system works

---

# LOGGING RULE

Deployment logs MUST include:

- start time
- version deployed
- service status
- errors if any

Never log sensitive data.

---

# AUTO-RECOVERY RULE

If failure detected:

- restart affected service
- retry deployment step
- rollback if necessary
- notify monitoring system

---

# CI/CD RULE (OPTIONAL FUTURE)

If CI/CD is used:

- must run code review rules first
- must validate performance rules
- must not deploy unreviewed code

---

# SECURITY RULE

Deployment MUST NOT:

- expose secrets
- expose seed phrases
- expose encryption keys
- expose internal IPFS structure

---

# PERFORMANCE RULE

Deployment MUST:

- avoid heavy downtime operations
- not overload CPU during restart
- stagger service restarts

---

# ANTI-PATTERNS

Never:

- deploy without backup
- deploy without rollback plan
- restart all services at once
- skip health checks
- bypass review system

---

# AI DEPLOYMENT RULE

AI MUST:

1. Predict deployment impact
2. Validate safety rules
3. Ensure rollback availability
4. Avoid risky migrations
5. Prefer incremental rollout

---

# FINAL RULE

Deployment is a controlled risk process.

If risk is unknown → do not deploy.

---

END OF DOCUMENT