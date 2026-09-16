import type { Client } from "@/types/client";

interface ClientsSectionProps {
  clients: Client[];
}

export default function ClientsSection({ clients }: ClientsSectionProps) {
  if (!clients || clients.length === 0) {
    return null;
  }

  return (
    <section className="section" id="clients">
      <div className="shell">
        <div className="section-head reveal">
          <h2>Our Clients</h2>
        </div>

        <div className="marquee reveal">
          <div className="mtrack">
              <>
                {clients.map((client, index) => (
                  <div key={`client-${client.id}`} className="client-logo" aria-hidden="false">
                    <img src={client.image_url || ''} alt={`Abby Lighting client ${index + 1}`} />
                  </div>
                ))}

                {clients.map((client) => (
                  <div key={`client-dup-${client.id}`} className="client-logo" aria-hidden="true">
                    <img src={client.image_url || ''} alt="" />
                  </div>
                ))}
              </>
          </div>
        </div>
      </div>
    </section>
  );
}
