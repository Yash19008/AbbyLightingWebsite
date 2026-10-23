import type { Client } from "@/types/client";

interface ClientsSectionProps {
  clients?: Client[];
}

export default function ClientsSection({ clients = [] }: ClientsSectionProps) {
  // If no dynamic client logos exist, hide the entire section (including heading)
  if (!clients || clients.length === 0) {
    return null;
  }

  // If the client list is small (< 8), repeat it symmetrically so that one half
  // easily spans ultra-wide screens (> 2560px) without any gap before the -50% boundary.
  let displayList = clients;
  if (clients.length < 8) {
    const repeatCount = Math.ceil(8 / clients.length);
    displayList = Array.from({ length: repeatCount }, () => clients).flat();
  }

  // Calculate duration proportional to number of logos (approx 3.5s per logo item)
  // to ensure a consistent, relaxed, legible scrolling velocity (~50 px/s)
  const baseSecondsPerLogo = 3.5;
  const marqueeDuration = Math.max(Math.round(displayList.length * baseSecondsPerLogo), 25);

  const mtrackStyle = {
    '--marquee-duration': `${marqueeDuration}s`,
    animationDuration: `${marqueeDuration}s`,
  } as React.CSSProperties;

  return (
    <section className="section" id="clients">
      <div className="shell">
        <div className="section-head reveal" suppressHydrationWarning>
          <h2>Our Clients</h2>
        </div>

        <div className="marquee reveal" suppressHydrationWarning>
          <div className="mtrack" style={mtrackStyle}>
            {/* First half */}
            {displayList.map((client, index) => (
              <div key={`client-${client.id}-${index}`} className="client-logo" aria-hidden="false">
                <img src={client.image_url || ''} alt={`Abby Lighting client ${index + 1}`} />
              </div>
            ))}

            {/* Second half duplicates for seamless infinite scroll */}
            {displayList.map((client, index) => (
              <div key={`client-dup-${client.id}-${index}`} className="client-logo" aria-hidden="true">
                <img src={client.image_url || ''} alt="" />
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
