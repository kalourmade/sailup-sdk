from kalourmade_sailup.exceptions import (
    ApiError,
    AuthenticationError,
    InvalidSignatureError,
    NetworkError,
    RateLimitError,
    SailupError,
    ValidationError,
)


def test_all_exceptions_extend_sailup_error():
    assert issubclass(AuthenticationError, SailupError)
    assert issubclass(ValidationError, SailupError)
    assert issubclass(RateLimitError, SailupError)
    assert issubclass(NetworkError, SailupError)
    assert issubclass(InvalidSignatureError, SailupError)
    assert issubclass(ApiError, SailupError)


def test_api_error_carries_status_and_raw_body():
    error = ApiError(503, '{"detail":"down"}', "Sailup API error (status 503)")

    assert error.status_code == 503
    assert error.raw_body == '{"detail":"down"}'
    assert str(error) == "Sailup API error (status 503)"
