import { describe, expect, it } from 'vitest';
import { SailupClient } from '../src/client';
import { SmsResource } from '../src/sms';

describe('SailupClient', () => {
  it('exposes an SmsResource', () => {
    const client = new SailupClient('sailup_test_key');

    expect(client.sms).toBeInstanceOf(SmsResource);
  });
});
