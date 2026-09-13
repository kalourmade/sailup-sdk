from kalourmade_sailup.client import SailupClient
from kalourmade_sailup.sms import SmsResource


def test_client_exposes_sms_resource():
    client = SailupClient("sailup_test_key")

    assert isinstance(client.sms, SmsResource)
