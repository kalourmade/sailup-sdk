import hashlib
import hmac
import json
import os
import time

from kalourmade_sailup import Webhooks
from kalourmade_sailup.exceptions import InvalidSignatureError

secret = os.environ["SAILUP_WEBHOOK_SECRET"]

# Simulate a Sailup webhook delivery for demonstration purposes.
raw_body = json.dumps({
    "event": "message.delivered",
    "created_at": "2026-09-12T10:00:00Z",
    "data": {"message_id": "msg_123", "delivery_status": "delivered"},
})
timestamp = str(int(time.time()))
signature = hmac.new(secret.encode(), f"{timestamp}.{raw_body}".encode(), hashlib.sha256).hexdigest()

event = Webhooks.verify(
    raw_body,
    {"X-Sailup-Signature": signature, "X-Sailup-Timestamp": timestamp},
    secret,
)
print(f"Verified event: {event['event']}")

# Demonstrate the failure case: a tampered body fails verification.
try:
    Webhooks.verify(
        '{"event":"message.failed"}',
        {"X-Sailup-Signature": signature, "X-Sailup-Timestamp": timestamp},
        secret,
    )
except InvalidSignatureError as e:
    print(f"Correctly rejected tampered payload: {e}")
