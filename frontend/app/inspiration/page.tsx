import type { Metadata } from "next";
import InspirationHero from "@/components/inspiration/InspirationHero";
import LooksInPlaceSection from "@/components/inspiration/LooksInPlaceSection";
import WatchAndShopSection from "@/components/inspiration/WatchAndShopSection";
import JournalSection from "@/components/inspiration/JournalSection";
import { getShowcaseCompositions } from "@/lib/api/compositions";
import "@/styles/inspiration.css";

export const metadata: Metadata = {
  title: "Inspiration | Ideas, Stories & Spaces | Abby Lighting",
  description: "Ideas, stories and inspiration from Abby Lighting. See light in place, watch latest reels, and read design guides.",
};

export default async function InspirationPage() {
  const { data: compositions } = await getShowcaseCompositions();

  return (
    <div className="inspiration-page">
      <InspirationHero />
      <LooksInPlaceSection compositions={compositions} />
      <WatchAndShopSection />
      <JournalSection />
    </div>
  );
}
