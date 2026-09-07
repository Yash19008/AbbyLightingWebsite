import Image from 'next/image';
import type { Client } from '@/types/client';

interface ClientCardProps {
  client: Client;
}

export default function ClientCard({ client }: ClientCardProps) {
  return (
    <div className="group relative overflow-hidden rounded-lg bg-white shadow-md transition-all hover:shadow-xl">
      <div className="aspect-square relative">
        {client.image_url ? (
          <Image
            src={client.image_url}
            alt={`Client ${client.id}`}
            fill
            className="object-contain p-4 transition-transform group-hover:scale-105"
            sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
          />
        ) : (
          <div className="flex h-full items-center justify-center bg-gray-100">
            <span className="text-gray-400">No Image</span>
          </div>
        )}
      </div>
    </div>
  );
}
