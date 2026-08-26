# DAODES Frontend Rules
## File: ai/rules/07_FRONTEND_RULES.md

Version: 1.0

Status: Permanent

Priority: High (UI / UX Architecture Layer)

---

# PURPOSE

This document defines frontend architecture rules for DAODES.

Frontend is responsible ONLY for presentation and user interaction.

Frontend MUST NOT contain business logic.

---

# CORE PRINCIPLE

Frontend = UI Layer Only

No architecture decisions.
No data ownership.
No backend logic.

---

# ARCHITECTURE FLOW

Correct flow:

User Interaction
→ API Request
→ Controller
→ Service
→ Data Response
→ Frontend Render

Frontend MUST NEVER bypass backend logic.

---

# TECHNOLOGY STACK RULE

DAODES frontend may use:

- Blade (server-rendered UI)
- Vue.js (interactive UI layer)
- Livewire (reactive Laravel UI)
- TailwindCSS (styling)

Mixing is allowed ONLY with clear separation of concerns.

---

# STATE MANAGEMENT RULE

Frontend state MUST be:

- minimal
- derived from backend
- not authoritative

Backend is source of truth.

Frontend state is temporary.

---

# VUE RULES

If Vue is used:

- Vue handles UI only
- Vue MUST NOT contain business logic
- API calls go through dedicated service layer
- No direct IPFS or database access

Allowed:

- component state
- UI transitions
- event handling

Forbidden:

- authentication logic
- data storage logic
- architecture logic

---

# LIVEWIRE RULES

If Livewire is used:

- logic stays in Livewire class
- Blade only renders state
- no DB logic inside Blade
- no Service calls inside Blade

Livewire acts as bridge only.

---

# BLADE + VUE SEPARATION RULE

Do NOT mix responsibilities:

Blade:
→ layout
→ structure
→ SSR rendering

Vue:
→ dynamic UI
→ interactions
→ reactivity

Never duplicate logic in both layers.

---

# REAL-TIME RULE

For messaging system:

- updates MUST come via backend events
- frontend MUST subscribe to updates
- no polling logic unless necessary

Preferred:

- WebSockets
- Laravel Echo
- Pusher or equivalent

---

# CHAT UI RULE (DAODES CORE FEATURE)

Chat system MUST follow:

- messages loaded from API
- messages contain CID reference
- content rendered via sanitized backend response

Frontend MUST NOT resolve CID.

---

# IPFS FRONTEND RULE

Frontend MUST NEVER:

- call IPFS directly
- resolve CID manually
- access IPFS gateways without backend validation

All IPFS interactions go through backend service layer.

---

# COMPONENT RULE

Frontend must use reusable components:

Examples:

- MessageBubble
- ChatWindow
- UserAvatar
- WalletCard
- NotificationItem

Components MUST be:

- stateless when possible
- reusable
- presentation-only

---

# DESIGN SYSTEM RULE

DAODES UI theme:

- dark mode base
- gold accents (primary actions)
- turquoise accents (interactive elements)

Rules:

- consistent spacing
- consistent typography
- no inline styling unless necessary

---

# UX RULE

User experience must be:

- predictable
- fast
- minimal latency
- consistent across modules

No hidden behaviors.

No unexpected UI side effects.

---

# API INTEGRATION RULE

Frontend communicates ONLY via:

- REST API
- or WebSockets

Never:

- direct DB access
- direct IPFS calls
- server file access

---

# ERROR HANDLING RULE

Frontend errors must be:

- user-friendly
- non-technical
- non-revealing

Never expose:

- stack traces
- internal paths
- server architecture

---

# PERFORMANCE RULE

- lazy load heavy components
- paginate lists (messages, transactions)
- avoid large DOM rendering blocks
- cache static UI assets

---

# SECURITY RULE

Frontend must assume:

- all client data is untrusted
- backend validation is required
- no sensitive data stored in frontend state

Never store:

- tokens in unsafe storage
- seeds
- private keys

---

# NOTIFICATION RULE

Notifications must be:

- event-driven
- backend-triggered
- frontend-rendered only

No frontend polling for critical updates.

---

# ANTI-PATTERNS

Never:

- implement business logic in UI
- bypass API layer
- store sensitive data in state
- resolve IPFS in frontend
- duplicate backend logic

---

# EXTENSIBILITY RULE

Frontend must support:

- adding new modules without rewriting core UI
- plugin-like component structure
- modular architecture

---

# FINAL RULE

Frontend is a projection layer.

It reflects backend state.

It does not define system behavior.

---

END OF DOCUMENT