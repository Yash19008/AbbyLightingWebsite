import type { Metadata } from "next";
import LightCalculatorContent from "@/components/calculator/LightCalculatorContent";
import { buildPageMetadata } from "@/lib/seo/meta-config";
import "@/styles/light-calculator.css";

export const metadata: Metadata = buildPageMetadata('lightCalculator');

export default function LightCalculatorPage() {
  return <LightCalculatorContent />;
}
