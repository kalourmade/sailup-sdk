# Sailup Node SDK

`@kalourmade/sailup-sms` — Node/TypeScript SDK for the Sailup SMS API.

## Install

```bash
npm install @kalourmade/sailup-sms
# or
pnpm add @kalourmade/sailup-sms
```

Requires Node 18+. No runtime dependencies. Ships ESM + CJS + types.

## Quickstart

```typescript
import { SailupClient } from '@kalourmade/sailup-sms';

const client = new SailupClient(process.env.SAILUP_API_KEY!);

const message = await client.sms.send({
  from: 'MyBrand',
  to: ['233241234567'],
  body: 'Hello from Sailup',
});
```

Full runnable examples: [`examples/node/send.ts`](../examples/node/send.ts),
[`list.ts`](../examples/node/list.ts), [`get.ts`](../examples/node/get.ts)
(run with `npx tsx examples/node/send.ts` after `npm link` in `node/`).

## Error handling

```typescript
import {
  AuthenticationError, ValidationError, RateLimitError, ApiError, NetworkError,
} from '@kalourmade/sailup-sms';

try {
  await client.sms.send({ from: 'MyBrand', to: ['233241234567'], body: 'Hi' });
} catch (e) {
  if (e instanceof AuthenticationError) { /* invalid/missing API key */ }
  else if (e instanceof ValidationError) { /* bad request payload */ }
  else if (e instanceof RateLimitError) { /* back off and retry */ }
  else if (e instanceof ApiError) { /* e.statusCode, e.rawBody */ }
  else if (e instanceof NetworkError) { /* couldn't reach Sailup */ }
}
```

## Verifying webhooks

```typescript
import { verifyWebhook, InvalidSignatureError } from '@kalourmade/sailup-sms';

try {
  const event = verifyWebhook(rawBody, req.headers, process.env.SAILUP_WEBHOOK_SECRET!);
} catch (e) {
  if (e instanceof InvalidSignatureError) {
    res.status(400).end();
  }
}
```

Full example: [`examples/node/verifyWebhook.ts`](../examples/node/verifyWebhook.ts).
