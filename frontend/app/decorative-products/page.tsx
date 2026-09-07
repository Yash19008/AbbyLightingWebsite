import type { Metadata } from "next";
import DecorativePageClient from "./DecorativePageClient";

export const metadata: Metadata = {
  title: "Decorative Lighting | Abby Lighting",
  description:
    "Sculptural pendants, wall lights, floor and table lamps across the Symphony, Quarry and Neoma collections — cast concrete discs, colour-blocked forms and lunar orbs, all made to order.",
};

export default function DecorativeProductsPage() {
  return <DecorativePageClient />;
}

