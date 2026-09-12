import type { Metadata } from "next";
import DownloadsPageContent from "@/components/downloads/DownloadsPageContent";
import "@/styles/downloads.css";

export const metadata: Metadata = {
  title: "Product Catalogues | Abby Lighting",
  description:
    "Explore and download Abby Lighting's complete architectural, decorative and outdoor product catalogues, specifications and technical guides.",
};

export default function CataloguesPage() {
  return <DownloadsPageContent />;
}
