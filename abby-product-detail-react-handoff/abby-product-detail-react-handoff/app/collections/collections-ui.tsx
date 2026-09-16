"use client";

import { useEffect, useLayoutEffect, useRef, useState } from "react";
import type React from "react";
import { createPortal } from "react-dom";

const forms = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X"];
const menus: Record<string, { title: string; links: [string, string][] }> = {
  product: { title: "Products", links: [["Architectural", "/home#worlds"], ["Decorative", "/decorative3"], ["Outdoor", "/home#worlds"], ["Smart Lighting", "/home#worlds"]] },
  work: { title: "Our Work", links: [["Projects", "/home#projects"], ["Clients", "/home#clients"]] },
  inspiration: { title: "Inspiration", links: [["Stories & News", "/home#news"], ["Catalogues", "/home#contact"]] },
  more: { title: "More", links: [["About Us", "/home#contact"], ["Contact Us", "/home#contact"], ["Careers", "/home#contact"], ["Fairs & Events", "/home#contact"]] },
};

function ProductCard({ roman, index }: { roman: string; index: number }) {
  const [view, setView] = useState(0);
  const [finish, setFinish] = useState(0);
  const [revealed, setRevealed] = useState(false);
  const card = useRef<HTMLElement>(null);
  const src = `/images/symphony/product-${index + 1}.png`;
  const colours = ["#f47829", "#deb34f", "#1e1e1e", "#f2f0ea"];
  useEffect(() => {
    if (!card.current) return;
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) { setRevealed(true); observer.disconnect(); }
    }, { rootMargin: "0px 0px -100px", threshold: 0.01 });
    observer.observe(card.current);
    return () => observer.disconnect();
  }, []);
  useEffect(() => {
    const track = document.querySelector<HTMLElement>(".s-tone-grid");
    const next = document.querySelector<HTMLButtonElement>(".s-tone-next");
    const dots = Array.from(document.querySelectorAll<HTMLElement>(".s-tone-dots i"));
    if (!track || !next) return;
    const updateDots = () => {
      const cards = Array.from(track.querySelectorAll<HTMLElement>("article"));
      const index = cards.reduce((best, card, i) => Math.abs(card.offsetLeft - track.scrollLeft) < Math.abs(cards[best].offsetLeft - track.scrollLeft) ? i : best, 0);
      dots.forEach((dot, i) => dot.classList.toggle("active", i === index));
    };
    const advance = () => {
      const cards = Array.from(track.querySelectorAll<HTMLElement>("article"));
      const current = cards.findIndex(card => Math.abs(card.offsetLeft - track.scrollLeft) < card.offsetWidth / 2);
      const target = cards[(current + 1 + cards.length) % cards.length];
      track.scrollTo({ left: target.offsetLeft, behavior: window.matchMedia("(prefers-reduced-motion: reduce)").matches ? "auto" : "smooth" });
    };
    next.addEventListener("click", advance);
    track.addEventListener("scroll", updateDots, { passive: true });
    return () => { next.removeEventListener("click", advance); track.removeEventListener("scroll", updateDots); };
  }, []);
  return <div className="decorative-grid-item-motion" style={{ "--filter-delay": `${index * 40}ms` } as React.CSSProperties}>
    <article ref={card} className={`decorative-card ${revealed ? "is-revealed" : ""}`} style={{ "--card-order": index % 3, "--light-delay": `${index * 50}ms`, "--motion-delay": `${Math.min(index, 6) * 60}ms` } as React.CSSProperties}>
      <div className="decorative-card-image is-lit">
        <a className="decorative-card-main-link" href="/product-detail/symphony-iv" aria-label={`View Symphony ${roman}`}>
          <img className="decorative-product-image is-current is-light-off" src={src} alt="" aria-hidden="true" />
          <img className="decorative-product-image is-current is-light-on" src={src} alt={`Symphony ${roman} pendant light`} />
        </a>
        <button type="button" className="decorative-card-arrow decorative-card-prev" aria-label={`Previous Symphony ${roman} image`} onClick={() => setView((view + 2) % 3)}>‹</button>
        <button type="button" className="decorative-card-arrow decorative-card-next" aria-label={`Next Symphony ${roman} image`} onClick={() => setView((view + 1) % 3)}>›</button>
        <span className="decorative-gallery-dots">{[0, 1, 2].map(i => <button key={i} className={view === i ? "active" : ""} type="button" aria-label={`Show Symphony ${roman} view ${i + 1}`} onClick={() => setView(i)} />)}</span>
      </div>
      <div className="decorative-card-copy"><h3>Symphony {roman}</h3><p>Pendant Light</p><div className="decorative-swatches" aria-label={`Symphony ${roman} finishes`}>{colours.map((colour, i) => <button key={colour} type="button" className={finish === i ? "active" : ""} style={{ background: colour }} aria-label={`Select finish ${i + 1}`} onClick={() => setFinish(i)} />)}</div><span className="decorative-collection">Symphony Collection</span></div>
    </article>
  </div>;
}

