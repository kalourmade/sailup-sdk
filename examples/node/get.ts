import { SailupClient } from '@kalourmade/sailup-sms';

const messageId = process.argv[2];
if (!messageId) {
  console.error('Usage: tsx get.ts <message_id>');
  process.exit(1);
}

const client = new SailupClient(process.env.SAILUP_API_KEY!);
const message = await client.sms.get(messageId);

console.log(`${message.id} — ${message.body} (${message.deliveryStatus})`);
