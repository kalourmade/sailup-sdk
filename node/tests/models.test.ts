import { describe, expect, it } from 'vitest';
import { messageFromJson, pageFromJson } from '../src/models';

function messageJson(overrides: Record<string, unknown> = {}) {
  return {
    id: 'msg_123',
    to: ['233241234567'],
    sender: 'MyBrand',
    body: 'Hello',
    quantity: 1,
    delivery_status: 'delivered',
    created_at: '2026-09-12T10:00:00Z',
    ...overrides,
  };
}

describe('messageFromJson', () => {
  it('maps all fields', () => {
    const message = messageFromJson(messageJson());

    expect(message.id).toBe('msg_123');
    expect(message.to).toEqual(['233241234567']);
    expect(message.deliveryStatus).toBe('delivered');
  });

  it('wraps a scalar `to` in an array', () => {
    const message = messageFromJson(messageJson({ to: '233241234567', sender: null }));

    expect(message.to).toEqual(['233241234567']);
    expect(message.sender).toBeNull();
  });
});

describe('pageFromJson', () => {
  it('maps envelope and results', () => {
    const page = pageFromJson({
      count: 1,
      next: null,
      previous: null,
      results: [messageJson()],
    });

    expect(page.count).toBe(1);
    expect(page.results).toHaveLength(1);
    expect(page.results[0].id).toBe('msg_123');
  });
});