const icons: Record<string, React.ReactNode> = {
  home: <><path d="M3.5 10.6 12 3.9l8.5 6.7"/><path d="M5.7 9.2v9.1a1.6 1.6 0 0 0 1.6 1.6h9.4a1.6 1.6 0 0 0 1.6-1.6V9.2"/></>,
  product: <><path d="M12 3.4v3.1"/><path d="M6.6 13.4a5.4 5.4 0 0 1 10.8 0Z"/><path d="M9.7 13.4a2.3 2.3 0 0 0 4.6 0"/></>,
  work: <><path d="M3.5 7.1h6.2l2 2h8.8v8.7a1.8 1.8 0 0 1-1.8 1.8H5.3a1.8 1.8 0 0 1-1.8-1.8Z"/><path d="M3.5 10h17"/></>,
  inspiration: <><path d="M9.1 16.3a5 5 0 1 1 5.8 0 1.6 1.6 0 0 0-.6 1.2v.4H9.7v-.4a1.6 1.6 0 0 0-.6-1.2Z"/><path d="M10 20.1h4"/></>,
  more: <><path d="M4.5 8.2h15"/><path d="M4.5 12h15"/><path d="M4.5 15.8h15"/></>,
};

export default function CollectionsUI() {
  const [grid, setGrid] = useState<Element | null>(null);
  const [formsExpanded, setFormsExpanded] = useState(false);
  const [mobileForms, setMobileForms] = useState(false);
  const [menu, setMenu] = useState<string | null>(null);
  const [visible, setVisible] = useState(true);
  const dock = useRef<HTMLDivElement>(null);
  const [plate, setPlate] = useState({ width: 390, height: 76 });

  useEffect(() => setGrid(document.querySelector(".s-products .decorative-grid")), []);
  useEffect(() => {
    const media = window.matchMedia("(max-width: 700px)");
    const update = () => setMobileForms(media.matches);
    update();
    media.addEventListener("change", update);
    return () => media.removeEventListener("change", update);
  }, []);
  useEffect(() => {
    const button = document.querySelector<HTMLButtonElement>(".s-products .s-view");
    if (!button) return;
    const expand = () => setFormsExpanded(true);
    button.addEventListener("click", expand);
    return () => button.removeEventListener("click", expand);
  }, []);
  useEffect(() => {
    const products = document.querySelector(".s-products");
    products?.classList.toggle("is-expanded", formsExpanded);
  }, [formsExpanded]);
  useLayoutEffect(() => {
    document.documentElement.classList.add("symphony-motion-enabled");
    const groups = [
      [".s-parameter-grid article", "motion-rise"], [".s-look", "motion-wipe"],
      [".s-tone-grid article", "motion-rise"], [".s-score-grid article", "motion-rise"],
      [".s-place", "motion-rise"], [".s-catalogue", "motion-rise"], [".s-related a", "motion-rise"],
    ];
    const targets: Element[] = [];
    groups.forEach(([selector, className]) => document.querySelectorAll(selector).forEach((node, index) => {
      node.classList.add(className); (node as HTMLElement).style.setProperty("--motion-delay", `${Math.min(index, 6) * 60}ms`); targets.push(node);
    }));
    const observer = new IntersectionObserver(entries => entries.forEach(entry => {
      if (entry.isIntersecting) { entry.target.classList.add("is-revealed"); observer.unobserve(entry.target); }
    }), { rootMargin: "0px 0px -100px", threshold: 0.01 });
    targets.forEach(target => observer.observe(target));
    return () => { observer.disconnect(); document.documentElement.classList.remove("symphony-motion-enabled"); };
  }, []);
  useLayoutEffect(() => {
    if (!dock.current) return;
    const measure = () => setPlate({ width: Math.round(dock.current!.getBoundingClientRect().width), height: Math.round(dock.current!.getBoundingClientRect().height) });
    measure(); const observer = new ResizeObserver(measure); observer.observe(dock.current); return () => observer.disconnect();
  }, []);
  useEffect(() => {
    let previous = window.scrollY;
    const onScroll = () => { const next = window.scrollY; setVisible(next < previous || next < 40); previous = next; };
    window.addEventListener("scroll", onScroll, { passive: true }); return () => window.removeEventListener("scroll", onScroll);
  }, []);
  useEffect(() => { document.body.style.overflowY = menu ? "hidden" : "auto"; return () => { document.body.style.overflowY = "auto"; }; }, [menu]);
  useEffect(() => { const onKey = (event: KeyboardEvent) => event.key === "Escape" && setMenu(null); window.addEventListener("keydown", onKey); return () => window.removeEventListener("keydown", onKey); }, []);
  useEffect(() => {
    const onClick = (event: MouseEvent) => {
      const button = (event.target as Element).closest(".s-carousel-arrow");
      if (!button) return;
      const track = button.parentElement?.querySelector<HTMLElement>(".s-scroll");
      const card = track?.querySelector<HTMLElement>("article");
      if (!track || !card) return;
      const gap = Number.parseFloat(getComputedStyle(track).gap) || 12;
      const direction = button.classList.contains("s-carousel-next") ? 1 : -1;
      track.scrollBy({ left: direction * (card.getBoundingClientRect().width + gap), behavior: window.matchMedia("(prefers-reduced-motion: reduce)").matches ? "auto" : "smooth" });
    };
    document.addEventListener("click", onClick);
    return () => document.removeEventListener("click", onClick);
  }, []);
  useEffect(() => {
    const selector = ".s-parameter-grid article, .s-look, .s-place";
    const activate = (card: Element) => {
      document.querySelectorAll(selector).forEach(item => item.classList.toggle("is-active", item === card && !card.classList.contains("is-active")));
    };
    const onClick = (event: MouseEvent) => {
      const card = (event.target as Element).closest(selector);
      if (card) activate(card); else document.querySelectorAll(selector).forEach(item => item.classList.remove("is-active"));
    };
    const onKey = (event: KeyboardEvent) => {
      const card = (event.target as Element).closest(selector);
      if (card && (event.key === "Enter" || event.key === " ")) { event.preventDefault(); activate(card); }
      if (event.key === "Escape") document.querySelectorAll(selector).forEach(item => item.classList.remove("is-active"));
    };
    document.addEventListener("click", onClick);
    document.addEventListener("keydown", onKey);
    return () => { document.removeEventListener("click", onClick); document.removeEventListener("keydown", onKey); };
  }, []);

  const tabs = [["home", "Home"], ["product", "Products"], ["work", "Our Work"], ["inspiration", "Inspiration"], ["more", "More"]];
  const d = `M6 0H${plate.width - 6}Q${plate.width} 0 ${plate.width} 6V${plate.height - 6}Q${plate.width} ${plate.height} ${plate.width - 6} ${plate.height}H6Q0 ${plate.height} 0 ${plate.height - 6}V6Q0 0 6 0Z`;
  return <>
    {grid && createPortal(forms.slice(0, formsExpanded ? 10 : mobileForms ? 4 : 8).map((roman, index) => <ProductCard key={roman} roman={roman} index={index} />), grid)}
    <nav className={`halo-nav-wrap ${visible || menu ? "halo-visible" : "halo-hidden"}`} aria-label="Mobile sections"><div ref={dock} className="halo-nav is-ready is-idle"><span className="halo-nav-shadow" aria-hidden="true"/><svg className="halo-nav-skin" aria-hidden="true" focusable="false" preserveAspectRatio="none" viewBox={`0 0 ${plate.width} ${plate.height}`}><defs><linearGradient id="internalHaloPlateGradient" x1="0" y1="0" x2="0" y2="1"><stop className="halo-plate-highlight" offset="0"/><stop className="halo-plate-shadow" offset="1"/></linearGradient><linearGradient id="internalHaloRimGradient" x1="0" y1="0" x2="0" y2="1"><stop className="halo-rim-strong" offset="0"/><stop className="halo-rim-soft" offset="1"/></linearGradient></defs><path className="halo-nav-plate" d={d}/></svg><span className="halo-orb" aria-hidden="true"/><div className="halo-tabs" role="tablist" aria-label="Page sections">{tabs.map(([key, label], index) => <button key={key} className="halo-tab" role="tab" type="button" id={`halo-tab-${key}`} aria-selected={menu === key} tabIndex={index === 0 ? 0 : -1} onClick={() => key === "home" ? window.location.assign("/home") : setMenu(current => current === key ? null : key)}><svg className="halo-icon" viewBox="0 0 24 24" aria-hidden="true">{icons[key]}</svg><span className="halo-label">{label}</span></button>)}</div></div></nav>
    <button className={`menu-backdrop ${menu ? "open" : ""}`} aria-label="Close menu" onClick={() => setMenu(null)}/>
    <div className={`msheet ${menu ? "open" : ""}`} role="dialog" aria-modal="true" aria-label="Mobile menu"><button className="menu-close" aria-label="Close menu" onClick={() => setMenu(null)}><span aria-hidden="true">×</span></button><div className="m-dash"/><div className="m-title">{menu ? menus[menu]?.title : ""}</div><div className="mobile-menu-list">{menu && <div className="mobile-menu-group">{menus[menu]?.links.map(([label, href]) => <a key={label} href={href}>{label}<span>→</span></a>)}</div>}</div></div>
  </>;
}
