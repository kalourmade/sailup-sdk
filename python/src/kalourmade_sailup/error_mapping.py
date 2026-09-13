from .exceptions import ApiError, AuthenticationError, RateLimitError, ValidationError


def raise_for_status(status: int, raw_body: str) -> None:
    if status < 400:
        return

    message = f"Sailup API error (status {status})"

    if status == 401:
        raise AuthenticationError(message)
    if status == 429:
        raise RateLimitError(message)
    if status in (400, 422):
        raise ValidationError(message)
    raise ApiError(status, raw_body, message)
