"use client";

import React, { useEffect, useState } from "react";

interface EnquireModalProps {
  isOpen: boolean;
  onClose: () => void;
  productName: string;
  selectedVariant?: string;
}

type SubmitState = "idle" | "submitting" | "success" | "error";

export default function EnquireModal({
  isOpen,
  onClose,
  productName,
  selectedVariant,
}: EnquireModalProps) {
  const [submitState, setSubmitState] = useState<SubmitState>("idle");

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

  // Reset form state when modal closes
  useEffect(() => {
    if (!isOpen) setSubmitState("idle");
  }, [isOpen]);

  if (!isOpen) return null;

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitState("submitting");
    // TODO: wire up to real API endpoint
    await new Promise((r) => setTimeout(r, 800)); // simulate network
    setSubmitState("success");
  };

  return (
    <div
      className="figma-enquiry"
      role="presentation"
      onClick={(e) => e.target === e.currentTarget && onClose()}
    >
      <div
        className="figma-enquiry-card"
        role="dialog"
        aria-modal="true"
        aria-labelledby="enquiry-modal-title"
      >
        <button
          className="figma-enquiry-close"
          aria-label="Close enquiry form"
          onClick={onClose}
        >
          ×
        </button>

        {submitState === "success" ? (
          <div className="figma-enquiry-success">
            <span>✓</span>
            <h2>Enquiry Sent!</h2>
            <p>Our team will get back to you shortly with pricing, finishes and lead time.</p>
            <button className="figma-enquiry-submit" onClick={onClose}>
              Close
            </button>
          </div>
        ) : (
          <>
            <h2 id="enquiry-modal-title">Product Enquiry</h2>
            <p className="figma-enquiry-lead">
              Tell us about your project, and our team will get back to you shortly.
            </p>
            <form onSubmit={handleSubmit}>
              <div className="figma-enquiry-grid">
                <input
                  className="full"
                  defaultValue={selectedVariant ? `${productName} — ${selectedVariant}` : productName}
                  readOnly
                  aria-label="Selected product"
                />
                <input className="full" required placeholder="Name*" />
                <input required placeholder="Mobile number*" />
                <input type="email" required placeholder="Email id*" />
                <select required>
                  <option value="">City*</option>
                  <option value="Mumbai">Mumbai</option>
                  <option value="Delhi NCR">Delhi NCR</option>
                  <option value="Bengaluru">Bengaluru</option>
                  <option value="Other">Other</option>
                </select>
                <input placeholder="Company / Firm Name" />
                <select className="full" required>
                  <option value="">I am a*</option>
                  <option value="Architect">Architect</option>
                  <option value="Interior Designer">Interior Designer</option>
                  <option value="Homeowner">Homeowner</option>
                  <option value="Other">Other</option>
                </select>
                <textarea
                  className="full"
                  placeholder={`Tell us more — ${productName}`}
                />
              </div>
              <div className="figma-captcha">
                <i></i> I am not a robot
              </div>
              <button
                className="figma-enquiry-submit"
                type="submit"
                disabled={submitState === "submitting"}
              >
                {submitState === "submitting" ? "Sending…" : "SEND ENQUIRY"}
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
