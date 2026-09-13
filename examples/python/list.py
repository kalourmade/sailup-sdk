import os

from kalourmade_sailup import SailupClient

client = SailupClient(os.environ["SAILUP_API_KEY"])

page = client.sms.list(page=1, page_size=10)

for message in page.results:
    print(f"{message.id} — {message.body} ({message.delivery_status.value})")
