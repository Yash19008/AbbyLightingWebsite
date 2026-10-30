import "./globals.css";
import type { Metadata } from "next";
import Script from "next/script";
import { fetchMenuItems } from '@/lib/api/menu';
import { fetchCustomScripts } from '@/lib/api/custom-scripts';
import HeaderClient from "../components/HeaderClient";
import Footer from "../components/Footer";
import SpotlightEffect from "../components/home/SpotlightEffect";
import CustomScripts from "../components/CustomScripts";
import { siteIconsMetadata, SITE_URL } from "@/lib/seo/meta-config";

export const metadata: Metadata = {
  metadataBase: new URL(SITE_URL),
  title: {
    default: "Abby Lighting | Architectural & Decorative Lighting Manufacturer",
    template: "%s | Abby Lighting",
  },
  description: "Architectural, decorative, outdoor and smart lighting, engineered and manufactured in India.",
  icons: siteIconsMetadata,
  manifest: "/favicon/manifest.json",
  other: {
    "msapplication-TileColor": "#ffffff",
    "msapplication-TileImage": "/favicon/ms-icon-144x144.png",
  },
};

export default async function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const [menuItems, customScripts] = await Promise.all([
    fetchMenuItems(),
    fetchCustomScripts(),
  ]);

  return (
    <html lang="en">
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <Script
          src="https://www.google.com/recaptcha/api.js?render=explicit"
          strategy="afterInteractive"
        />
      </head>
      <body>
        <main>
          <SpotlightEffect />
          <HeaderClient />
          {children}
          <Footer initialMenuItems={menuItems} />
        </main>

        {/* Dynamic Custom Header & Footer Scripts */}
        <CustomScripts
          headerHtml={customScripts.header_scripts}
          footerHtml={customScripts.footer_scripts}
          enabled={customScripts.enabled}
        />
      </body>
    </html>
  );
}
