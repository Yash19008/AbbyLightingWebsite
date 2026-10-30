import type { Metadata } from "next";
import DownloadsPageContent from "@/components/downloads/DownloadsPageContent";
import { buildPageMetadata } from "@/lib/seo/meta-config";
import "@/styles/downloads.css";

export const metadata: Metadata = buildPageMetadata('catalogues');

export default function CataloguesPage() {
  return <DownloadsPageContent />;
}
