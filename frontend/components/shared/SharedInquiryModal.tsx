"use client";
import "@/styles/shared-inquiry.css";
import React, { useEffect, useState, useRef, useCallback } from "react";
import { COUNTRIES, Country } from "@/lib/countries";
import PhoneInputGroup from "@/components/ui/PhoneInputGroup";
import CustomFormSelect, { CustomSelectOption } from "@/components/ui/CustomFormSelect";
import GoogleReCaptcha, { GoogleReCaptchaHandle } from "@/components/ui/GoogleReCaptcha";
import { API_URL } from "@/lib/config";

export type InquiryType = "product" | "catalogue" | "calculator" | "general";

interface SharedInquiryModalProps {
  isOpen: boolean;
  onClose: () => void;
  type: InquiryType;
  title: string;
  subtitle: string;
  reference?: string; // Context (e.g. Product Name or Catalogue Name)
  catalogueDownloadUrl?: string; // Only used for type === "catalogue"
  cataloguePdfUrl?: string; // Only used for type === "catalogue" fallback
}

type SubmitState = "idle" | "submitting" | "success" | "error";

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

export default function SharedInquiryModal({
  isOpen,
  onClose,
  type,
  title,
  subtitle,
  reference,
  catalogueDownloadUrl,
  cataloguePdfUrl,
}: SharedInquiryModalProps) {
  const [submitState, setSubmitState] = useState<SubmitState>("idle");
  const [formData, setFormData] = useState({
    name: "",
    phone: "",
    email: "",
    city: "",
    company: "",
    role: "",
    message: "",
  });

  const [selectedCountry, setSelectedCountry] = useState<Country>(COUNTRIES[0]);
  const [apiErrors, setApiErrors] = useState<Record<string, string[]>>({});
  const [captchaToken, setCaptchaToken] = useState<string>("");
  const [captchaError, setCaptchaError] = useState(false);
  const recaptchaRef = useRef<GoogleReCaptchaHandle>(null);

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

  // Initialize form state when modal opens
  useEffect(() => {
    if (isOpen) {
      setSubmitState("idle");
      setApiErrors({});
      setCaptchaToken("");
      setCaptchaError(false);
      recaptchaRef.current?.reset();
      setFormData({
        name: "",
        phone: "",
        email: "",
        city: "",
        company: "",
        role: "",
        message: "",
      });
    }
  }, [isOpen, type, reference]);

  const handleCaptchaVerify = useCallback((token: string) => {
    setCaptchaToken(token);
    setCaptchaError(false);
    setApiErrors((prev) => {
      if (!prev["g-recaptcha-response"]) return prev;
      return { ...prev, "g-recaptcha-response": [] };
    });
  }, []);

  const handleCaptchaExpire = useCallback(() => {
    setCaptchaToken("");
  }, []);

  const handleFormChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>
  ) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    if (apiErrors[name]) setApiErrors((prev) => ({ ...prev, [name]: [] }));
    if (apiErrors.general) setApiErrors((prev) => ({ ...prev, general: [] }));
  };

  const handleSelectChange = (name: string, value: string) => {
    setFormData((prev) => ({ ...prev, [name]: value }));
    if (apiErrors[name]) setApiErrors((prev) => ({ ...prev, [name]: [] }));
    if (apiErrors.general) setApiErrors((prev) => ({ ...prev, general: [] }));
  };

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setApiErrors({});
    if (!captchaToken) {
      setCaptchaError(true);
      return;
    }
    setSubmitState("submitting");
    try {
      const res = await fetch(`${API_URL}/inquiries`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          type: type,
          reference: reference || null,
          name: formData.name,
          phone: formData.phone ? `${selectedCountry.dialCode} ${formData.phone}` : '',
          email: formData.email,
          city: formData.city,
          company: formData.company,
          role: formData.role,
          message: formData.message,
          'g-recaptcha-response': captchaToken,
        }),
      });
      
      const data = await res.json().catch(() => null);
      
      if (!res.ok) {
        if (res.status === 422 && data?.errors) {
          setApiErrors(data.errors);
          setSubmitState("idle");
          if (data.errors['g-recaptcha-response']) {
            setCaptchaError(true);
            setCaptchaToken("");
            recaptchaRef.current?.reset();
          }
          return;
        }
        throw new Error(data?.message || 'Server error');
      }
      
      setSubmitState("success");
      setCaptchaToken("");
      recaptchaRef.current?.reset();
    } catch {
      setSubmitState("error");
      setApiErrors({ general: ["Failed to send enquiry. Please try again later."] });
    }
  };

  const renderError = (field: string) => {
    if (!apiErrors[field] || apiErrors[field].length === 0) return null;
    return <span className="figma-enquiry-error" style={{ color: '#e53e3e', fontSize: '12px', marginTop: '4px', display: 'block', fontWeight: 500 }}>{apiErrors[field][0]}</span>;
  };

  const handleDownloadTrigger = () => {
    if (catalogueDownloadUrl) {
      const cleanName = (reference || "catalogue").toLowerCase().replace(/[^a-z0-9]+/g, "-");
      
      const link = document.createElement("a");
      link.href = catalogueDownloadUrl;
      link.download = `${cleanName}-catalogue.pdf`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    } else if (cataloguePdfUrl) {
      window.open(cataloguePdfUrl, "_blank");
    }
  };

  if (!isOpen) return null;

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
              {type === "catalogue" 
                ? "Your download should have started automatically. If it hasn't, click the link below to download the catalogue."
                : "Thank you for reaching out. Our team will get back to you shortly."
              }
            </p>
            {type === "catalogue" ? (
              <button
                type="button"
                className="figma-enquiry-thankyou-btn"
                onClick={handleDownloadTrigger}
              >
                DOWNLOAD
              </button>
            ) : (
              <button
                type="button"
                className="figma-enquiry-thankyou-btn"
                onClick={onClose}
              >
                CONTINUE
              </button>
            )}
          </div>
        ) : (
          <>
            <h2 id="enquiry-modal-title">{title}</h2>
            <p className="figma-enquiry-lead">
              {subtitle}
            </p>
            <form onSubmit={handleSubmit} noValidate className="figma-enquiry-form">
              <div className="figma-enquiry-grid">
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
                  {renderError("name")}
                </div>

                <PhoneInputGroup
                  className="figma-enquiry-field"
                  id="enquiry-phone"
                  name="phone"
                  label="Enter number"
                  value={formData.phone}
                  onChange={handleFormChange}
                  selectedCountry={selectedCountry}
                  onCountryChange={setSelectedCountry}
                />
                {renderError("phone")}

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
                  {renderError("email")}
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
                {renderError("role")}
                {renderError("city")}

                <div className={`figma-enquiry-field ${formData.company ? "filled" : ""}`}>
                  <label htmlFor="enquiry-company">Enter Company / Firm Name</label>
                  <input
                    id="enquiry-company"
                    name="company"
                    value={formData.company}
                    onChange={handleFormChange}
                    autoComplete="organization"
                  />
                  {renderError("company")}
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
                    placeholder="Tell us more"
                  />
                  {renderError("message")}
                </div>
              </div>

              {apiErrors.general && <div style={{color: '#e53e3e', fontSize: '13px', marginBottom: '10px', textAlign: 'center', fontWeight: 600}}>{apiErrors.general[0]}</div>}
              <div className="figma-captcha-wrapper">
                <GoogleReCaptcha
                  ref={recaptchaRef}
                  onVerify={handleCaptchaVerify}
                  onExpire={handleCaptchaExpire}
                />
                {captchaError && (
                  <div className="figma-captcha-error" role="alert">
                    Please complete Google Captcha verification.
                  </div>
                )}
                {apiErrors["g-recaptcha-response"] && (
                  <div className="figma-captcha-error" role="alert">
                    {apiErrors["g-recaptcha-response"][0]}
                  </div>
                )}
              </div>

              <button
                className="figma-enquiry-submit"
                type="submit"
                disabled={submitState === "submitting"}
              >
                {submitState === "submitting" ? (
                  <span style={{ display: 'inline-flex', alignItems: 'center', gap: '8px' }}>
                    <svg className="animate-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="3" strokeDasharray="32" strokeLinecap="round" opacity="0.3"></circle>
                      <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" strokeWidth="3" strokeLinecap="round"></path>
                    </svg>
                    SENDING...
                  </span>
                ) : "SEND ENQUIRY"}
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
