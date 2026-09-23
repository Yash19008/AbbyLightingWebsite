import { fetchHomePageData } from "@/lib/api/server-fetchers";
import HeroSection from "@/components/home/HeroSection";
import WorldsSection from "@/components/home/WorldsSection";
import ManufacturingSection from "@/components/home/ManufacturingSection";
import NewArrivalsSection from "@/components/home/NewArrivalsSection";
import ProjectsSection from "@/components/home/ProjectsSection";
import ClientsSection from "@/components/home/ClientsSection";
import CatalogueSection from "@/components/home/CatalogueSection";
import NewsSection from "@/components/home/NewsSection";

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export const metadata = {
  title: 'Abby Lighting | Architectural & Decorative Lighting',
  description: 'Precision architectural lighting and decorative fixtures for beautifully designed spaces. Designed, engineered, and manufactured in-house.',
};

export default async function Home() {
  // Fetch all home page data in parallel on the server
  const { sliders, projects, clients, newsItems, manufacturingSection, homeCatalogueSection, newArrivalCategories, lightWorlds } = await fetchHomePageData();

  return (
    <>
      <HeroSection sliders={sliders} />
      <WorldsSection lightWorlds={lightWorlds} />
      <ManufacturingSection data={manufacturingSection} />
      <NewArrivalsSection categories={newArrivalCategories} />
      <ProjectsSection projects={projects} />
      <ClientsSection clients={clients} />
      <CatalogueSection data={homeCatalogueSection} />
      <NewsSection newsItems={newsItems} />
    </>
  );
}
