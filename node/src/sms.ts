import type { Transport } from './http.js';
import { throwIfError } from './errorMapping.js';
import { Message, Page, messageFromJson, pageFromJson } from './models.js';

export class SmsResource {
  constructor(private readonly transport: Transport) {}

  async send(params: { from: string; to: string[]; body: string }): Promise<Message> {
    const { status, body } = await this.transport.request('POST', '/sms/', undefined, params);
    throwIfError(status, body);
    return messageFromJson(JSON.parse(body));
  }

  async list(params: { page?: number; pageSize?: number } = {}): Promise<Page<Message>> {
    const { status, body } = await this.transport.request('GET', '/sms/', {
      page: params.page ?? 1,
      page_size: params.pageSize ?? 30,
    });
    throwIfError(status, body);
    return pageFromJson(JSON.parse(body));
  }

  async get(messageId: string): Promise<Message> {
    const { status, body } = await this.transport.request('GET', `/sms/${messageId}/`);
    throwIfError(status, body);
    return messageFromJson(JSON.parse(body));
  }
}
