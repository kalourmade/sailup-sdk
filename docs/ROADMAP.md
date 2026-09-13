# Roadmap

v1 (current) covers the SMS API only: send, list, get, and webhook
signature verification, across all three SDKs (PHP, Python, Node),
released together from a single git tag.

There's no separate "v2" milestone — the next two releases are
additive minor versions on top of v1's SMS surface, followed by a
distinct v3 package. Nothing below breaks the existing `client.sms.*`
API.

## v1.1 — Contacts API

Adds `client.contacts.*` across all three SDKs, backed by Sailup's
Contacts API:

- Create, read, update, delete a contact
- Bulk upsert contacts
- Same typed-exception error handling and test/example pattern as
  the SMS resource

## v1.2 — Lists API

Adds `client.lists.*`, backed by Sailup's Lists API:

- Create/manage lists (named groups of contacts)
- Add/remove contacts from a list
- Use a list as a send target for campaign-style messaging — this is
  the real fix for "send to multiple contacts" beyond a hand-typed
  array of numbers (which `client.sms.send()` already supports today)

Exact endpoint schemas for both Contacts and Lists weren't fully
public when v1 shipped — re-verify against `sailup.io/docs/contacts`
and `sailup.io/docs/lists` before starting either.

## v3 — MCP server

A separate package, `@kalourmade/sailup-mcp`, exposing `sms.send`,
`sms.list`, `sms.get` (plus contacts/lists once v1.1/v1.2 exist) as
Model Context Protocol tools — so AI agent hosts (Claude, Claude Code,
and other MCP-compatible hosts) can send/query SMS through Sailup
directly.

- Built in Node/TypeScript on top of `@kalourmade/sailup-sms` — a
  thin tool-schema wrapper around the existing client, not a
  reimplementation
- Lives in its own `mcp/` directory in this monorepo, versioned and
  released independently (its own tag prefix, since it doesn't need
  to move in lockstep with the SMS SDKs)
- Design questions deferred to their own brainstorming/spec pass when
  this is picked up: tool schemas, auth passthrough (how the caller's
  API key reaches the MCP server), stdio vs. HTTP transport

## Not planned

- Phone number validation/lookup — Sailup's API has no such endpoint
  (only SMS, Contacts, Lists, Webhooks), so there's nothing for an SDK
  to wrap. `delivery_status` on a sent message (`rejected` vs.
  `delivered`) is the closest signal available today.
