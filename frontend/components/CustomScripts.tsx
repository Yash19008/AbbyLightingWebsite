'use client';

import { useEffect, useRef } from 'react';

interface CustomScriptsProps {
  headerHtml?: string;
  footerHtml?: string;
  enabled?: boolean;
}

export default function CustomScripts({ headerHtml, footerHtml, enabled }: CustomScriptsProps) {
  const headerExecutedRef = useRef(false);
  const footerExecutedRef = useRef(false);

  useEffect(() => {
    if (!enabled) return;

    // Helper to execute any script tags inside raw HTML if needed
    const executeScripts = (html: string, targetNode: HTMLElement, executedRef: React.MutableRefObject<boolean>) => {
      if (!html || executedRef.current) return;
      executedRef.current = true;

      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = html;

      const scripts = tempDiv.querySelectorAll('script');
      scripts.forEach((oldScript) => {
        const newScript = document.createElement('script');
        Array.from(oldScript.attributes).forEach((attr) => {
          newScript.setAttribute(attr.name, attr.value);
        });
        newScript.textContent = oldScript.textContent;
        targetNode.appendChild(newScript);
      });

      // Also append non-script tags (like <noscript>, <style>, <link>, <meta>)
      Array.from(tempDiv.children).forEach((child) => {
        if (child.tagName !== 'SCRIPT') {
          targetNode.appendChild(child.cloneNode(true));
        }
      });
    };

    if (headerHtml && !headerExecutedRef.current) {
      executeScripts(headerHtml, document.head, headerExecutedRef);
    }

    if (footerHtml && !footerExecutedRef.current) {
      executeScripts(footerHtml, document.body, footerExecutedRef);
    }
  }, [headerHtml, footerHtml, enabled]);

  return null;
}

