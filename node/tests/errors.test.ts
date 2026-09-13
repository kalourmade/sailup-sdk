import { describe, expect, it } from 'vitest';
import {
  ApiError,
  AuthenticationError,
  InvalidSignatureError,
  NetworkError,
  RateLimitError,
  SailupError,
  ValidationError,
} from '../src/errors';

describe('error hierarchy', () => {
  it('all error types extend SailupError', () => {
    expect(new AuthenticationError('x')).toBeInstanceOf(SailupError);
    expect(new ValidationError('x')).toBeInstanceOf(SailupError);
    expect(new RateLimitError('x')).toBeInstanceOf(SailupError);
    expect(new NetworkError('x')).toBeInstanceOf(SailupError);
    expect(new InvalidSignatureError('x')).toBeInstanceOf(SailupError);
    expect(new ApiError(500, 'body', 'x')).toBeInstanceOf(SailupError);
  });

  it('ApiError carries status and raw body', () => {
    const error = new ApiError(503, '{"detail":"down"}', 'Sailup API error (status 503)');

    expect(error.statusCode).toBe(503);
    expect(error.rawBody).toBe('{"detail":"down"}');
    expect(error.message).toBe('Sailup API error (status 503)');
  });
});
