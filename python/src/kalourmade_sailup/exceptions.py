class SailupError(Exception):
    """Base exception for all Sailup SDK errors."""


class AuthenticationError(SailupError):
    """Raised on 401 responses."""


class ValidationError(SailupError):
    """Raised on 400/422 responses."""


class RateLimitError(SailupError):
    """Raised on 429 responses."""


class ApiError(SailupError):
    """Raised on other 4xx/5xx responses."""

    def __init__(self, status_code: int, raw_body: str, message: str):
        super().__init__(message)
        self.status_code = status_code
        self.raw_body = raw_body


class NetworkError(SailupError):
    """Raised when the request could not reach Sailup."""


class InvalidSignatureError(SailupError):
    """Raised when webhook signature verification fails."""
