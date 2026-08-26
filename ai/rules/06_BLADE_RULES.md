# DAODES Blade Rules
## File: ai/rules/06_BLADE_RULES.md

Version: 1.0

Status: Permanent

Priority: High (Frontend Security & Presentation Layer)

---

# PURPOSE

This document defines rules for Blade templates in DAODES.

Blade is ONLY a presentation layer.

Blade MUST NOT contain business logic.

---

# CORE PRINCIPLE

Blade = VIEW ONLY

No logic.
No data processing.
No architecture decisions.

---

# FORBIDDEN IN BLADE

Never use Blade for:

- Database queries
- Business logic
- API calls
- IPFS requests
- Encryption logic
- Conditional architecture decisions
- Data transformations

---

# ALLOWED IN BLADE

Blade is allowed to:

- Display data
- Loop over prepared datasets
- Render UI components
- Include components
- Handle simple presentation conditions (UI only)

---

# DATA FLOW RULE

All data MUST be prepared before reaching Blade.

Correct flow:

Controller → Service → DTO/Array → Blade

Blade MUST NOT call Services directly.

---

# COMPONENT RULE

Prefer Blade components for reusable UI:

- Message components
- User avatar components
- Chat bubbles
- Wallet UI elements

Components MUST be clean and logic-free.

---

# SECURITY RULE (CRITICAL)

All output in Blade MUST be escaped by default.

Never use:

{!! !!} unless absolutely required and fully sanitized.

Prevent XSS at all costs.

---

# IPFS CONTENT RENDERING RULE

All content retrieved from IPFS:

- MUST be sanitized before rendering
- MUST be escaped unless explicitly safe HTML
- MUST be processed in Service layer before Blade

Blade MUST NOT decode IPFS content.

---

# MESSAGE RENDERING RULE

Messages are rendered ONLY from prepared data.

Blade receives:

- message text (already processed)
- sender info
- timestamps
- attachments metadata

Blade MUST NOT fetch CID or IPFS content.

---

# CONDITIONAL LOGIC RULE

Blade conditions MUST be minimal:

Allowed:

@if($message)
@if($user->id === $message->sender_id)

Forbidden:

- complex nested logic
- business decisions
- service calls

---

# LOOP RULE

Loops are allowed only for display:

@foreach($messages as $message)

Inside loops:

- no logic
- no service calls
- no transformations

---

# UI COMPONENT RULE

All complex UI MUST be extracted into components:

Examples:

- <x-message />
- <x-chat.bubble />
- <x-user.avatar />
- <x-wallet.balance />

---

# STYLING RULE

DAODES UI uses:

- dark theme
- gold accents
- turquoise accents

Blade MUST NOT contain inline styles unless necessary.

Prefer:

- TailwindCSS
- component classes
- reusable CSS classes

---

# JAVASCRIPT RULE

Blade should NOT contain business JavaScript.

Allowed:

- UI interactions only
- event triggers
- modal toggles

Forbidden:

- API logic
- state management logic
- data fetching logic

---

# LIVEWIRE RULE (IF USED)

If Livewire is used:

- logic stays in Livewire class
- Blade only renders state
- no backend logic in Blade

---

# VUE RULE (IF USED)

If Vue is used:

- Blade acts only as mount point
- Vue handles UI state
- Blade does not manage data

---

# IPFS SAFETY RULE

Never pass raw CID directly into unsafe rendering.

Always:

CID → Service → sanitized content → Blade

---

# PERFORMANCE RULE

- Avoid heavy rendering loops
- Use pagination for message lists
- Avoid unnecessary re-renders

---

# ACCESS CONTROL RULE

Blade MUST NOT decide access rights.

Access control must be handled in:

- Middleware
- Policies
- Controllers/Services

Blade only renders already filtered data.

---

# ERROR DISPLAY RULE

Never expose:

- stack traces
- internal system errors
- sensitive debug info

User-facing errors must be generic.

---

# COMPONENT DESIGN RULE

Components must be:

- reusable
- stateless when possible
- presentation-only
- isolated from services

---

# ANTI-PATTERNS

Never:

- call DB in Blade
- call IPFS in Blade
- call Services in Blade
- embed business logic
- compute architecture decisions
- expose raw sensitive data

---

# OUTPUT SAFETY RULE

All dynamic output must be:

- escaped
- sanitized
- validated before rendering

---

# FINAL RULE

Blade is the last layer.

It must never influence:

- business logic
- data structure
- architecture decisions

Blade only displays prepared safe data.

---

END OF DOCUMENT