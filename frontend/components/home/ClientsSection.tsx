import type { Client } from "@/types/client";

interface ClientsSectionProps {
  clients: Client[];
}

export default function ClientsSection({ clients }: ClientsSectionProps) {
  return (
    <section className="section" id="clients">
      <div className="shell">
        <div className="section-head reveal">
          <h2>Our Clients</h2>
        </div>

        <div className="marquee reveal">
          <div className="mtrack">
            {clients.length > 0 ? (
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
            ) : (
              <>
                {/* Fallback client logos - only shown if server fetch fails */}
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466660_Abby%20Logo-01.png" alt="Abby Lighting client 1" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466674_Abby%20Logo-02.png" alt="Abby Lighting client 2" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466684_Abby%20Logo-03.png" alt="Abby Lighting client 3" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466692_Abby%20Logo-04.png" alt="Abby Lighting client 4" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466712_Abby%20Logo-05.png" alt="Abby Lighting client 5" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466720_Abby%20Logo-06.png" alt="Abby Lighting client 6" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466732_Abby%20Logo-07.png" alt="Abby Lighting client 7" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466742_Abby%20Logo-08.png" alt="Abby Lighting client 8" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466773_Abby%20Logo-09.png" alt="Abby Lighting client 9" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466785_Abby%20Logo-10.png" alt="Abby Lighting client 10" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466806_Abby%20Logo-11.png" alt="Abby Lighting client 11" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466816_Abby%20Logo-12.png" alt="Abby Lighting client 12" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466825_Abby%20Logo-13.png" alt="Abby Lighting client 13" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466833_Abby%20Logo-14.png" alt="Abby Lighting client 14" /></div>
                <div className="client-logo" aria-hidden="false"><img src="https://abbylighting.com/storage/uploads/clients/1719466842_Abby%20Logo-15.png" alt="Abby Lighting client 15" /></div>

                {/* Duplicates for marquee effect */}
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466660_Abby%20Logo-01.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466674_Abby%20Logo-02.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466684_Abby%20Logo-03.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466692_Abby%20Logo-04.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466712_Abby%20Logo-05.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466720_Abby%20Logo-06.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466732_Abby%20Logo-07.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466742_Abby%20Logo-08.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466773_Abby%20Logo-09.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466785_Abby%20Logo-10.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466806_Abby%20Logo-11.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466816_Abby%20Logo-12.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466825_Abby%20Logo-13.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466833_Abby%20Logo-14.png" alt="" /></div>
                <div className="client-logo" aria-hidden="true"><img src="https://abbylighting.com/storage/uploads/clients/1719466842_Abby%20Logo-15.png" alt="" /></div>
              </>
            )}
          </div>
        </div>
      </div>
    </section>
  );
}
