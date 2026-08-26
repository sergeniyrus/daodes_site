# DAODES Code Review Rules
## File: ai/rules/13_CODE_REVIEW_RULES.md

Version: 1.0

Status: Permanent

Priority: Critical (Quality & Safety Gate)

---

# PURPOSE

This document defines rules for code review in DAODES.

Every code change MUST pass this review logic before being applied.

---

# CORE PRINCIPLE

No code is accepted without validation.

Every change must be reviewed for:

- architecture integrity
- security
- performance
- compatibility
- IPFS consistency

---

# REVIEW GATE RULE

Before applying any change:

1. Understand current system behavior
2. Compare with proposed change
3. Check breaking changes
4. Validate dependencies
5. Confirm IPFS/CID safety
6. Verify Laravel compatibility

If any step fails → reject change.

---

# ARCHITECTURE REVIEW RULE

Check:

- Controller remains thin
- Service layer is respected
- Model contains no business logic
- IPFS layer is not bypassed
- API contract is preserved

---

# SECURITY REVIEW RULE

Reject if:

- seed phrases exposed
- encryption bypassed
- authorization missing
- CID access uncontrolled
- sensitive data leaked

---

# IPFS REVIEW RULE

Validate:

- CID is properly generated
- content is stored only in IPFS
- DB contains only CID reference
- no direct DB content storage

---

# DATABASE REVIEW RULE

Check:

- migrations used correctly
- no schema breaking changes
- relationships preserved
- indexes maintained
- no data loss risk

---

# API REVIEW RULE

Ensure:

- endpoints unchanged unless versioned
- response format consistent
- no breaking changes
- proper validation exists

---

# MESSENGER REVIEW RULE

Check:

- CID-based message flow intact
- reply structure preserved
- real-time flow not broken
- no duplication of message content

---

# WALLET REVIEW RULE

Ensure:

- EncryptionService used
- no plaintext secrets
- no private key exposure
- signing flow intact
- authorization enforced

---

# PERFORMANCE REVIEW RULE

Reject if:

- introduces N+1 queries
- removes caching without replacement
- adds blocking operations in request cycle
- loads unbounded datasets

---

# REFACTOR SAFETY RULE

Reject if:

- large uncontrolled rewrite
- unnecessary architecture changes
- removal of working functionality
- missing migration path

---

# DEPENDENCY REVIEW RULE

Check:

- new dependencies are necessary
- no insecure packages introduced
- no duplication of existing Laravel features

---

# CODE QUALITY RULE

Ensure:

- readable structure
- consistent naming
- no duplication
- proper separation of concerns
- testability maintained

---

# ERROR HANDLING RULE

Ensure:

- no silent failures
- no exposed stack traces
- proper exception handling
- safe user-facing messages

---

# LOGGING REVIEW RULE

Reject if logs contain:

- passwords
- tokens
- seed phrases
- private keys
- sensitive CID content

---

# AI REVIEW RULE

AI MUST:

1. Simulate execution mentally
2. Detect hidden side effects
3. Validate architecture impact
4. Ensure no rule violation
5. Prefer safest implementation

---

# CHANGE ACCEPTANCE RULE

A change is accepted ONLY if:

✓ No security violation  
✓ No IPFS violation  
✓ No database corruption risk  
✓ No API breaking  
✓ No wallet compromise  
✓ No performance degradation  

---

# ANTI-PATTERNS

Never accept:

- unsafe shortcuts
- partial implementations
- "temporary hacks"
- bypassing service layer
- direct DB/IPFS coupling

---

# FINAL RULE

Code review is a mandatory gate.

If review fails → system rejects change automatically.

---

END OF DOCUMENT