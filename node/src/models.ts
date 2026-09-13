export type DeliveryStatus = 'pending' | 'delivered' | 'failed' | 'expired' | 'rejected';

export interface Message {
  id: string;
  to: string[];
  sender: string | null;
  body: string;
  quantity: number;
  deliveryStatus: DeliveryStatus;
  createdAt: string;
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function messageFromJson(data: any): Message {
  return {
    id: data.id,
    to: Array.isArray(data.to) ? data.to : [data.to],
    sender: data.sender ?? null,
    body: data.body,
    quantity: data.quantity,
    deliveryStatus: data.delivery_status,
    createdAt: data.created_at,
  };
}

export interface Page<T> {
  count: number;
  next: string | null;
  previous: string | null;
  results: T[];
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function pageFromJson(data: any): Page<Message> {
  return {
    count: data.count,
    next: data.next ?? null,
    previous: data.previous ?? null,
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    results: (data.results as any[]).map(messageFromJson),
  };
}
