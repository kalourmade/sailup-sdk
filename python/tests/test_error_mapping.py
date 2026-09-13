import pytest

from kalourmade_sailup.error_mapping import raise_for_status
from kalourmade_sailup.exceptions import (
    ApiError,
    AuthenticationError,
    RateLimitError,
    ValidationError,
)


def test_ok_status_does_not_raise():
    raise_for_status(200, "{}")


def test_401_raises_authentication_error():
    with pytest.raises(AuthenticationError):
        raise_for_status(401, "{}")


def test_429_raises_rate_limit_error():
    with pytest.raises(RateLimitError):
        raise_for_status(429, "{}")


def test_400_and_422_raise_validation_error():
    with pytest.raises(ValidationError):
        raise_for_status(422, "{}")


def test_other_4xx_5xx_raises_api_error_with_status_and_body():
    with pytest.raises(ApiError) as exc_info:
        raise_for_status(503, '{"detail":"down"}')

    assert exc_info.value.status_code == 503
    assert exc_info.value.raw_body == '{"detail":"down"}'
