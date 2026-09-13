# Sailup Python SDK

`kalourmade-sailup` — Python SDK for the Sailup SMS API.

## Install

```bash
pip install kalourmade-sailup
```

Requires Python 3.9+. Single dependency: `httpx`.

## Quickstart

```python
import os
from kalourmade_sailup import SailupClient

client = SailupClient(os.environ["SAILUP_API_KEY"])

message = client.sms.send(
    from_="MyBrand",
    to=["233241234567"],
    body="Hello from Sailup",
)
```

Full runnable examples: [`examples/python/send.py`](../examples/python/send.py),
[`list.py`](../examples/python/list.py), [`get.py`](../examples/python/get.py).

## Error handling

```python
from kalourmade_sailup.exceptions import (
    AuthenticationError, ValidationError, RateLimitError, ApiError, NetworkError,
)

try:
    client.sms.send(from_="MyBrand", to=["233241234567"], body="Hi")
except AuthenticationError:
    ...  # invalid/missing API key
except ValidationError:
    ...  # bad request payload
except RateLimitError:
    ...  # back off and retry
except ApiError as e:
    ...  # other API error — e.status_code, e.raw_body
except NetworkError:
    ...  # couldn't reach Sailup
```

## Verifying webhooks

```python
from kalourmade_sailup import Webhooks
from kalourmade_sailup.exceptions import InvalidSignatureError

try:
    event = Webhooks.verify(raw_body, request.headers, os.environ["SAILUP_WEBHOOK_SECRET"])
except InvalidSignatureError:
    return Response(status=400)
```

Full example: [`examples/python/verify_webhook.py`](../examples/python/verify_webhook.py).
