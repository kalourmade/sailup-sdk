from .http import HttpxTransport
from .sms import SmsResource


class SailupClient:
    def __init__(
        self,
        api_key: str,
        base_url: str = "https://api.sailup.io/v1",
        timeout: float = 30.0,
    ):
        transport = HttpxTransport(api_key, base_url, timeout)
        self.sms = SmsResource(transport)
