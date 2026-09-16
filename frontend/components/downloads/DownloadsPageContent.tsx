"use client";

import React, { useState, useRef, useEffect, useMemo } from "react";
import { getCatalogueCategories, getCatalogues, submitCatalogDownload, CatalogueDto, CatalogueCategoryDto } from "@/lib/api/catalogues";
import { COUNTRIES, Country } from "@/lib/countries";

interface CatalogueItem {
  id: number;
  title: string;
  category: string;
  categorySlug: string;
  image: string;
  pdfUrl?: string | null;
  downloadUrl?: string | null;
  fileSize?: string | null;
  isFeatured?: boolean;
}



const DEFAULT_CATEGORIES = [
  { id: 0, name: "All", slug: "all" },
];

const SORT_OPTIONS = [
  { id: "popular", label: "Most popular" },
  { id: "new", label: "New products" },
  { id: "az", label: "Alphabetical A-Z" },
  { id: "za", label: "Alphabetical Z-A" },
];

type SortMode = "popular" | "new" | "az" | "za";

export default function DownloadsPageContent() {
  const [categories, setCategories] = useState<{ id: number; name: string; slug: string }[]>(DEFAULT_CATEGORIES);
  const [catalogues, setCatalogues] = useState<CatalogueItem[]>([]);
  const [loading, setLoading] = useState(true);

  const [activeFilter, setActiveFilter] = useState<string>("All");
  const [sortMode, setSortMode] = useState<SortMode>("popular");
  const [sortMenuOpen, setSortMenuOpen] = useState(false);
  const [mobileFilterOpen, setMobileFilterOpen] = useState(false);
  const [visibleLimit, setVisibleLimit] = useState<number>(6);
  const [selectedCardId, setSelectedCardId] = useState<number | null>(null);

  // Modal State
  const [modalOpen, setModalOpen] = useState(false);
  const [selectedCatalogue, setSelectedCatalogue] = useState<CatalogueItem | null>(null);
  const [submitting, setSubmitting] = useState(false);
  const [submitted, setSubmitted] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);

  // Country Flag Selector State
  const [selectedCountry, setSelectedCountry] = useState<Country>(COUNTRIES[0]);
  const [countryDropdownOpen, setCountryDropdownOpen] = useState(false);
  const [countrySearch, setCountrySearch] = useState("");
  const countryRef = useRef<HTMLDivElement>(null);

  const filteredCountries = useMemo(() => {
    if (!countrySearch.trim()) return COUNTRIES;
    const q = countrySearch.toLowerCase().trim();
    return COUNTRIES.filter(
      (c) =>
        c.name.toLowerCase().includes(q) ||
        c.dialCode.includes(q) ||
        c.code.toLowerCase().includes(q)
    );
  }, [countrySearch]);

  // Form fields
  const [formData, setFormData] = useState({
    name: "",
    phone: "",
    email: "",
    city: "",
    company: "",
    role: "",
    message: "",
    captcha: false,
  });

  const sortRef = useRef<HTMLDivElement>(null);
  const mobileFilterRef = useRef<HTMLDivElement>(null);

  // Fetch dynamic categories and catalogues from API
  useEffect(() => {
    let isMounted = true;
    async function loadData() {
      try {
        setLoading(true);
        const [catRes, itemsRes] = await Promise.all([
          getCatalogueCategories(),
          getCatalogues(),
        ]);

        if (isMounted) {
          let loadedCatalogues: CatalogueItem[] = [];
          if (itemsRes.success && itemsRes.data && itemsRes.data.length > 0) {
            loadedCatalogues = itemsRes.data.map((item) => ({
              id: item.id,
              title: item.title,
              category: item.category?.name || "Uncategorized",
              categorySlug: item.category?.slug || "all",
              image: item.cover_image || "/images/figma-update/catalogue.png",
              pdfUrl: item.pdf_url,
              downloadUrl: item.download_url || (item.id ? `http://localhost:8000/api/catalogues/${item.id}/download-pdf` : null),
              fileSize: item.file_size,
              isFeatured: item.is_featured,
            }));
            setCatalogues(loadedCatalogues);
          }

          if (catRes.success && catRes.data && catRes.data.length > 0) {
            // Only keep categories that have at least 1 mapped catalogue
            const activeCats = catRes.data.filter(
              (c) =>
                (c.catalogues_count !== undefined && c.catalogues_count > 0) ||
                loadedCatalogues.some(
                  (item) =>
                    item.categorySlug.toLowerCase() === c.slug.toLowerCase() ||
                    item.category.toLowerCase() === c.name.toLowerCase()
                )
            );
            setCategories([
              { id: 0, name: "All", slug: "all" },
              ...activeCats.map((c) => ({ id: c.id, name: c.name, slug: c.slug })),
            ]);
          }
        }
      } catch (err) {
        console.error("Error loading catalogues:", err);
      } finally {
        if (isMounted) setLoading(false);
      }
    }

    loadData();
    return () => {
      isMounted = false;
    };
  }, []);

  // Close dropdowns on outside click
  useEffect(() => {
    function handleClickOutside(e: MouseEvent) {
      if (sortRef.current && !sortRef.current.contains(e.target as Node)) {
        setSortMenuOpen(false);
      }
      if (mobileFilterRef.current && !mobileFilterRef.current.contains(e.target as Node)) {
        setMobileFilterOpen(false);
      }
      if (countryRef.current && !countryRef.current.contains(e.target as Node)) {
        setCountryDropdownOpen(false);
      }
    }
    document.addEventListener("click", handleClickOutside);
    return () => document.removeEventListener("click", handleClickOutside);
  }, []);

  // Keyboard navigation for modal
  useEffect(() => {
    function handleKeyDown(e: KeyboardEvent) {
      if (e.key === "Escape") {
        if (modalOpen) {
          closeModal();
        } else {
          setSortMenuOpen(false);
          setMobileFilterOpen(false);
        }
      }
    }
    document.addEventListener("keydown", handleKeyDown);
    return () => document.removeEventListener("keydown", handleKeyDown);
  }, [modalOpen]);

  // Lock body scroll when modal is open
  useEffect(() => {
    if (modalOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [modalOpen]);

  const filteredAndSortedCatalogues = useMemo(() => {
    let list = [...catalogues];

    if (activeFilter !== "All" && activeFilter !== "all") {
      list = list.filter(
        (item) =>
          item.category.toLowerCase() === activeFilter.toLowerCase() ||
          item.categorySlug.toLowerCase() === activeFilter.toLowerCase()
      );
    }

    if (sortMode === "az") {
      list.sort((a, b) => a.title.localeCompare(b.title));
    } else if (sortMode === "za") {
      list.sort((a, b) => b.title.localeCompare(a.title));
    } else if (sortMode === "new") {
      list.sort((a, b) => b.id - a.id);
    } else {
      list.sort((a, b) => (b.isFeatured ? 1 : 0) - (a.isFeatured ? 1 : 0) || a.id - b.id);
    }

    return list;
  }, [catalogues, activeFilter, sortMode]);

  const displayedCatalogues = useMemo(() => {
    return filteredAndSortedCatalogues.slice(0, visibleLimit);
  }, [filteredAndSortedCatalogues, visibleLimit]);

  const hasMore = filteredAndSortedCatalogues.length > visibleLimit;

  const handleFilterSelect = (categoryName: string) => {
    setActiveFilter(categoryName);
    setVisibleLimit(6);
    setMobileFilterOpen(false);
  };

  const handleSortSelect = (mode: SortMode) => {
    setSortMode(mode);
    setSortMenuOpen(false);
  };

  const openDownloadModal = (item: CatalogueItem, e?: React.MouseEvent) => {
    if (e) e.stopPropagation();
    setSelectedCatalogue(item);
    setSubmitted(false);
    setSubmitting(false);
    setSubmitError(null);
    setFormData({
      name: "",
      phone: "",
      email: "",
      city: "",
      company: "",
      role: "",
      message: "",
      captcha: false,
    });
    setModalOpen(true);
  };

  const closeModal = () => {
    setModalOpen(false);
    setSelectedCardId(null);
    setSubmitError(null);
    setSubmitting(false);
  };

  const handleFormChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    const { name, value, type } = e.target;
    if (type === "checkbox") {
      const checked = (e.target as HTMLInputElement).checked;
      setFormData((prev) => ({ ...prev, [name]: checked }));
    } else {
      setFormData((prev) => ({ ...prev, [name]: value }));
    }
  };

  const triggerFileDownload = async (url: string, filename?: string) => {
    const finalFilename = filename || "catalogue.pdf";
    try {
      // 1. Fetch file as a Blob directly from API
      const response = await fetch(url);
      if (!response.ok) throw new Error("Failed to fetch PDF blob");
      const blob = await response.blob();
      const blobUrl = window.URL.createObjectURL(blob);

      // 2. Create invisible temporary link with blob URL and simulate click to save to disk
      const link = document.createElement("a");
      link.href = blobUrl;
      link.setAttribute("download", finalFilename);
      link.style.display = "none";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      setTimeout(() => {
        window.URL.revokeObjectURL(blobUrl);
      }, 5000);
    } catch (e) {
      console.warn("Direct blob download failed, falling back to invisible download iframe:", e);
      // Fallback: Invisible iframe navigation to download endpoint
      // Because the endpoint sets Content-Disposition: attachment, it downloads without navigating the page or opening a tab
      const iframe = document.createElement("iframe");
      iframe.style.display = "none";
      iframe.src = url;
      document.body.appendChild(iframe);
      setTimeout(() => {
        if (document.body.contains(iframe)) {
          document.body.removeChild(iframe);
        }
      }, 60000);
    }
  };


  const handleFormSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.captcha || submitting) return;

    setSubmitting(true);
    setSubmitError(null);

    const fullPhone = formData.phone
      ? `${selectedCountry.dialCode} ${formData.phone}`.trim()
      : "";

    try {
      const result = await submitCatalogDownload({
        name: formData.name,
        email: formData.email,
        phone: fullPhone,
        city: formData.city,
        company: formData.company,
        role: formData.role,
        message: formData.message,
        catalogue_name: selectedCatalogue?.title || "General Catalogue",
        catalogue_id: selectedCatalogue?.id,
      });

      setSubmitting(false);

      if (result.success) {
        setSubmitted(true);
        // Automatically start the direct PDF file download
        const downloadUrl =
          selectedCatalogue?.downloadUrl ||
          (selectedCatalogue?.id
            ? `http://localhost:8000/api/catalogues/${selectedCatalogue.id}/download-pdf`
            : "http://localhost:8000/api/catalogues/1/download-pdf");
        const cleanName = (selectedCatalogue?.title || "catalogue")
          .toLowerCase()
          .replace(/[^a-z0-9]+/g, "-");
        triggerFileDownload(downloadUrl, `${cleanName}-catalogue.pdf`);
      } else {
        setSubmitError(result.message || "Failed to submit. Please check your information and try again.");
      }
    } catch (err) {
      console.error("Download submit error:", err);
      setSubmitting(false);
      setSubmitError("Failed to connect to the server. Please try again.");
    }
  };



  return (
    <div className="downloads-page">
      {/* Hero Section */}
      <section className="downloads-hero" aria-labelledby="downloads-title">
        <div className="downloads-hero-shade" />
        <div className="downloads-hero-copy">
          <h1 id="downloads-title">
            <span className="hero-copy-desktop">Our Product</span>
            <span className="hero-copy-mobile">Our Product</span>
          </h1>
          <p>
            <span className="hero-copy-desktop">Catalogs</span>
            <span className="hero-copy-mobile">Catalogs</span>
          </p>
        </div>
      </section>

      {/* Catalogue Grid & Toolbar Section */}
      <section className="catalogue-section" aria-label="Product catalogues">
        {/* Toolbar */}
        <div className="catalogue-toolbar">
          <div className="catalogue-filters" role="tablist" aria-label="Catalogue categories">
            {categories.map((cat) => (
              <button
                key={cat.id}
                className={activeFilter.toLowerCase() === cat.name.toLowerCase() ? "active" : ""}
                type="button"
                role="tab"
                aria-selected={activeFilter.toLowerCase() === cat.name.toLowerCase()}
                onClick={() => handleFilterSelect(cat.name)}
              >
                {cat.name}
              </button>
            ))}
          </div>

          <div className="catalogue-actions">
            {/* Mobile Filter */}
            <div className="catalogue-mobile-filter" ref={mobileFilterRef}>
              <button
                className={`catalogue-filter-trigger ${mobileFilterOpen ? 'is-open' : ''}`}
                type="button"
                aria-haspopup="menu"
                aria-expanded={mobileFilterOpen}
                onClick={() => {
                  setMobileFilterOpen((prev) => !prev);
                  setSortMenuOpen(false);
                }}
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z"/>
                </svg>
                <span>FILTER BY</span>
              </button>
              {mobileFilterOpen && (
                <div className="catalogue-filter-menu" role="menu">
                  {categories.map((cat) => (
                    <button
                      key={cat.id}
                      className={activeFilter.toLowerCase() === cat.name.toLowerCase() ? "is-active" : ""}
                      type="button"
                      role="menuitemradio"
                      aria-checked={activeFilter.toLowerCase() === cat.name.toLowerCase()}
                      onClick={() => handleFilterSelect(cat.name)}
                    >
                      {cat.name === "Architecture" ? "Architectural" : cat.name}
                    </button>
                  ))}
                </div>
              )}
            </div>

            {/* Sort By Dropdown */}
            <div className="catalogue-sort-wrap" ref={sortRef}>
              <button
                className={`catalogue-sort ${sortMenuOpen ? 'is-open' : ''}`}
                type="button"
                aria-haspopup="menu"
                aria-expanded={sortMenuOpen}
                onClick={() => {
                  setSortMenuOpen((prev) => !prev);
                  setMobileFilterOpen(false);
                }}
              >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                </svg>
                <span>SORT BY</span>
              </button>
              {sortMenuOpen && (
                <div className="catalogue-sort-menu" role="menu">
                  {SORT_OPTIONS.map((opt) => (
                    <button
                      key={opt.id}
                      type="button"
                      role="menuitem"
                      className={sortMode === opt.id ? "is-active" : ""}
                      onClick={() => handleSortSelect(opt.id as SortMode)}
                    >
                      {opt.label}
                    </button>
                  ))}
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Empty State */}
        {displayedCatalogues.length === 0 && (
          <div className="empty-state">
            <p>No catalogues available at the moment.</p>
          </div>
        )}

        {/* Cards Grid */}
        <div className="catalogue-grid">
          {displayedCatalogues.map((item) => {
            const isSelected = selectedCardId === item.id;
            return (
              <article
                key={item.id}
                className={`catalogue-card ${isSelected ? "selected" : ""}`}
                data-category={item.category}
                data-title={item.title}
                tabIndex={0}
                aria-label={`Select ${item.title} catalogue`}
                onClick={() => setSelectedCardId((prev) => (prev === item.id ? null : item.id))}
                onKeyDown={(e) => {
                  if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    setSelectedCardId((prev) => (prev === item.id ? null : item.id));
                  }
                }}
              >
                <div className="catalogue-cover">
                  <img src={item.image} alt={`${item.title} catalogue cover`} />
                  <div className="catalogue-selected">
                    <button
                      type="button"
                      aria-label={`Download ${item.title} catalogue`}
                      onClick={(e) => openDownloadModal(item, e)}
                    >
                      <svg
                        className="catalogue-download-svg"
                        width="32"
                        height="32"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#ffffff"
                        strokeWidth="1"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        aria-hidden="true"
                      >
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="-4" />
                      </svg>
                      <strong>Download PDF</strong>
                    </button>
                    <small>{item.title}</small>
                  </div>
                </div>
                <h2>{item.title}</h2>
              </article>
            );
          })}
        </div>

        {/* View More Button */}
        {hasMore && (
          <button
            className="catalogue-more"
            type="button"
            onClick={() => setVisibleLimit((prev) => prev + 6)}
          >
            View More
          </button>
        )}
      </section>

      {/* Download Modal */}
      {modalOpen && (
        <div className="download-modal" id="download-modal" aria-hidden="false">
          <button
            className="download-backdrop"
            type="button"
            tabIndex={-1}
            aria-label="Close download form"
            onClick={closeModal}
          />
          <section
            className="download-dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="download-dialog-title"
          >
            <button
              className="download-close"
              type="button"
              aria-label="Close download form"
              onClick={closeModal}
            >
              ×
            </button>
            <h2 id="download-dialog-title">Download Catalogue</h2>
            <p>
              Fill in your details to access Abby Lighting's complete <strong>{selectedCatalogue?.title}</strong> catalogue, including product specifications, technical details, finishes, and application references.
            </p>

            <form className="download-form" onSubmit={handleFormSubmit}>
              <div className={`download-field full ${formData.name ? "filled" : ""}`}>
                <label htmlFor="download-name">Your name*</label>
                <input
                  id="download-name"
                  name="name"
                  value={formData.name}
                  onChange={handleFormChange}
                  autoComplete="name"
                  required
                />
              </div>

              <div className={`download-field phone-field-wrap ${formData.phone ? "filled" : ""}`}>
                <div className="phone-country-select-container" ref={countryRef}>
                  <button
                    type="button"
                    className="phone-country-trigger"
                    onClick={() => setCountryDropdownOpen((prev) => !prev)}
                    aria-label="Select Country Code"
                    aria-expanded={countryDropdownOpen}
                  >
                    <img
                      src={`https://flagcdn.com/w40/${selectedCountry.code.toLowerCase()}.png`}
                      alt={selectedCountry.name}
                      className="country-flag-img"
                    />
                    <svg
                      className={`country-chevron-icon ${countryDropdownOpen ? 'is-open' : ''}`}
                      width="10"
                      height="6"
                      viewBox="0 0 10 6"
                      fill="none"
                      stroke="#555"
                      strokeWidth="1.5"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    >
                      <path d="M1 1L5 5L9 1" />
                    </svg>
                  </button>

                  {countryDropdownOpen && (
                    <div className="phone-country-dropdown">
                      <div className="phone-country-search-wrap">
                        <input
                          type="text"
                          className="phone-country-search-input"
                          placeholder="Search country..."
                          value={countrySearch}
                          onChange={(e) => setCountrySearch(e.target.value)}
                          onClick={(e) => e.stopPropagation()}
                          autoFocus
                        />
                      </div>
                      <div className="phone-country-list">
                        {filteredCountries.length > 0 ? (
                          filteredCountries.map((c) => (
                            <button
                              key={c.code}
                              type="button"
                              className={`phone-country-option ${selectedCountry.code === c.code ? "is-selected" : ""}`}
                              onClick={() => {
                                setSelectedCountry(c);
                                setCountryDropdownOpen(false);
                                setCountrySearch("");
                              }}
                            >
                              <img
                                src={`https://flagcdn.com/w40/${c.code.toLowerCase()}.png`}
                                alt={c.name}
                                className="country-flag-img-option"
                                loading="lazy"
                              />
                              <span className="country-name-text">{c.name}</span>
                              <span className="country-dial-code">{c.dialCode}</span>
                            </button>
                          ))
                        ) : (
                          <div className="phone-country-empty">No country found</div>
                        )}
                      </div>
                    </div>
                  )}
                </div>

                <label htmlFor="download-phone">Enter number</label>
                <input
                  id="download-phone"
                  name="phone"
                  type="tel"
                  value={formData.phone}
                  onChange={handleFormChange}
                  autoComplete="tel"
                  required
                />
              </div>

              <div className={`download-field ${formData.email ? "filled" : ""}`}>
                <label htmlFor="download-email">Enter Email id*</label>
                <input
                  id="download-email"
                  name="email"
                  type="email"
                  value={formData.email}
                  onChange={handleFormChange}
                  autoComplete="email"
                  required
                />
              </div>

              <div className={`download-field ${formData.city ? "filled" : ""}`}>
                <label htmlFor="download-city">Select City*</label>
                <select
                  id="download-city"
                  name="city"
                  value={formData.city}
                  onChange={handleFormChange}
                  required
                >
                  <option value="" disabled hidden></option>
                  <option value="Bengaluru">Bengaluru</option>
                  <option value="Delhi">Delhi</option>
                  <option value="Mumbai">Mumbai</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div className={`download-field ${formData.company ? "filled" : ""}`}>
                <label htmlFor="download-company">Enter Company / Firm Name</label>
                <input
                  id="download-company"
                  name="company"
                  value={formData.company}
                  onChange={handleFormChange}
                  autoComplete="organization"
                />
              </div>

              <div className={`download-field full ${formData.role ? "filled" : ""}`}>
                <label htmlFor="download-role">I am a*</label>
                <select
                  id="download-role"
                  name="role"
                  value={formData.role}
                  onChange={handleFormChange}
                  required
                >
                  <option value="" disabled hidden></option>
                  <option value="Architect">Architect</option>
                  <option value="Interior Designer">Interior Designer</option>
                  <option value="Lighting Consultant">Lighting Consultant</option>
                  <option value="Contractor">Contractor</option>
                  <option value="Homeowner">Homeowner</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div className={`download-field full message ${formData.message ? "filled" : ""}`}>
                <label htmlFor="download-message">Tell us more (optional)</label>
                <textarea
                  id="download-message"
                  name="message"
                  value={formData.message}
                  onChange={handleFormChange}
                  placeholder="Quantities, project details, custom finish or cable length, timeline .................."
                />
              </div>

              <label className="download-captcha">
                <input
                  type="checkbox"
                  name="captcha"
                  checked={formData.captcha}
                  onChange={handleFormChange}
                  required
                />
                <span>I'm not a robot</span>
              </label>

              <button
                className="download-submit"
                type="submit"
                disabled={submitting || submitted}
                style={{ opacity: submitting ? 0.7 : 1, cursor: submitting ? "not-allowed" : "pointer" }}
              >
                {submitting ? "SUBMITTING..." : submitted ? "DOWNLOAD STARTED" : "SUBMIT FORM"}
              </button>

              {submitError && (
                <p className="download-error" style={{ color: "#dc2626", fontSize: "13px", marginTop: "8px", textAlign: "center" }}>
                  {submitError}
                </p>
              )}

              <p className="download-consent">
                By submitting this form, you agree to be contacted by <span>Abby Lighting</span> regarding your enquiry.
              </p>

              {submitted && (
                <p className="download-status" role="status">
                  Thank you. Your request for <strong>{selectedCatalogue?.title || "catalogue"}</strong> has been received and your download has started.
                </p>
              )}

            </form>
          </section>
        </div>
      )}
    </div>
  );
}
