import { describe, expect, it } from 'vitest';
import { throwIfError } from '../src/errorMapping';
import { ApiError, AuthenticationError, RateLimitError, ValidationError } from '../src/errors';

describe('throwIfError', () => {
  it('does not throw for status < 400', () => {
    expect(() => throwIfError(200, '{}')).not.toThrow();
  });

  it('throws AuthenticationError for 401', () => {
    expect(() => throwIfError(401, '{}')).toThrow(AuthenticationError);
  });

  it('throws RateLimitError for 429', () => {
    expect(() => throwIfError(429, '{}')).toThrow(RateLimitError);
  });

  it('throws ValidationError for 400 and 422', () => {
    expect(() => throwIfError(422, '{}')).toThrow(ValidationError);
  });

  it('throws ApiError with status and body for other 4xx/5xx', () => {
    try {
      throwIfError(503, '{"detail":"down"}');
      expect.fail('Expected ApiError');
    } catch (e) {
      expect(e).toBeInstanceOf(ApiError);
      expect((e as ApiError).statusCode).toBe(503);
      expect((e as ApiError).rawBody).toBe('{"detail":"down"}');
    }
  });
});
