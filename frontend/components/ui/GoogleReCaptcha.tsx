"use client";

import React, {
  useEffect,
  useRef,
  useImperativeHandle,
  forwardRef,
} from "react";
import { RECAPTCHA_SITE_KEY } from "@/lib/config";

declare global {
  interface Window {
    grecaptcha?: {
      ready: (callback: () => void) => void;
      render: (
        container: string | HTMLElement,
        parameters: {
          sitekey: string;
          theme?: "light" | "dark";
          size?: "normal" | "compact";
          tabindex?: number;
          callback?: (response: string) => void;
          "expired-callback"?: () => void;
          "error-callback"?: () => void;
        }
      ) => number;
      reset: (opt_widget_id?: number) => void;
      getResponse: (opt_widget_id?: number) => string;
    };
    __grecaptchaCallbacks?: Array<() => void>;
  }
}

export interface GoogleReCaptchaProps {
  onVerify: (token: string) => void;
  onExpire?: () => void;
  onError?: () => void;
  className?: string;
  theme?: "light" | "dark";
}

export interface GoogleReCaptchaHandle {
  reset: () => void;
  getResponse: () => string;
}

const SCRIPT_ID = "google-recaptcha-v2-script";

function ensureRecaptchaScript(onReady: () => void) {
  if (typeof window === "undefined") return;

  if (window.grecaptcha && typeof window.grecaptcha.render === "function") {
    window.grecaptcha.ready(onReady);
    return;
  }

  if (!window.__grecaptchaCallbacks) {
    window.__grecaptchaCallbacks = [];
  }
  window.__grecaptchaCallbacks.push(onReady);

  const existingScript = document.getElementById(SCRIPT_ID);
  if (existingScript) {
    return;
  }

  const script = document.createElement("script");
  script.id = SCRIPT_ID;
  script.src = "https://www.google.com/recaptcha/api.js?render=explicit";
  script.async = true;
  script.defer = true;

  const triggerCallbacks = () => {
    if (window.grecaptcha && typeof window.grecaptcha.ready === "function") {
      window.grecaptcha.ready(() => {
        window.__grecaptchaCallbacks?.forEach((cb) => {
          try {
            cb();
          } catch (e) {
            console.error("Error executing grecaptcha ready callback:", e);
          }
        });
        window.__grecaptchaCallbacks = [];
      });
    }
  };

  script.onload = triggerCallbacks;
  script.onerror = () => {
    console.error("Failed to load Google reCAPTCHA script.");
  };

  document.head.appendChild(script);
}

const GoogleReCaptcha = forwardRef<GoogleReCaptchaHandle, GoogleReCaptchaProps>(
  ({ onVerify, onExpire, onError, className = "", theme = "light" }, ref) => {
    const containerRef = useRef<HTMLDivElement>(null);
    const widgetIdRef = useRef<number | null>(null);
    const isRenderingRef = useRef(false);

    // Keep callback refs always up-to-date without triggering effect re-runs
    const onVerifyRef = useRef(onVerify);
    onVerifyRef.current = onVerify;

    const onExpireRef = useRef(onExpire);
    onExpireRef.current = onExpire;

    const onErrorRef = useRef(onError);
    onErrorRef.current = onError;

    useImperativeHandle(ref, () => ({
      reset: () => {
        if (
          widgetIdRef.current !== null &&
          window.grecaptcha &&
          typeof window.grecaptcha.reset === "function"
        ) {
          try {
            window.grecaptcha.reset(widgetIdRef.current);
          } catch (err) {
            console.warn("reCAPTCHA reset warning:", err);
          }
        }
      },
      getResponse: () => {
        if (
          widgetIdRef.current !== null &&
          window.grecaptcha &&
          typeof window.grecaptcha.getResponse === "function"
        ) {
          try {
            return window.grecaptcha.getResponse(widgetIdRef.current);
          } catch {
            return "";
          }
        }
        return "";
      },
    }));

    useEffect(() => {
      let isMounted = true;
      const container = containerRef.current;

      const renderWidget = () => {
        if (!isMounted || !container || !window.grecaptcha) return;
        if (widgetIdRef.current !== null || isRenderingRef.current) return;

        isRenderingRef.current = true;

        try {
          // Clear any stale children
          container.innerHTML = "";

          // Create a brand new DOM element so Google reCAPTCHA never encounters an already-used element
          const targetEl = document.createElement("div");
          container.appendChild(targetEl);

          const id = window.grecaptcha.render(targetEl, {
            sitekey: RECAPTCHA_SITE_KEY,
            theme: theme,
            callback: (token: string) => {
              if (isMounted) onVerifyRef.current(token);
            },
            "expired-callback": () => {
              if (isMounted) onExpireRef.current?.();
            },
            "error-callback": () => {
              if (isMounted) onErrorRef.current?.();
            },
          });

          widgetIdRef.current = id;
        } catch (err) {
          console.error("Error rendering Google reCAPTCHA widget:", err);
        } finally {
          isRenderingRef.current = false;
        }
      };

      ensureRecaptchaScript(() => {
        if (isMounted) {
          renderWidget();
        }
      });

      return () => {
        isMounted = false;
        if (widgetIdRef.current !== null && window.grecaptcha) {
          try {
            window.grecaptcha.reset(widgetIdRef.current);
          } catch {
            // Ignore reset during unmount
          }
          widgetIdRef.current = null;
        }
        if (container) {
          container.innerHTML = "";
        }
      };
    }, [theme]);

    return (
      <div
        className={`google-recaptcha-wrapper ${className}`}
        style={{ minHeight: "78px" }}
      >
        <div ref={containerRef} className="g-recaptcha-container" />
      </div>
    );
  }
);

GoogleReCaptcha.displayName = "GoogleReCaptcha";

export default GoogleReCaptcha;
