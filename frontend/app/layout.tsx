import "./globals.css";
import type { Metadata } from "next";
import HeaderClient from "../components/HeaderClient";
import Footer from "../components/Footer";
import SpotlightEffect from "../components/home/SpotlightEffect";

export const metadata: Metadata = {
  title: "Abby Lighting | Architectural & Decorative Lighting",
  description: "Architectural, decorative, outdoor and smart lighting, engineered and manufactured in India.",
  icons: {
    icon: "/favicon.svg",
    shortcut: "/favicon.svg",
  },
};

export default async function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en">
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
      </head>
      <body>
        <main>
          <SpotlightEffect />
          <HeaderClient />
          {children}
          <Footer />
        </main>
      </body>
    </html>
  );
}
