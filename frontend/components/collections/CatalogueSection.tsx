import Image from 'next/image';
import Link from 'next/link';

export default function CatalogueSection() {
  return (
    <section className="s-catalogue">
      <Image
        src="/images/symphony/catalogue.png"
        alt="Symphony catalogue"
        fill
        style={{ objectFit: 'cover' }}
      />
      <div>
        <h2>
          See the whole <em>collection.</em>
        </h2>
        <Link href="/catalogues">Download catalogue</Link>
      </div>
    </section>
  );
}
