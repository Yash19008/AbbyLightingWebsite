import type { Metadata } from "next";
import LightCalculatorContent from "@/components/calculator/LightCalculatorContent";
import "@/styles/light-calculator.css";

export const metadata: Metadata = {
  title: "Professional Ambient Lighting Calculator | Abby Lighting",
  description:
    "Estimate ambient lighting requirements, fixture count, spacing and specification guidance for your space with Abby Lighting's professional lighting calculator.",
};

export default function LightCalculatorPage() {
  return <LightCalculatorContent />;
}
