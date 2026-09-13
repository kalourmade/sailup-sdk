import { createHmac, timingSafeEqual } from 'node:crypto';
import { InvalidSignatureError } from './errors.js';

export function verifyWebhook(
  rawBody: string,
  headers: Record<string, string | undefined>,
  secret: string
): unknown {
  const normalized = Object.fromEntries(
    Object.entries(headers).map(([key, value]) => [key.toLowerCase(), value])
  );
  const signature = normalized['x-sailup-signature'];
  const timestamp = normalized['x-sailup-timestamp'];

  if (!signature || !timestamp) {
    throw new InvalidSignatureError('Missing signature headers');
  }

  const expected = createHmac('sha256', secret).update(`${timestamp}.${rawBody}`).digest('hex');
  const expectedBuf = Buffer.from(expected, 'utf8');
  const actualBuf = Buffer.from(signature, 'utf8');

  if (expectedBuf.length !== actualBuf.length || !timingSafeEqual(expectedBuf, actualBuf)) {
    throw new InvalidSignatureError('Signature mismatch');
  }

  return JSON.parse(rawBody);
}
