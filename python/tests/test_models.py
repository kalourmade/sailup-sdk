from kalourmade_sailup.models import DeliveryStatus, Message, Page


def message_dict(**overrides):
    base = {
        "id": "msg_123",
        "to": ["233241234567"],
        "sender": "MyBrand",
        "body": "Hello",
        "quantity": 1,
        "delivery_status": "delivered",
        "created_at": "2026-09-12T10:00:00Z",
    }
    base.update(overrides)
    return base


def test_message_from_dict_maps_all_fields():
    message = Message.from_dict(message_dict())

    assert message.id == "msg_123"
    assert message.to == ["233241234567"]
    assert message.delivery_status == DeliveryStatus.DELIVERED


def test_message_from_dict_wraps_scalar_to_in_list():
    message = Message.from_dict(message_dict(to="233241234567", sender=None))

    assert message.to == ["233241234567"]
    assert message.sender is None


def test_page_from_dict_maps_envelope_and_results():
    page = Page.from_dict({
        "count": 1,
        "next": None,
        "previous": None,
        "results": [message_dict()],
    })

    assert page.count == 1
    assert page.next is None
    assert len(page.results) == 1
    assert isinstance(page.results[0], Message)
