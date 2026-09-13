import os

from kalourmade_sailup import SailupClient

client = SailupClient(os.environ["SAILUP_API_KEY"])

message = client.sms.send(
    from_="MyBrand",
    to=["233241234567"],
    body="Hello from the Sailup Python SDK",
)

print(f"Sent {message.id} — delivery_status={message.delivery_status.value}")
