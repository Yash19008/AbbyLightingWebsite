import type { Metadata } from "next";
import BlogArticleHead from "@/components/blogs/BlogArticleHead";
import BlogTableOfContents from "@/components/blogs/BlogTableOfContents";
import BlogArticleContent from "@/components/blogs/BlogArticleContent";
import BlogRelatedSection from "@/components/blogs/BlogRelatedSection";
import "@/styles/blogs.css";

export const metadata: Metadata = {
  title: "Made in India, designed for the world | Abby Lighting",
  description:
    "Inside Abby Lighting's fully integrated Indian manufacturing facility and the quiet advantage of building in-house.",
};

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
