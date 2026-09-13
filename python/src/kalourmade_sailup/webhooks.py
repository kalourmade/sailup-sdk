import hashlib
import hmac
import json
from typing import Mapping

from .exceptions import InvalidSignatureError


class Webhooks:
    @staticmethod
    def verify(raw_body: str, headers: Mapping[str, str], secret: str) -> dict:
        headers_lower = {k.lower(): v for k, v in headers.items()}
        signature = headers_lower.get("x-sailup-signature")
        timestamp = headers_lower.get("x-sailup-timestamp")

        if not signature or not timestamp:
            raise InvalidSignatureError("Missing signature headers")

        expected = hmac.new(
            secret.encode(), f"{timestamp}.{raw_body}".encode(), hashlib.sha256
        ).hexdigest()

        if not hmac.compare_digest(expected, signature):
            raise InvalidSignatureError("Signature mismatch")

        return json.loads(raw_body)
