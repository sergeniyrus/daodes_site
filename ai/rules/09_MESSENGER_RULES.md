# DAODES Messenger Rules
## File: ai/rules/09_MESSENGER_RULES.md

Version: 1.0

Status: Permanent

Priority: Critical (Core Business Module)

---

# PURPOSE

This document defines rules for the DAODES messaging system.

Messaging is the core feature of DAODES.

All message logic MUST comply with IPFS-first architecture.

---

# CORE PRINCIPLE

Messages are NOT stored in database.

Database stores ONLY CID references.

All message content lives in IPFS.

---

# MESSAGE FLOW ARCHITECTURE

User sends message:

1. User input received
2. Message payload created
3. Payload sent to IPFS
4. IPFS returns CID
5. CID stored in database
6. Message retrieved via CID when needed

---

# MESSAGE STRUCTURE IN IPFS

Each message MUST follow structure:

```json
{
  "type": "message",
  "version": 1,
  "message_id": "uuid",
  "sender_id": "user_id",
  "receiver_id": "user_id",
  "content": "text or structured payload",
  "reply_to": null,
  "attachments": [],
  "timestamp": "ISO8601",
  "metadata": {
    "encrypted": false,
    "edited": false
  }
}
```

---

# DATABASE MESSAGE STRUCTURE

Database stores ONLY:

- id
- sender_id
- receiver_id
- cid
- reply_to
- status
- created_at
- updated_at

NO MESSAGE TEXT ALLOWED.

---

# REPLY SYSTEM RULE

Replies MUST reference CID of original message.

Never duplicate message content in replies.

---

# EDIT MESSAGE RULE

Messages are immutable in IPFS.

Editing a message means:

1. Create new IPFS object
2. Generate new CID
3. Update database reference
4. Mark previous CID as "edited"

---

# DELETE MESSAGE RULE

Messages are NOT deleted from IPFS.

Deletion means:

- remove CID reference from DB
- mark message as deleted
- optionally unpin CID

---

# REAL-TIME RULE

Messages MUST support real-time delivery:

Preferred methods:

- WebSockets
- Laravel Echo
- Event broadcasting

No polling for message updates.

---

# MESSAGE STATUS RULE

Supported statuses:

- sent
- delivered
- read

Status is tracked in database only.

---

# ATTACHMENT RULE

Attachments:

- MUST be stored in IPFS
- each attachment has its own CID
- linked to parent message CID

---

# ENCRYPTION RULE

Sensitive messages MUST be encrypted before IPFS upload.

Encryption MUST use:

EncryptionService ONLY

Never store plaintext sensitive content.

---

# CONVERSATION MODEL

Conversation is NOT stored as full text.

Conversation is:

- collection of message CIDs
- ordered by timestamp

---

# THREADING RULE

Message threads are built via:

- reply_to CID references
- chronological ordering
- conversation grouping in service layer

---

# PAGINATION RULE

Message loading MUST use pagination.

Never load full chat history at once.

---

# PERFORMANCE RULE

- cache recent messages
- batch CID resolution
- lazy load older messages
- avoid repeated IPFS fetches

---

# SEARCH RULE

Search is performed on metadata only:

- sender_id
- receiver_id
- timestamps

Never search raw message content in DB.

---

# SECURITY RULE

Messages MUST be protected:

- CID access requires authorization
- users can only access their own messages
- IPFS content must be validated
- encryption enforced when required

---

# NOTIFICATION RULE

Message events MUST trigger:

- real-time notification
- unread counter update
- optional push notification

---

# READ STATUS RULE

Read status is stored in database only.

IPFS content is immutable and does not track state.

---

# ANTI-PATTERNS

Never:

- store message text in DB
- bypass IPFS layer
- allow public CID access
- duplicate message content in DB
- poll for updates
- mutate IPFS data

---

# EXTENSIBILITY RULE

Messenger system must support:

- group chats (future)
- media messages
- encrypted conversations
- message reactions (future)

Without breaking core architecture.

---

# AI RULE FOR MESSAGING

When generating messaging code:

1. Ensure IPFS-first storage
2. Ensure CID-only DB storage
3. Ensure encryption where needed
4. Ensure real-time support
5. Ensure no content duplication
6. Ensure scalability

---

# FINAL RULE

Messenger is the core system of DAODES.

If messaging breaks → entire platform breaks.

Therefore all rules are strict and non-negotiable.

---

END OF DOCUMENT