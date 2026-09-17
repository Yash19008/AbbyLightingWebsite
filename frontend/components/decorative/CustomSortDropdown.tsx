'use client';

import React, { useState, useEffect, useRef } from 'react';

const SORT_OPTIONS = [
  { label: 'New products', value: 'new' },
  { label: 'Alphabetical A-Z', value: 'name_asc' },
  { label: 'Alphabetical Z-A', value: 'name_desc' },
];

export default function CustomSortDropdown({
  value,
  onChange,
}: {
  value: string;
  onChange: (val: string) => void;
}) {
  const [isOpen, setIsOpen] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
      if (containerRef.current && !containerRef.current.contains(e.target as Node)) {
        setIsOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const activeLabel = SORT_OPTIONS.find((opt) => opt.value === value)?.label || 'Newest';

  return (
    <div
      ref={containerRef}
      style={{
        position: 'relative',
        display: 'inline-block',
        fontFamily: 'Inter, sans-serif',
        textAlign: 'left'
      }}
    >

      <div
        className="abby-desktop-action-btn decorative-sort"
        onClick={() => setIsOpen(!isOpen)}
      >
        <svg
          width="14"
          height="14"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          strokeWidth="1.5"
          strokeLinecap="round"
          strokeLinejoin="round"
        >
          <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
        </svg>
        <span>Sort By</span>
      </div>

      {isOpen && (
        <div
          style={{
            position: 'absolute',
            top: '100%',
            left: 0,
            minWidth: '180px',
            background: '#fff',
            border: '1px solid #1a1c1d',
            borderTop: 'none',
            zIndex: 10,
            boxShadow: '0 4px 12px rgba(0,0,0,0.1)',
            padding: '8px 0'
          }}
        >
          {SORT_OPTIONS.map((opt) => (
            <div
              key={opt.value}
              onClick={() => {
                onChange(opt.value);
                setIsOpen(false);
              }}
              style={{
                padding: '10px 16px',
                cursor: 'pointer',
                fontSize: '13px',
                color: value === opt.value ? '#1a1c1d' : '#6f6f6f',
                fontWeight: value === opt.value ? 500 : 400,
                transition: 'background 0.2s',
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.background = '#f7f7f7';
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.background = '#fff';
              }}
            >
              {opt.label}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
