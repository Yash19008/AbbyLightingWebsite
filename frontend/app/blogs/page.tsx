import type { Metadata } from "next";
import BlogArticleHead from "@/components/blogs/BlogArticleHead";
import BlogTableOfContents from "@/components/blogs/BlogTableOfContents";
import BlogArticleContent from "@/components/blogs/BlogArticleContent";
import BlogRelatedSection from "@/components/blogs/BlogRelatedSection";
import { buildPageMetadata } from "@/lib/seo/meta-config";
import "@/styles/blogs.css";

export const metadata: Metadata = buildPageMetadata('blogs');

export default function BlogsPage() {
  return (
    <div className="blog-page">
      <main className="article">
        <BlogArticleHead />
        <BlogTableOfContents />
        <BlogArticleContent />
        <BlogRelatedSection />
      </main>
    </div>
  );
}
