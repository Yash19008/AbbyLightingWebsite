import React, { useState, useRef, useMemo, useEffect } from "react";
import { COUNTRIES, Country } from "@/lib/countries";

interface PhoneInputGroupProps {
  id?: string;
  name?: string;
  label?: string;
  value: string;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  required?: boolean;
  selectedCountry: Country;
  onCountryChange: (country: Country) => void;
  className?: string;
}

export default function PhoneInputGroup({
  id = "phone",
  name = "phone",
  label = "Enter number",
  value,
  onChange,
  required = true,
  selectedCountry,
  onCountryChange,
  className = "download-field",
}: PhoneInputGroupProps) {
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

  useEffect(() => {
    function handleClickOutside(e: MouseEvent) {
      if (countryRef.current && !countryRef.current.contains(e.target as Node)) {
        setCountryDropdownOpen(false);
      }
    }
    document.addEventListener("click", handleClickOutside);
    return () => document.removeEventListener("click", handleClickOutside);
  }, []);

  return (
    <div className={`${className} phone-field-wrap ${value ? "filled" : ""} ${countryDropdownOpen ? "dropdown-open" : ""}`} style={{ zIndex: countryDropdownOpen ? 70 : 1 }}>
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
                      onCountryChange(c);
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

      <label htmlFor={id}>{label}</label>
      <input
        id={id}
        name={name}
        type="tel"
        value={value}
        onChange={onChange}
        autoComplete="tel"
        required={required}
      />
    </div>
  );
}
