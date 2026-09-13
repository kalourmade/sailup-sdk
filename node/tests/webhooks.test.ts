import { createHmac } from 'node:crypto';
import { describe, expect, it } from 'vitest';
import { verifyWebhook } from '../src/webhooks';
import { InvalidSignatureError } from '../src/errors';

const SECRET = 'whsec_test';

function sign(timestamp: string, rawBody: string): string {
  return createHmac('sha256', SECRET).update(`${timestamp}.${rawBody}`).digest('hex');
}

describe('verifyWebhook', () => {
  it('returns the decoded payload for a valid signature', () => {
    const rawBody = JSON.stringify({ event: 'message.delivered', data: { message_id: 'msg_123' } });
    const timestamp = '1700000000';
    const signature = sign(timestamp, rawBody);

    const event = verifyWebhook(
      rawBody,
      { 'x-sailup-signature': signature, 'x-sailup-timestamp': timestamp },
      SECRET
    ) as { event: string };

    expect(event.event).toBe('message.delivered');
  });

  it('throws on a tampered body', () => {
    const rawBody = JSON.stringify({ event: 'message.delivered' });
    const timestamp = '1700000000';
    const signature = sign(timestamp, rawBody);
    const tampered = JSON.stringify({ event: 'message.failed' });

    expect(() =>
      verifyWebhook(tampered, { 'x-sailup-signature': signature, 'x-sailup-timestamp': timestamp }, SECRET)
    ).toThrow(InvalidSignatureError);
  });

  it('throws on missing headers', () => {
    expect(() => verifyWebhook('{}', {}, SECRET)).toThrow(InvalidSignatureError);
  });
});
