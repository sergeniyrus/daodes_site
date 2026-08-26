# DAODES IPFS Rules
## File: ai/rules/04_IPFS_RULES.md

Version: 1.0

Status: Permanent

Priority: Critical (Core Storage Layer)

---

# PURPOSE

This document defines how IPFS is used in the DAODES system.

IPFS is the PRIMARY storage layer for all user-generated content.

Database is only an index layer for CID references.

---

# CORE PRINCIPLE

IPFS = SOURCE OF TRUTH FOR CONTENT

Database = INDEX ONLY

---

# CRITICAL RULE

ALL MESSAGE CONTENT MUST BE STORED IN IPFS.

NEVER store full message content in database.

Database MUST ONLY store CID references.

---

# IPFS DATA MODEL

Every stored object in IPFS MUST follow this structure:

```json
{
  "type": "message",
  "version": 1,
  "created_at": "timestamp",
  "sender_id": "user_id",
  "receiver_id": "user_id",
  "content": "message text or payload",
  "attachments": [],
  "metadata": {}
}
```

After storage:

→ IPFS returns CID
→ CID is stored in database

---

# MESSAGE LIFECYCLE

Step 1: User sends message

Step 2: Backend prepares message payload

Step 3: Payload is sent to IPFS

Step 4: IPFS returns CID

Step 5: CID is stored in database

Step 6: Message retrieved via CID when needed

---

# UPDATE RULE

IPFS is immutable.

Messages cannot be edited in place.

If a message is "edited":

→ new IPFS object is created
→ new CID is generated
→ database reference is updated (or versioned)

---

# DELETE RULE

IPFS content is NOT deleted.

Deletion means:

- removing CID reference from database
- marking record as deleted
- optionally unpinning from node

Original content remains in IPFS network.

---

# VERSIONING RULE

Every modification creates a new CID.

Messages MUST support:

- version history
- chain of edits (optional)
- original CID reference

---

# ATTACHMENTS RULE

Attachments MUST NOT be stored in database.

Allowed storage:

- IPFS only

Each attachment:

- generates its own CID
- linked to parent message CID

---

# ENCRYPTION RULE

Sensitive content MUST be encrypted BEFORE IPFS upload.

Encryption MUST use:

EncryptionService

Never store plaintext:

- seed phrases
- private keys
- sensitive metadata

---

# DATA FLOW ARCHITECTURE

User Input
→ Backend Service
→ Encryption (optional)
→ IPFS Upload
→ CID Returned
→ Database Storage (CID only)
→ Retrieval via CID

---

# RETRIEVAL RULE

To display message:

1. Fetch CID from database
2. Request content from IPFS
3. Decrypt if required
4. Render to user

Database alone MUST NOT be used for content rendering.

---

# PERFORMANCE RULES

- Cache frequently accessed CID responses
- Batch IPFS requests when possible
- Avoid repeated IPFS fetches
- Use queue for heavy uploads

---

# RELIABILITY RULES

- Always retry IPFS upload on failure
- Validate CID before saving
- Ensure content integrity via hash validation

---

# SECURITY RULES

- Never expose raw IPFS hashes in frontend without control
- Always validate ownership before CID access
- Encrypt sensitive payloads before upload
- Do not trust client-provided CID without validation

---

# ANTI-PATTERNS

Never:

- store message text in DB
- bypass IPFS layer
- mutate IPFS content directly
- assume IPFS is mutable
- use IPFS as optional layer

---

# ARCHITECTURE GUARANTEE

If IPFS is unavailable:

- system must queue request
- must not fallback to database storage
- must retry upload later

---

# AI RULE FOR IPFS

Before generating any solution:

1. Ensure content goes to IPFS
2. Ensure DB only stores CID
3. Ensure encryption is applied if needed
4. Ensure immutability is respected
5. Ensure no direct DB content storage

---

# FINAL RULE

IPFS is not optional.

IPFS is mandatory for all content storage.

Database is only an index.

---

END OF DOCUMENT