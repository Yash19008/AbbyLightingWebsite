import type { Metadata } from "next";
import BlogArticleHead from "@/components/blogs/BlogArticleHead";
import BlogTableOfContents from "@/components/blogs/BlogTableOfContents";
import BlogArticleContent from "@/components/blogs/BlogArticleContent";
import BlogRelatedSection from "@/components/blogs/BlogRelatedSection";
import { processArticleContent } from "@/lib/toc";
import "@/styles/blogs.css";

interface PageProps {
  params: Promise<{ slug: string }>;
}

async function fetchBlog(slug: string) {
  try {
    const API_URL = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000";
    const res = await fetch(`${API_URL}/api/blogs/${slug}`, {
      next: { revalidate: 60 },
    });
    if (!res.ok) return { blog: null, latestBlogs: [] };
    const json = await res.json();
    return {
      blog: json.success ? json.data : null,
      latestBlogs: json.success && Array.isArray(json.latest_blogs) ? json.latest_blogs : [],
    };
  } catch {
    return { blog: null, latestBlogs: [] };
  }
}

export async function generateMetadata({ params }: PageProps): Promise<Metadata> {
  const { slug } = await params;
  const { blog } = await fetchBlog(slug);

  if (blog) {
    return {
      title: `${blog.title} | Abby Lighting`,
      description: blog.dek || blog.meta_description || "Abby Lighting Blog Article",
    };
  }

  return {
    title: "Blog Article | Abby Lighting",
    description: "Insights, guides and stories from Abby Lighting.",
  };
}

export default async function BlogDetailPage({ params }: PageProps) {
  const { slug } = await params;
  const { blog, latestBlogs } = await fetchBlog(slug);

  const API_URL = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000";

  // Detail Page image: uses secondary_image, falling back to featured_image
  const detailImg = blog?.secondary_image
    ? `${API_URL}/uploads/blogs/${blog.secondary_image}`
    : (blog?.featured_image
      ? `${API_URL}/uploads/blogs/${blog.featured_image}`
      : undefined);

  const detailImgCaption = blog?.secondary_image_caption || blog?.featured_image_caption || undefined;

  const publishDateStr = blog?.published_at
    ? new Date(blog.published_at).toLocaleDateString("en-US", { month: "long", year: "numeric" })
    : undefined;

  // Auto-generate Table of Contents from <h2> tags inside article content
  const { processedHtml, tocItems } = processArticleContent(blog?.content);

  // Format the latest blogs (excluding current open article) for the Keep Reading section
  const relatedArticles = latestBlogs.map((b: { featured_image?: string, secondary_image?: string, featured_image_caption?: string, slug?: string, title?: string, category?: { name?: string }, published_at?: string }) => {
    const imgUrl = b.featured_image
      ? `${API_URL}/uploads/blogs/${b.featured_image}`
      : (b.secondary_image
        ? `${API_URL}/uploads/blogs/${b.secondary_image}`
        : "/images/reference/project-atlas.png");

    return {
      title: b.title,
      tag: b.category?.name || "Inspiration",
      image: imgUrl,
      link: `/blogs/${b.slug}`,
      alt: b.featured_image_caption || b.title,
    };
  });

  return (
    <div className="blog-page">
      <main className="article">
        <BlogArticleHead
          title={blog?.title || ""}
          dek={blog?.dek || undefined}
          author={blog?.author || undefined}
          date={publishDateStr}
        />
        {tocItems.length > 0 && <BlogTableOfContents items={tocItems} />}
        <BlogArticleContent
          content={processedHtml || undefined}
          featuredImage={detailImg}
          featuredImageCaption={detailImgCaption}
          pullQuote={blog?.pull_quote || undefined}
          quoteAuthor={blog?.quote_author || undefined}
        />
        <BlogRelatedSection articles={relatedArticles} />
      </main>
    </div>
  );
}
