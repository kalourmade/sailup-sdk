export class SailupError extends Error {
  constructor(message: string) {
    super(message);
    this.name = new.target.name;
    Object.setPrototypeOf(this, new.target.prototype);
  }
}

export class AuthenticationError extends SailupError {}
export class ValidationError extends SailupError {}
export class RateLimitError extends SailupError {}
export class NetworkError extends SailupError {}
export class InvalidSignatureError extends SailupError {}

export class ApiError extends SailupError {
  constructor(
    public readonly statusCode: number,
    public readonly rawBody: string,
    message: string
  ) {
    super(message);
  }
}
