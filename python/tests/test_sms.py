import json

import pytest

from kalourmade_sailup.exceptions import AuthenticationError
from kalourmade_sailup.sms import SmsResource
from tests.fakes import FakeTransport


def message_json():
    return json.dumps({
        "id": "msg_123",
        "to": ["233241234567"],
        "sender": "MyBrand",
        "body": "Hello",
        "quantity": 1,
        "delivery_status": "pending",
        "created_at": "2026-09-12T10:00:00Z",
    })


def test_send_posts_to_sms_and_returns_message():
    transport = FakeTransport([(202, message_json())])
    resource = SmsResource(transport)

    message = resource.send("MyBrand", ["233241234567"], "Hello")

    assert message.id == "msg_123"
    assert transport.requests[0]["method"] == "POST"
    assert transport.requests[0]["path"] == "/sms/"
    assert transport.requests[0]["json_body"] == {
        "from": "MyBrand",
        "to": ["233241234567"],
        "body": "Hello",
    }


def test_list_gets_sms_with_pagination_query():
    envelope = json.dumps({
        "count": 1,
        "next": None,
        "previous": None,
        "results": [json.loads(message_json())],
    })
    transport = FakeTransport([(200, envelope)])
    resource = SmsResource(transport)

    page = resource.list(page=2, page_size=50)

    assert page.count == 1
    assert len(page.results) == 1
    assert transport.requests[0]["query"] == {"page": 2, "page_size": 50}


def test_get_fetches_single_message_by_id():
    transport = FakeTransport([(200, message_json())])
    resource = SmsResource(transport)

    message = resource.get("msg_123")

    assert message.id == "msg_123"
    assert transport.requests[0]["path"] == "/sms/msg_123/"


def test_error_status_raises_mapped_exception():
    transport = FakeTransport([(401, "{}")])
    resource = SmsResource(transport)

    with pytest.raises(AuthenticationError):
        resource.get("msg_123")
