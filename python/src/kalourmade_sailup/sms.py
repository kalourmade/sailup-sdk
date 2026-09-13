import json

from .error_mapping import raise_for_status
from .http import Transport
from .models import Message, Page


class SmsResource:
    def __init__(self, transport: Transport):
        self._transport = transport

    def send(self, from_: str, to: list, body: str) -> Message:
        status, raw = self._transport.request(
            "POST", "/sms/", json_body={"from": from_, "to": to, "body": body}
        )
        raise_for_status(status, raw)
        return Message.from_dict(json.loads(raw))

    def list(self, page: int = 1, page_size: int = 30) -> Page:
        status, raw = self._transport.request(
            "GET", "/sms/", query={"page": page, "page_size": page_size}
        )
        raise_for_status(status, raw)
        return Page.from_dict(json.loads(raw))

    def get(self, message_id: str) -> Message:
        status, raw = self._transport.request("GET", f"/sms/{message_id}/")
        raise_for_status(status, raw)
        return Message.from_dict(json.loads(raw))
