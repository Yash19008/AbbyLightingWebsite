import React from "react";
import type { Metadata } from "next";
import ProductDetailClient from "@/components/product/ProductDetailClient";
import "@/styles/product-detail.css";
import { DecProductDetail } from "@/types/decorative";
import { notFound } from "next/navigation";
import { API_URL, storageUrl } from "@/lib/config";
import { buildPageMetadata } from "@/lib/seo/meta-config";

export const dynamic = 'force-dynamic';
export const revalidate = 0;

async function fetchProductBySlug(slug: string): Promise<DecProductDetail | null> {
  try {
    const res = await fetch(`${API_URL}/dec-products/${slug}`, {
      cache: 'no-store',
    });
    
    if (!res.ok) {
      if (res.status === 404) return null;
      throw new Error(`Failed to fetch product: ${res.statusText}`);
    }
    
    const json = await res.json();
    return json.success ? json.data : json;
  } catch (error) {
    console.error("Error fetching product:", error);
    return null;
  }
}

export async function generateStaticParams() {
  try {
    const res = await fetch(`${API_URL}/dec-products`, { cache: 'no-store' });
    if (!res.ok) return [];
    
    const json = await res.json();
    const products: DecProductDetail[] = json.data || [];
    return products.map((product) => ({
      slug: product.slug,
    }));
  } catch (error) {
    console.error("Error in generateStaticParams:", error);
    return [];
  }
}

export async function generateMetadata({ params }: { params: Promise<{ slug: string }> }): Promise<Metadata> {
  const { slug } = await params;
  const product = await fetchProductBySlug(slug);
  
  if (!product) {
    return buildPageMetadata('decorativeDetailDefault', {
      title: 'Product Not Found | Abby Lighting',
      noindex: true,
    });
  }

  const title = product.meta_title || `${product.name} Decorative Light | Abby Lighting`;
  const description =
    product.meta_description ||
    product.short_description ||
    product.description ||
    `Explore the ${product.name} decorative lighting fixture crafted by Abby Lighting.`;
  const ogImage = product.featured_image ? storageUrl(product.featured_image) : undefined;
  
  return buildPageMetadata('decorativeDetailDefault', {
    title,
    description,
    canonical: `/decorative-lights/${slug}`,
    ogImage,
  });
}

export default async function ProductDetailPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const product = await fetchProductBySlug(slug);
  
  if (!product) {
    notFound();
  }
  
  return (
    <>
      <ProductDetailClient product={product} />
    </>
  );
}
