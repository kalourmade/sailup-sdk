import { SailupClient } from '@kalourmade/sailup-sms';

const client = new SailupClient(process.env.SAILUP_API_KEY!);

const page = await client.sms.list({ page: 1, pageSize: 10 });

for (const message of page.results) {
  console.log(`${message.id} — ${message.body} (${message.deliveryStatus})`);
}
