import { FetchTransport } from './http.js';
import { SmsResource } from './sms.js';

export class SailupClient {
  readonly sms: SmsResource;

  constructor(
    apiKey: string,
    baseUrl: string = 'https://api.sailup.io/v1',
    timeoutMs: number = 30000
  ) {
    const transport = new FetchTransport(apiKey, baseUrl, timeoutMs);
    this.sms = new SmsResource(transport);
  }
}
