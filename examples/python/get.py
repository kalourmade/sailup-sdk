import os
import sys

from kalourmade_sailup import SailupClient

message_id = sys.argv[1] if len(sys.argv) > 1 else sys.exit("Usage: python get.py <message_id>")
client = SailupClient(os.environ["SAILUP_API_KEY"])

message = client.sms.get(message_id)

print(f"{message.id} — {message.body} ({message.delivery_status.value})")
