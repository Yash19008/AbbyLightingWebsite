import React, { useState, useRef, useEffect } from "react";

export interface CustomSelectOption {
  value: string;
  label: string;
}

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

export default function CustomFormSelect({
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
