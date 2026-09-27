import type { Metadata } from "next";
import InspirationHero from "@/components/inspiration/InspirationHero";
import LooksInPlaceSection from "@/components/inspiration/LooksInPlaceSection";
import WatchAndShopSection from "@/components/inspiration/WatchAndShopSection";
import JournalSection from "@/components/inspiration/JournalSection";
import { getShowcaseCompositions } from "@/lib/api/compositions";
import { getInspirationHero } from "@/lib/api/inspiration";
import "@/styles/inspiration.css";

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export const metadata: Metadata = {
  title: "Inspiration | Ideas, Stories & Spaces | Abby Lighting",
  description: "Ideas, stories and inspiration from Abby Lighting. See light in place, watch latest reels, and read design guides.",
};

export default async function InspirationPage() {
  const [compositionsRes, heroRes] = await Promise.all([
    getShowcaseCompositions(),
    getInspirationHero(),
  ]);

  const compositions = compositionsRes.data || [];
  const heroSection = heroRes.data || null;

  return (
    <div className="inspiration-page">
      <InspirationHero heroSection={heroSection} />
      <LooksInPlaceSection compositions={compositions} />
      <WatchAndShopSection />
      <JournalSection />
    </div>
  );
}
