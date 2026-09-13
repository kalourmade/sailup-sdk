from dataclasses import dataclass
from enum import Enum
from typing import List, Optional


class DeliveryStatus(str, Enum):
    PENDING = "pending"
    DELIVERED = "delivered"
    FAILED = "failed"
    EXPIRED = "expired"
    REJECTED = "rejected"


@dataclass(frozen=True)
class Message:
    id: str
    to: List[str]
    sender: Optional[str]
    body: str
    quantity: int
    delivery_status: DeliveryStatus
    created_at: str

    @staticmethod
    def from_dict(data: dict) -> "Message":
        to = data["to"] if isinstance(data["to"], list) else [data["to"]]
        return Message(
            id=data["id"],
            to=to,
            sender=data.get("sender"),
            body=data["body"],
            quantity=data["quantity"],
            delivery_status=DeliveryStatus(data["delivery_status"]),
            created_at=data["created_at"],
        )


@dataclass(frozen=True)
class Page:
    count: int
    next: Optional[str]
    previous: Optional[str]
    results: List[Message]

    @staticmethod
    def from_dict(data: dict) -> "Page":
        return Page(
            count=data["count"],
            next=data.get("next"),
            previous=data.get("previous"),
            results=[Message.from_dict(m) for m in data["results"]],
        )
