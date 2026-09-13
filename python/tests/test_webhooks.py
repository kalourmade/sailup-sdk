import hashlib
import hmac
import json

import pytest

from kalourmade_sailup.exceptions import InvalidSignatureError
from kalourmade_sailup.webhooks import Webhooks

SECRET = "whsec_test"


def sign(timestamp: str, raw_body: str) -> str:
    return hmac.new(SECRET.encode(), f"{timestamp}.{raw_body}".encode(), hashlib.sha256).hexdigest()


def test_verify_returns_decoded_payload_for_valid_signature():
    raw_body = json.dumps({"event": "message.delivered", "data": {"message_id": "msg_123"}})
    timestamp = "1700000000"
    signature = sign(timestamp, raw_body)

    event = Webhooks.verify(
        raw_body,
        {"X-Sailup-Signature": signature, "X-Sailup-Timestamp": timestamp},
        SECRET,
    )

    assert event["event"] == "message.delivered"


def test_verify_raises_on_tampered_body():
    raw_body = json.dumps({"event": "message.delivered"})
    timestamp = "1700000000"
    signature = sign(timestamp, raw_body)
    tampered = json.dumps({"event": "message.failed"})

    with pytest.raises(InvalidSignatureError):
        Webhooks.verify(
            tampered,
            {"X-Sailup-Signature": signature, "X-Sailup-Timestamp": timestamp},
            SECRET,
        )


def test_verify_raises_on_missing_headers():
    with pytest.raises(InvalidSignatureError):
        Webhooks.verify("{}", {}, SECRET)
