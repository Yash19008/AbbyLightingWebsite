"use client";

import React, { useEffect, useState, useRef, useMemo } from "react";
import { COUNTRIES, Country } from "@/lib/countries";

interface EnquireModalProps {
  isOpen: boolean;
  onClose: () => void;
  productName: string;
  selectedVariant?: string;
}

type SubmitState = "idle" | "submitting" | "success" | "error";

interface CustomSelectOption {
  value: string;
  label: string;
}

const CITY_OPTIONS: CustomSelectOption[] = [
  { value: "Bengaluru", label: "Bengaluru" },
  { value: "Delhi", label: "Delhi" },
  { value: "Mumbai", label: "Mumbai" },
  { value: "Other", label: "Other" },
];

const ROLE_OPTIONS: CustomSelectOption[] = [
  { value: "Architect", label: "Architect" },
  { value: "Interior Designer", label: "Interior Designer" },
  { value: "Lighting Consultant", label: "Lighting Consultant" },
  { value: "Contractor", label: "Contractor" },
  { value: "Homeowner", label: "Homeowner" },
  { value: "Other", label: "Other" },
];

interface CustomFormSelectProps {
  id: string;
  name: string;
  placeholder: string;
  value: string;
  options: CustomSelectOption[];
  onChange: (name: string, value: string) => void;
  required?: boolean;
  className?: string;
}

