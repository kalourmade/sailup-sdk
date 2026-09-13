import { createHmac } from 'node:crypto';
import { verifyWebhook } from '@kalourmade/sailup-sms';
import { InvalidSignatureError } from '@kalourmade/sailup-sms';

const secret = process.env.SAILUP_WEBHOOK_SECRET!;

// Simulate a Sailup webhook delivery for demonstration purposes.
const rawBody = JSON.stringify({
  event: 'message.delivered',
  created_at: '2026-09-12T10:00:00Z',
  data: { message_id: 'msg_123', delivery_status: 'delivered' },
});
const timestamp = String(Math.floor(Date.now() / 1000));
const signature = createHmac('sha256', secret).update(`${timestamp}.${rawBody}`).digest('hex');

const event = verifyWebhook(
  rawBody,
  { 'x-sailup-signature': signature, 'x-sailup-timestamp': timestamp },
  secret
) as { event: string };
console.log(`Verified event: ${event.event}`);

// Demonstrate the failure case: a tampered body fails verification.
try {
  verifyWebhook(
    '{"event":"message.failed"}',
    { 'x-sailup-signature': signature, 'x-sailup-timestamp': timestamp },
    secret
  );
} catch (e) {
  if (e instanceof InvalidSignatureError) {
    console.log(`Correctly rejected tampered payload: ${e.message}`);
  }
}
