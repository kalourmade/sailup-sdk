import { describe, expect, it } from 'vitest';
import { SmsResource } from '../src/sms';
import { AuthenticationError } from '../src/errors';
import { FakeTransport } from './fakes';

function messageJson() {
  return JSON.stringify({
    id: 'msg_123',
    to: ['233241234567'],
    sender: 'MyBrand',
    body: 'Hello',
    quantity: 1,
    delivery_status: 'pending',
    created_at: '2026-09-12T10:00:00Z',
  });
}

describe('SmsResource', () => {
  it('send posts to /sms/ and returns a Message', async () => {
    const transport = new FakeTransport([{ status: 202, body: messageJson() }]);
    const resource = new SmsResource(transport);

    const message = await resource.send({ from: 'MyBrand', to: ['233241234567'], body: 'Hello' });

    expect(message.id).toBe('msg_123');
    expect(transport.requests[0].method).toBe('POST');
    expect(transport.requests[0].path).toBe('/sms/');
    expect(transport.requests[0].jsonBody).toEqual({
      from: 'MyBrand',
      to: ['233241234567'],
      body: 'Hello',
    });
  });

  it('list gets /sms/ with pagination query', async () => {
    const envelope = JSON.stringify({
      count: 1,
      next: null,
      previous: null,
      results: [JSON.parse(messageJson())],
    });
    const transport = new FakeTransport([{ status: 200, body: envelope }]);
    const resource = new SmsResource(transport);

    const page = await resource.list({ page: 2, pageSize: 50 });

    expect(page.count).toBe(1);
    expect(page.results).toHaveLength(1);
    expect(transport.requests[0].query).toEqual({ page: 2, page_size: 50 });
  });

  it('get fetches a single message by id', async () => {
    const transport = new FakeTransport([{ status: 200, body: messageJson() }]);
    const resource = new SmsResource(transport);

    const message = await resource.get('msg_123');

    expect(message.id).toBe('msg_123');
    expect(transport.requests[0].path).toBe('/sms/msg_123/');
  });

  it('raises the mapped exception for error statuses', async () => {
    const transport = new FakeTransport([{ status: 401, body: '{}' }]);
    const resource = new SmsResource(transport);

    await expect(resource.get('msg_123')).rejects.toBeInstanceOf(AuthenticationError);
  });
});
