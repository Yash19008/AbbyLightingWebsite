import React from "react";
import ProductDetailClient from "@/components/product/ProductDetailClient";
import "@/styles/product-detail.css";
import { DecProductDetail } from "@/types/decorative";
import { notFound } from "next/navigation";

export const dynamic = 'force-dynamic';
export const revalidate = 0;

import { API_URL } from "@/lib/config";

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

export async function generateMetadata({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const product = await fetchProductBySlug(slug);
  
  if (!product) {
    return { title: 'Product Not Found' };
  }
  
  return {
    title: `${product.name} | Abby Lighting`,
    description: product.short_description || product.description || `Buy ${product.name} at Abby Lighting`,
  };
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
