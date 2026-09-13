import { ApiError, AuthenticationError, RateLimitError, ValidationError } from './errors.js';

export function throwIfError(status: number, rawBody: string): void {
  if (status < 400) {
    return;
  }

  const message = `Sailup API error (status ${status})`;

  if (status === 401) throw new AuthenticationError(message);
  if (status === 429) throw new RateLimitError(message);
  if (status === 400 || status === 422) throw new ValidationError(message);
  throw new ApiError(status, rawBody, message);
}
