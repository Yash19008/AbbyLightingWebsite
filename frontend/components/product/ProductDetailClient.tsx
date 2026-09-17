"use client";

import React, { useState, useCallback, useMemo } from "react";
import Link from "next/link";
import ProductGallery from "./ProductGallery";
import ProductInfo from "./ProductInfo";
import CollectionBand from "./CollectionBand";
import ProductSpecs from "./ProductSpecs";
import RelatedFamily from "./RelatedFamily";
import { DecProductDetail } from "@/types/decorative";

// Base URL for images
const getImageUrl = (path: string | null) => {
  if (!path) return "";
  if (path.startsWith("http")) return path;
  const baseUrl = process.env.NEXT_PUBLIC_BACKEND_URL || "http://localhost:8000";
  return `${baseUrl}/storage/${path}`;
};

interface ProductDetailClientProps {
  product: DecProductDetail;
}

export default function ProductDetailClient({ product }: ProductDetailClientProps) {
  const [activeColourIndex, setActiveColourIndex] = useState(0);

  const handleColourChange = useCallback((index: number) => {
    setActiveColourIndex(index);
  }, []);

  const activeVariant = product.variants[activeColourIndex] ?? product.variants[0];

  // Derive the main stage image based on fallback rules
  const stageImage = useMemo(() => {
    if (activeVariant?.main_image) return getImageUrl(activeVariant.main_image);
    
    // Fallback 1: First variant that has an image
    const firstVariantWithImage = product.variants.find(v => v.main_image);
    if (firstVariantWithImage) return getImageUrl(firstVariantWithImage.main_image);
    
    // Fallback 2: Product featured image
    return getImageUrl(product.featured_image);
  }, [activeVariant, product.variants, product.featured_image]);

  // Gallery images (thumbnails) — prepend stageImage so it shows as a thumbnail
  const galleryImages = useMemo(() => {
    const images = product.galleries.map(g => getImageUrl(g.image));
    if (stageImage) {
        const stageFilename = stageImage.split('/').pop()?.split('?')[0];
        const alreadyExists = images.some(img => img.split('/').pop()?.split('?')[0] === stageFilename);
        if (!alreadyExists && stageImage !== images[0]) {
            return [stageImage, ...images];
        }
    }
    return images;
  }, [product.galleries, stageImage]);


  return (
    <main className="product-page">
      <div className="product-shell">
        <nav className="site-breadcrumb site-breadcrumb--on-light product-breadcrumb product-reveal" aria-label="Breadcrumb">
          <Link href="/">Home</Link> / {product.category?.name || "Decorative"} / {product.collection?.name || "Product"} / {product.name}
        </nav>
        <section className="product-top">
          <ProductGallery
            galleryImages={galleryImages}
            stageImage={stageImage}
          />
          <ProductInfo
            product={product}
            activeVariant={activeVariant}
            activeColourIndex={activeColourIndex}
            onColourChange={handleColourChange}
          />
        </section>
      </div>

      {product.collection && (
        <CollectionBand collection={product.collection} />
      )}

      {activeVariant?.spec_rows && (
        <ProductSpecs 
            specRows={activeVariant.spec_rows} 
            allVariants={product.variants}
            installationGuide={product.installation_guide ? getImageUrl(product.installation_guide) : null}
            careInstructions={product.care_instructions ? getImageUrl(product.care_instructions) : null}
        />
      )}

      {product.related_products && product.related_products.length > 0 && (
        <RelatedFamily 
            products={product.related_products} 
        />
      )}
    </main>
  );
}