function CustomFormSelect({
  id,
  name,
  placeholder,
  value,
  options,
  onChange,
  className = "",
}: CustomFormSelectProps) {
  const [isOpen, setIsOpen] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    function handleClickOutside(e: MouseEvent) {
      if (containerRef.current && !containerRef.current.contains(e.target as Node)) {
        setIsOpen(false);
      }
    }
    function handleKeyDown(e: KeyboardEvent) {
      if (e.key === "Escape") {
        setIsOpen(false);
      }
    }
    document.addEventListener("click", handleClickOutside);
    document.addEventListener("keydown", handleKeyDown);
    return () => {
      document.removeEventListener("click", handleClickOutside);
      document.removeEventListener("keydown", handleKeyDown);
    };
  }, []);

  const selectedOption = options.find((opt) => opt.value === value);

  return (
    <div
      className={`figma-enquiry-field ${className} ${isOpen ? "dropdown-active" : ""}`}
      ref={containerRef}
      style={{ zIndex: isOpen ? 60 : 1 }}
    >
      <div className={`download-select-wrap ${isOpen ? "is-open" : ""}`}>
        <button
          id={id}
          type="button"
          className={`download-select-trigger ${isOpen ? "is-open" : ""}`}
          aria-haspopup="listbox"
          aria-expanded={isOpen}
          onClick={() => setIsOpen((prev) => !prev)}
        >
          <span className={`download-select-label ${!selectedOption ? "is-placeholder" : ""}`}>
            {selectedOption ? selectedOption.label : placeholder}
          </span>
          <svg
            className={`download-select-arrow ${isOpen ? "is-open" : ""}`}
            width="12"
            height="8"
            viewBox="0 0 12 8"
            fill="none"
            aria-hidden="true"
          >
            <path
              d="M1.5 1.75L6 6.25L10.5 1.75"
              stroke="#666666"
              strokeWidth="1.6"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
        </button>

        {isOpen && (
          <div className="download-select-menu" role="listbox">
            {options.map((opt) => (
              <button
                key={opt.value}
                type="button"
                role="option"
                aria-selected={opt.value === value}
                className={`download-select-option ${opt.value === value ? "is-selected" : ""}`}
                onClick={() => {
                  onChange(name, opt.value);
                  setIsOpen(false);
                }}
              >
                {opt.label}
              </button>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

export default function EnquireModal({
  isOpen,
  onClose,
  productName,
  selectedVariant,
}: EnquireModalProps) {
  const [submitState, setSubmitState] = useState<SubmitState>("idle");
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

  // Country Flag Selector State
  const [selectedCountry, setSelectedCountry] = useState<Country>(COUNTRIES[0]);
  const [countryDropdownOpen, setCountryDropdownOpen] = useState(false);
  const [countrySearch, setCountrySearch] = useState("");
  const countryRef = useRef<HTMLDivElement>(null);

  const filteredCountries = useMemo(() => {
    if (!countrySearch.trim()) return COUNTRIES;
    const q = countrySearch.toLowerCase();
    return COUNTRIES.filter(
      (c) =>
        c.name.toLowerCase().includes(q) ||
        c.dialCode.includes(q) ||
        c.code.toLowerCase().includes(q)
    );
  }, [countrySearch]);

  // Lock body scroll while modal is open
  useEffect(() => {
    document.body.style.overflow = isOpen ? "hidden" : "";
    return () => {
      document.body.style.overflow = "";
    };
  }, [isOpen]);

  // Close on Escape key
  useEffect(() => {
    if (!isOpen) return;
    const handleKey = (e: KeyboardEvent) => e.key === "Escape" && onClose();
    window.addEventListener("keydown", handleKey);
    return () => window.removeEventListener("keydown", handleKey);
  }, [isOpen, onClose]);

  // Close dropdowns on outside click
  useEffect(() => {
    function handleClickOutside(e: MouseEvent) {
      if (countryRef.current && !countryRef.current.contains(e.target as Node)) {
        setCountryDropdownOpen(false);
      }
    }
    document.addEventListener("click", handleClickOutside);
    return () => document.removeEventListener("click", handleClickOutside);
  }, []);

  // Reset form state when modal closes
  useEffect(() => {
    if (!isOpen) {
      setSubmitState("idle");
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
      setCountryDropdownOpen(false);
      setCountrySearch("");
    }
  }, [isOpen]);

  if (!isOpen) return null;

  const handleFormChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>
  ) => {
    const { name, value, type } = e.target;
    if (type === "checkbox") {
      const checked = (e.target as HTMLInputElement).checked;
      setFormData((prev) => ({ ...prev, [name]: checked }));
    } else {
      setFormData((prev) => ({ ...prev, [name]: value }));
    }
  };

  const handleSelectChange = (name: string, value: string) => {
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitState("submitting");
    // Simulate brief network delay
    await new Promise((r) => setTimeout(r, 400));
    setSubmitState("success");
  };

  return (
    <div
      className="figma-enquiry"
      role="presentation"
      onClick={(e) => e.target === e.currentTarget && onClose()}
    >
      <div
        className={`figma-enquiry-card ${submitState === "success" ? "figma-enquiry-card-thankyou" : ""}`}
        role="dialog"
        aria-modal="true"
        aria-labelledby={submitState === "success" ? "enquiry-thankyou-title" : "enquiry-modal-title"}
      >
        <button
          className="figma-enquiry-close"
          aria-label="Close enquiry form"
          onClick={onClose}
        >
          ×
        </button>

        {submitState === "success" ? (
          <div className="figma-enquiry-thankyou-card">
            <div className="figma-enquiry-thankyou-icon" aria-hidden="true">
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#111111"
                strokeWidth="2.5"
                strokeLinecap="round"
                strokeLinejoin="round"
              >
                <polyline points="20 6 9 17 4 12" />
              </svg>
            </div>
            <h2 id="enquiry-thankyou-title" className="figma-enquiry-thankyou-title">
              Thank You for Your Interest
            </h2>
            <p className="figma-enquiry-thankyou-desc">
              Thank you for reaching out. Our team will get back to you shortly with pricing, finishes and lead time.
            </p>
            <button
              type="button"
              className="figma-enquiry-thankyou-btn"
              onClick={onClose}
            >
              CONTINUE
            </button>
          </div>
        ) : (
          <>
            <h2 id="enquiry-modal-title">Product Enquiry</h2>
            <p className="figma-enquiry-lead">
              Tell us about your project, and our team will get back to you shortly.
            </p>
            <form onSubmit={handleSubmit} noValidate className="figma-enquiry-form">
              <div className="figma-enquiry-grid">
                <div className="figma-enquiry-field full filled">
                  <input
                    defaultValue={selectedVariant ? `${productName} — ${selectedVariant}` : productName}
                    readOnly
                    aria-label="Selected product"
                    className="read-only-product"
                  />
                </div>

                <div className={`figma-enquiry-field full ${formData.name ? "filled" : ""}`}>
                  <label htmlFor="enquiry-name">Your name*</label>
                  <input
                    id="enquiry-name"
                    name="name"
                    value={formData.name}
                    onChange={handleFormChange}
                    autoComplete="name"
                    required
                  />
                </div>

                <div
                  className={`figma-enquiry-field phone-field-wrap ${formData.phone ? "filled" : ""} ${countryDropdownOpen ? "dropdown-open" : ""}`}
                  style={{ zIndex: countryDropdownOpen ? 70 : 1 }}
                >
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

                  <label htmlFor="enquiry-phone">Enter number</label>
                  <input
                    id="enquiry-phone"
                    name="phone"
                    type="tel"
                    value={formData.phone}
                    onChange={handleFormChange}
                    autoComplete="tel"
                    required
                  />
                </div>

                <div className={`figma-enquiry-field ${formData.email ? "filled" : ""}`}>
                  <label htmlFor="enquiry-email">Enter Email id*</label>
                  <input
                    id="enquiry-email"
                    name="email"
                    type="email"
                    value={formData.email}
                    onChange={handleFormChange}
                    autoComplete="email"
                    required
                  />
                </div>

                <CustomFormSelect
                  id="enquiry-city"
                  name="city"
                  placeholder="Select City*"
                  value={formData.city}
                  options={CITY_OPTIONS}
                  onChange={handleSelectChange}
                  required
                />

                <div className={`figma-enquiry-field ${formData.company ? "filled" : ""}`}>
                  <label htmlFor="enquiry-company">Enter Company / Firm Name</label>
                  <input
                    id="enquiry-company"
                    name="company"
                    value={formData.company}
                    onChange={handleFormChange}
                    autoComplete="organization"
                  />
                </div>

                <CustomFormSelect
                  id="enquiry-role"
                  name="role"
                  placeholder="I am a*"
                  value={formData.role}
                  options={ROLE_OPTIONS}
                  onChange={handleSelectChange}
                  className="full"
                  required
                />

                <div className={`figma-enquiry-field full message ${formData.message ? "filled" : ""}`}>
                  <label htmlFor="enquiry-message">Tell us more (optional)</label>
                  <textarea
                    id="enquiry-message"
                    name="message"
                    value={formData.message}
                    onChange={handleFormChange}
                    placeholder={`Tell us more — ${productName}`}
                  />
                </div>
              </div>

              <label className="download-captcha figma-captcha-box">
                <div className="download-captcha-left">
                  <input
                    type="checkbox"
                    name="captcha"
                    checked={formData.captcha}
                    onChange={handleFormChange}
                    required
                  />
                  <span>I&apos;m not a robot</span>
                </div>
                <div className="download-captcha-badge">
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#4285f4"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    aria-hidden="true"
                  >
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    <polyline points="9 12 11 14 15 10" />
                  </svg>
                </div>
              </label>

              <button
                className="figma-enquiry-submit"
                type="submit"
                disabled={submitState === "submitting"}
              >
                {submitState === "submitting" ? "SENDING…" : "SEND ENQUIRY"}
              </button>

              <p className="figma-enquiry-consent">
                By submitting this form, you agree to be contacted by{" "}
                <em>Abby Lighting</em> regarding your enquiry.
              </p>
            </form>
          </>
        )}
      </div>
    </div>
  );
}
