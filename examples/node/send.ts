import { SailupClient } from '@kalourmade/sailup-sms';

const client = new SailupClient(process.env.SAILUP_API_KEY!);

const message = await client.sms.send({
  from: 'MyBrand',
  to: ['233241234567'],
  body: 'Hello from the Sailup Node SDK',
});

console.log(`Sent ${message.id} — delivery_status=${message.deliveryStatus}`);
