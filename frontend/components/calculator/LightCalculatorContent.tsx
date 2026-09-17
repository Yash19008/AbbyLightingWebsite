"use client";

import React, { useState, useMemo, useEffect, useRef } from "react";

const ROOM_AMBIENCE_DEFAULTS: Record<string, string> = {
  "200_bedroom": "0.9|2700",
  "250_living": "1|3000",
  "350_kitchen": "1.1|4000",
  "250_dining": "1|3000",
  "500_study": "1.1|4000",
  "200_bathroom": "1.1|4000",
  "100_corridor": "1|3000",
};

const ROOM_TYPES = [
  { value: "200_bedroom", label: "Bedroom" },
  { value: "250_living", label: "Living Room" },
  { value: "350_kitchen", label: "Kitchen" },
  { value: "250_dining", label: "Dining Room" },
  { value: "500_study", label: "Study or Home Office" },
  { value: "200_bathroom", label: "Bathroom" },
  { value: "100_corridor", label: "Corridor" },
];

const UNIT_OPTIONS = [
  { value: "ft" as const, label: "Feet (ft)" },
  { value: "m" as const, label: "Metres (m)" },
];

const AMBIENCE_OPTIONS = [
  { value: "0.9|2700", label: "Warm & Relaxed" },
  { value: "1|3000", label: "Balanced & Comfortable" },
  { value: "1.1|4000", label: "Bright & Functional" },
];

const CEILING_OPTIONS = [
  { value: 1, label: "Flat" },
  { value: 1.02, label: "False Ceiling" },
  { value: 1.05, label: "Cove Ceiling" },
  { value: 1.15, label: "Double Height" },
  { value: 1.08, label: "Sloped" },
];

const WALL_COLOUR_OPTIONS = [
  { value: 1, label: "White or Light" },
  { value: 1.05, label: "Medium Tone" },
  { value: 1.12, label: "Dark Tone" },
];

const DAYLIGHT_OPTIONS = [
  { value: 0.95, label: "High Daylight" },
  { value: 1, label: "Moderate Daylight" },
  { value: 1.08, label: "Limited Daylight" },
];

const ROLE_OPTIONS = [
  { value: "Home Owner", label: "Home Owner" },
  { value: "Interior Designer", label: "Interior Designer" },
  { value: "Architect", label: "Architect" },
  { value: "Lighting Consultant", label: "Lighting Consultant" },
  { value: "Contractor", label: "Contractor" },
  { value: "Other", label: "Other" },
];

interface LegendItem {
  title: string;
  desc: string;
}

const LEGEND_ITEMS: LegendItem[] = [
  {
    title: "Lux",
    desc: "Target illumination on the working plane measured in lumens per square metre (lx).",
  },
  {
    title: "Lumens",
    desc: "Total light emitted by a luminaire. Fixture quantities are based on this value.",
  },
  {
    title: "Maintenance Factor",
    desc: "Compensates for lumen depreciation due to ageing, dust and maintenance. Residential default: 0.80.",
  },
  {
    title: "Utilization Factor",
    desc: "Represents how efficiently emitted light reaches useful surfaces.",
  },
  {
    title: "Colour Temperature",
    desc: "The calculator recommends a suitable colour temperature based on ambience. CCT is not used as a primary engineering multiplier because lumen output is already specified by the fixture manufacturer.",
  },
];

interface CustomSelectOption<T> {
  value: T;
  label: string;
}

interface CustomSelectProps<T> {
  id: string;
  labelId?: string;
  value: T;
  options: CustomSelectOption<T>[];
  onChange: (value: T) => void;
  placeholder?: string;
}

function CustomSelect<T extends string | number>({
  id,
  labelId,
  value,
  options,
  onChange,
  placeholder,
}: CustomSelectProps<T>) {
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
  const displayText = selectedOption ? selectedOption.label : placeholder || "Select option";

  return (
    <div className={`custom-select-wrap ${isOpen ? "dropdown-active" : ""}`} ref={containerRef}>
      <button
        id={id}
        type="button"
        className={`custom-select-trigger ${isOpen ? "is-open" : ""}`}
        aria-haspopup="listbox"
        aria-expanded={isOpen}
        aria-labelledby={labelId}
        onClick={() => setIsOpen((prev) => !prev)}
      >
        <span>{displayText}</span>
        <svg
          className={`custom-select-arrow ${isOpen ? "is-open" : ""}`}
          width="12"
          height="8"
          viewBox="0 0 12 8"
          fill="none"
        >
          <path
            d="M1.5 2L6 6.5L10.5 2"
            stroke="#333333"
            strokeWidth="1.8"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      </button>

      {isOpen && (
        <div className="custom-select-menu" role="listbox" aria-labelledby={labelId}>
          {options.map((opt) => (
            <button
              key={String(opt.value)}
              type="button"
              role="option"
              aria-selected={opt.value === value}
              className={`custom-select-option ${opt.value === value ? "is-selected" : ""}`}
              onClick={() => {
                onChange(opt.value);
                setIsOpen(false);
              }}
            >
              {opt.label}
            </button>
          ))}
        </div>
      )}
    </div>
  );
}

export default function LightCalculatorContent() {
  // Calculator Form State
  const [units, setUnits] = useState<"ft" | "m">("ft");
  const [roomTypeKey, setRoomTypeKey] = useState<string>("250_living");
  const [ambience, setAmbience] = useState<string>("1|3000");
  const [length, setLength] = useState<number>(15);
  const [width, setWidth] = useState<number>(12);
  const [ceilingHeight, setCeilingHeight] = useState<number>(10);
  const [ceilingType, setCeilingType] = useState<number>(1);
  const [wallColour, setWallColour] = useState<number>(1);
  const [daylight, setDaylight] = useState<number>(1);
  const [maintenanceFactor, setMaintenanceFactor] = useState<number>(0.8);
  const [utilizationFactor, setUtilizationFactor] = useState<number>(0.65);
  const [fixtureLumens, setFixtureLumens] = useState<number>(850);
  const [validationError, setValidationError] = useState<string>("");

  // Tooltips & Accordions State
  const [activeHelp, setActiveHelp] = useState<string | null>(null);
  const [openLegends, setOpenLegends] = useState<Record<number, boolean>>({});

  // Contact Modal State
  const [contactModalOpen, setContactModalOpen] = useState(false);
  const [contactSubmitted, setContactSubmitted] = useState(false);
  const [contactForm, setContactForm] = useState({
    name: "",
    phone: "",
    email: "",
    city: "",
    company: "",
    role: "",
    message: "",
    captcha: false,
  });

  const calculatorRef = useRef<HTMLDivElement>(null);

  // Close tooltips on outside click
  useEffect(() => {
    function handleClickOutside(e: MouseEvent) {
      if (!(e.target as HTMLElement).closest(".field-label-row")) {
        setActiveHelp(null);
      }
    }
    document.addEventListener("click", handleClickOutside);
    return () => document.removeEventListener("click", handleClickOutside);
  }, []);

  // Escape key for modal & tooltips
  useEffect(() => {
    function handleKeyDown(e: KeyboardEvent) {
      if (e.key === "Escape") {
        if (contactModalOpen) {
          setContactModalOpen(false);
        } else {
          setActiveHelp(null);
        }
      }
    }
    document.addEventListener("keydown", handleKeyDown);
    return () => document.removeEventListener("keydown", handleKeyDown);
  }, [contactModalOpen]);

  // Lock scroll on modal open
  useEffect(() => {
    if (contactModalOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [contactModalOpen]);

  // Room type selection handler with auto-ambience update
  const handleRoomTypeSelect = (key: string) => {
    setRoomTypeKey(key);
    if (ROOM_AMBIENCE_DEFAULTS[key]) {
      setAmbience(ROOM_AMBIENCE_DEFAULTS[key]);
    }
  };

  // Calculation computation
  const calculationResult = useMemo(() => {
    if (
      !length ||
      !width ||
      !ceilingHeight ||
      !maintenanceFactor ||
      !utilizationFactor ||
      !fixtureLumens ||
      length <= 0 ||
      width <= 0 ||
      ceilingHeight <= 0 ||
      maintenanceFactor <= 0 ||
      utilizationFactor <= 0 ||
      fixtureLumens <= 0
    ) {
      return null;
    }

    const isFeet = units === "ft";
    const areaFt = isFeet ? length * width : length * width * 10.7639;
    const areaM = isFeet ? length * width * 0.092903 : length * width;

    const [ambienceMultiplierStr, cct] = ambience.split("|");
    const ambienceMultiplier = parseFloat(ambienceMultiplierStr) || 1;

    // Room base lux lookup
    const roomLuxMap: Record<string, number> = {
      "200_bedroom": 200,
      "250_living": 250,
      "350_kitchen": 350,
      "250_dining": 250,
      "500_study": 500,
      "200_bathroom": 200,
      "100_corridor": 100,
    };
    const baseLux = roomLuxMap[roomTypeKey] || 250;
    const targetLux = baseLux * ambienceMultiplier;

    let totalLumens = (areaM * targetLux) / (maintenanceFactor * utilizationFactor);
    totalLumens *= ceilingType * wallColour * daylight;

    const fixtures = Math.max(1, Math.ceil(totalLumens / fixtureLumens));
    const spacing = Math.sqrt(areaFt / fixtures);

    let beam = "36°";
    if (ceilingHeight > (isFeet ? 11 : 3.4)) beam = "24°";
    if (ceilingHeight < (isFeet ? 9 : 2.7)) beam = "60°";

    return {
      areaFt,
      areaM,
      targetLux,
      totalLumens,
      fixtures,
      spacing,
      beam,
      cct,
      fixtureLumens,
    };
  }, [
    units,
    roomTypeKey,
    ambience,
    length,
    width,
    ceilingHeight,
    ceilingType,
    wallColour,
    daylight,
    maintenanceFactor,
    utilizationFactor,
    fixtureLumens,
  ]);

  const handleCalculateSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!calculationResult) {
      setValidationError("Please enter valid positive values within the indicated ranges.");
    } else {
      setValidationError("");
    }
  };

  const toggleLegend = (index: number) => {
    setOpenLegends((prev) => ({ ...prev, [index]: !prev[index] }));
  };

  const handleContactChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>
  ) => {
    const { name, value, type } = e.target;
    if (type === "checkbox") {
      const checked = (e.target as HTMLInputElement).checked;
      setContactForm((prev) => ({ ...prev, [name]: checked }));
    } else {
      setContactForm((prev) => ({ ...prev, [name]: value }));
    }
  };

  const handleContactSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!contactForm.captcha) return;
    setContactSubmitted(true);
  };

  return (
    <div className="calculator-page" ref={calculatorRef}>
      {/* Hero Section */}
      <section className="calculator-hero" aria-labelledby="calculator-title">
        <div className="calculator-hero-copy">
          <h1 id="calculator-title">
            Professional Ambient <em>Lighting Calculator</em>
          </h1>
        </div>
      </section>

      <div className="calculator-shell">
        <section aria-labelledby="inputs-title">
          <h2 id="inputs-title">Project Inputs</h2>
          <form id="calculator-form" onSubmit={handleCalculateSubmit} noValidate>
            <div className="input-grid">
              {/* Measurement Unit */}
              <div className="field">
                <div className={`field-label-row ${activeHelp === "units" ? "help-open" : ""}`}>
                  <label id="units-label" htmlFor="units-trigger">Measurement Unit</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About measurement unit"
                    aria-expanded={activeHelp === "units"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) => (prev === "units" ? null : "units"));
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Choose whether you want to enter the room dimensions in feet or metres.
                  </span>
                </div>
                <CustomSelect
                  id="units-trigger"
                  labelId="units-label"
                  value={units}
                  options={UNIT_OPTIONS}
                  onChange={(val) => setUnits(val as "ft" | "m")}
                />
              </div>

              {/* Room or Space Type */}
              <div className="field">
                <div className={`field-label-row ${activeHelp === "room-type" ? "help-open" : ""}`}>
                  <label id="room-type-label" htmlFor="room-type-trigger">Room or Space Type</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About room or space type"
                    aria-expanded={activeHelp === "room-type"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) => (prev === "room-type" ? null : "room-type"));
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Used to determine the recommended illumination level for the space.
                  </span>
                </div>
                <CustomSelect
                  id="room-type-trigger"
                  labelId="room-type-label"
                  value={roomTypeKey}
                  options={ROOM_TYPES}
                  onChange={handleRoomTypeSelect}
                />
              </div>

              {/* Lighting Ambience */}
              <div className="field">
                <div className={`field-label-row ${activeHelp === "ambience" ? "help-open" : ""}`}>
                  <label id="ambience-label" htmlFor="ambience-trigger">Lighting Ambience</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About lighting ambience"
                    aria-expanded={activeHelp === "ambience"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) => (prev === "ambience" ? null : "ambience"));
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Choose the overall brightness and mood you want the room to have.
                  </span>
                </div>
                <CustomSelect
                  id="ambience-trigger"
                  labelId="ambience-label"
                  value={ambience}
                  options={AMBIENCE_OPTIONS}
                  onChange={setAmbience}
                />
              </div>

              {/* Room Length */}
              <div className="field">
                <div className={`field-label-row ${activeHelp === "length" ? "help-open" : ""}`}>
                  <label htmlFor="length">Room Length</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About room length"
                    aria-expanded={activeHelp === "length"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) => (prev === "length" ? null : "length"));
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Enter the internal length of the room in your selected measurement unit.
                  </span>
                </div>
                <input
                  id="length"
                  type="number"
                  value={length || ""}
                  min="0.1"
                  step="0.1"
                  inputMode="decimal"
                  onChange={(e) => setLength(parseFloat(e.target.value) || 0)}
                />
              </div>

              {/* Room Width */}
              <div className="field">
                <div className={`field-label-row ${activeHelp === "width" ? "help-open" : ""}`}>
                  <label htmlFor="width">Room Width</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About room width"
                    aria-expanded={activeHelp === "width"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) => (prev === "width" ? null : "width"));
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Enter the internal width of the room in your selected measurement unit.
                  </span>
                </div>
                <input
                  id="width"
                  type="number"
                  value={width || ""}
                  min="0.1"
                  step="0.1"
                  inputMode="decimal"
                  onChange={(e) => setWidth(parseFloat(e.target.value) || 0)}
                />
              </div>

              {/* Ceiling Height */}
              <div className="field">
                <div
                  className={`field-label-row ${activeHelp === "ceiling-height" ? "help-open" : ""}`}
                >
                  <label htmlFor="ceiling-height">Ceiling Height</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About ceiling height"
                    aria-expanded={activeHelp === "ceiling-height"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) =>
                        prev === "ceiling-height" ? null : "ceiling-height"
                      );
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Measure from the finished floor to the ceiling in your selected measurement unit.
                  </span>
                </div>
                <input
                  id="ceiling-height"
                  type="number"
                  value={ceilingHeight || ""}
                  min="0.1"
                  step="0.1"
                  inputMode="decimal"
                  onChange={(e) => setCeilingHeight(parseFloat(e.target.value) || 0)}
                />
              </div>

              {/* Ceiling Design */}
              <div className="field">
                <div
                  className={`field-label-row ${activeHelp === "ceiling-type" ? "help-open" : ""}`}
                >
                  <label id="ceiling-type-label" htmlFor="ceiling-type-trigger">Ceiling Design</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About ceiling design"
                    aria-expanded={activeHelp === "ceiling-type"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) => (prev === "ceiling-type" ? null : "ceiling-type"));
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Select the option that most closely matches the room&apos;s ceiling construction.
                  </span>
                </div>
                <CustomSelect
                  id="ceiling-type-trigger"
                  labelId="ceiling-type-label"
                  value={ceilingType}
                  options={CEILING_OPTIONS}
                  onChange={setCeilingType}
                />
              </div>

              {/* Wall Colour Tone */}
              <div className="field">
                <div
                  className={`field-label-row ${activeHelp === "wall-colour" ? "help-open" : ""}`}
                >
                  <label id="wall-colour-label" htmlFor="wall-colour-trigger">Wall Colour Tone</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About wall colour tone"
                    aria-expanded={activeHelp === "wall-colour"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) => (prev === "wall-colour" ? null : "wall-colour"));
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Lighter surfaces reflect more light, while darker surfaces absorb more light.
                  </span>
                </div>
                <CustomSelect
                  id="wall-colour-trigger"
                  labelId="wall-colour-label"
                  value={wallColour}
                  options={WALL_COLOUR_OPTIONS}
                  onChange={setWallColour}
                />
              </div>

              {/* Daylight Availability */}
              <div className="field">
                <div className={`field-label-row ${activeHelp === "daylight" ? "help-open" : ""}`}>
                  <label id="daylight-label" htmlFor="daylight-trigger">Daylight Availability</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About daylight availability"
                    aria-expanded={activeHelp === "daylight"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) => (prev === "daylight" ? null : "daylight"));
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Consider the amount of daylight normally available when the room is in use.
                  </span>
                </div>
                <CustomSelect
                  id="daylight-trigger"
                  labelId="daylight-label"
                  value={daylight}
                  options={DAYLIGHT_OPTIONS}
                  onChange={setDaylight}
                />
              </div>

              {/* Maintenance Factor */}
              <div className="field">
                <div
                  className={`field-label-row ${activeHelp === "maintenance-factor" ? "help-open" : ""}`}
                >
                  <label htmlFor="maintenance-factor">Maintenance Factor</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About maintenance factor"
                    aria-expanded={activeHelp === "maintenance-factor"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) =>
                        prev === "maintenance-factor" ? null : "maintenance-factor"
                      );
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Accounts for light reduction caused by fixture ageing, dust and maintenance conditions. Default value: 0.80.
                  </span>
                </div>
                <input
                  id="maintenance-factor"
                  type="number"
                  value={maintenanceFactor || ""}
                  min="0.01"
                  max="1"
                  step="0.01"
                  inputMode="decimal"
                  onChange={(e) => setMaintenanceFactor(parseFloat(e.target.value) || 0)}
                />
              </div>

              {/* Utilization Factor */}
              <div className="field">
                <div
                  className={`field-label-row ${activeHelp === "utilization-factor" ? "help-open" : ""}`}
                >
                  <label htmlFor="utilization-factor">Light Utilization Factor</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About light utilization factor"
                    aria-expanded={activeHelp === "utilization-factor"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) =>
                        prev === "utilization-factor" ? null : "utilization-factor"
                      );
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Estimates how much of the fixture&apos;s emitted light effectively reaches useful room surfaces. Default value: 0.65.
                  </span>
                </div>
                <input
                  id="utilization-factor"
                  type="number"
                  value={utilizationFactor || ""}
                  min="0.01"
                  max="1"
                  step="0.01"
                  inputMode="decimal"
                  onChange={(e) => setUtilizationFactor(parseFloat(e.target.value) || 0)}
                />
              </div>

              {/* Lumens per Fixture */}
              <div className="field">
                <div
                  className={`field-label-row ${activeHelp === "fixture-lumens" ? "help-open" : ""}`}
                >
                  <label htmlFor="fixture-lumens">Lumens per Fixture</label>
                  <button
                    className="info-button"
                    type="button"
                    aria-label="About lumens per fixture"
                    aria-expanded={activeHelp === "fixture-lumens"}
                    onClick={(e) => {
                      e.stopPropagation();
                      setActiveHelp((prev) =>
                        prev === "fixture-lumens" ? null : "fixture-lumens"
                      );
                    }}
                  >
                    i
                  </button>
                  <span className="field-help" role="tooltip">
                    Enter the lumen output of one proposed light fixture, as shown on its specification.
                  </span>
                </div>
                <input
                  id="fixture-lumens"
                  type="number"
                  value={fixtureLumens || ""}
                  min="1"
                  step="1"
                  inputMode="numeric"
                  onChange={(e) => setFixtureLumens(parseFloat(e.target.value) || 0)}
                />
              </div>
            </div>

            <div className="calculator-action">
              <button className="calculate-button" type="submit">
                Calculate Lighting Requirement
              </button>
              {validationError && (
                <p id="validation-message" className="validation-message" role="alert">
                  {validationError}
                </p>
              )}
            </div>
          </form>

          {/* Results & Recommendation */}
          <div className="result-panels">
            <section className="calculator-result" id="calculator-result" aria-live="polite">
              <p className="fixture-callout">
                <span className="fixture-callout-desktop">
                  Recommended
                  <br />
                  Fixture Quantity:
                </span>
                <span className="fixture-callout-mobile">
                  RECOMMENDED FIXTURE COUNT
                </span>
                <output id="fixture-count">
                  {calculationResult
                    ? `${calculationResult.fixtures} × ${calculationResult.fixtureLumens.toFixed(0)} lm`
                    : "—"}
                </output>
              </p>

              <h3 className="result-subtitle">
                <span className="result-subtitle-desktop">Calculation Summary</span>
                <span className="result-subtitle-mobile">Based on</span>
              </h3>
              <div className="result-grid" id="result-grid">
                {calculationResult && (
                  <>
                    <div className="result-row">
                      <strong>Total Room Area:</strong>
                      <span>
                        {calculationResult.areaFt.toFixed(1)} sq ft | {calculationResult.areaM.toFixed(2)} sq m
                      </span>
                    </div>
                    <div className="result-row">
                      <strong>Recommended Light Level:</strong>
                      <span>{calculationResult.targetLux.toFixed(0)} lx</span>
                    </div>
                    <div className="result-row">
                      <strong>Total Ambient Lumens Required:</strong>
                      <span>{calculationResult.totalLumens.toFixed(0)} lm</span>
                    </div>
                    <div className="result-row">
                      <strong>Suggested Fixture Spacing:</strong>
                      <span>{calculationResult.spacing.toFixed(1)} ft on centre (starting point)</span>
                    </div>
                    <div className="result-row">
                      <strong>Suggested Beam Angle:</strong>
                      <span>{calculationResult.beam}</span>
                    </div>
                    <div className="result-row">
                      <strong>Suggested Colour Temperature:</strong>
                      <span>{calculationResult.cct} K</span>
                    </div>
                    <div className="result-row">
                      <strong>Minimum Recommended CRI:</strong>
                      <span>≥90</span>
                    </div>
                  </>
                )}
              </div>

              <h3 className="result-notes-title">Important Note:</h3>
              <p className="result-note">
                This tool estimates ambient lighting only. Decorative pendants, cove lighting, wall washing, bedside lamps, artwork lighting, beam angles, glare control, CRI, furniture layouts and lighting scenes require professional design. Always validate the final fixture layout, beam angles, spacing, glare control and layered lighting before specification.
              </p>
            </section>

            <aside className="recommendation-cta" aria-labelledby="recommendation-title">
              <img
                src="/images/light-calculator-cta.png"
                alt="Black architectural spotlight casting a focused beam"
              />
              <div className="recommendation-copy">
                <h3 id="recommendation-title">
                  Find the right <em>Lights for your space</em>
                </h3>
                <p>Explore Abby Lighting fixtures suited to your lighting requirements.</p>
                <button
                  className="recommendation-button"
                  type="button"
                  aria-haspopup="dialog"
                  onClick={() => {
                    setContactSubmitted(false);
                    setContactModalOpen(true);
                  }}
                >
                  Get in Touch
                </button>
              </div>
            </aside>
          </div>
        </section>

        {/* Legend Section */}
        <section className="legend" aria-labelledby="legend-title">
          <h2 id="legend-title">Legend &amp; Design Notes</h2>
          <div className="legend-list">
            {LEGEND_ITEMS.map((item, idx) => {
              const isOpen = Boolean(openLegends[idx]);
              return (
                <div key={item.title} className={`legend-item ${isOpen ? "open" : ""}`}>
                  <button
                    type="button"
                    aria-expanded={isOpen}
                    onClick={() => toggleLegend(idx)}
                  >
                    {item.title}
                  </button>
                  <div className="legend-panel">
                    <div>
                      <p>{item.desc}</p>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </section>
      </div>

      {/* Contact Modal */}
      {contactModalOpen && (
        <div className="calculator-contact-modal" id="contact-modal" aria-hidden="false">
          <button
            className="calculator-contact-backdrop"
            type="button"
            tabIndex={-1}
            aria-label="Close contact form"
            onClick={() => setContactModalOpen(false)}
          />
          <section
            className="calculator-contact-dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="contact-dialog-title"
          >
            <button
              className="calculator-contact-close"
              type="button"
              aria-label="Close contact form"
              onClick={() => setContactModalOpen(false)}
            >
              ×
            </button>
            <h2 id="contact-dialog-title">Let&apos;s Discuss Your Lighting Requirements</h2>
            <p>Tell us about your project, and our team will get back to you shortly.</p>

            <form className="calculator-contact-form" onSubmit={handleContactSubmit}>
              <div className={`calculator-contact-field full ${contactForm.name ? "filled" : ""}`}>
                <label htmlFor="contact-name">Name*</label>
                <input
                  id="contact-name"
                  name="name"
                  value={contactForm.name}
                  onChange={handleContactChange}
                  autoComplete="name"
                  required
                />
              </div>

              <div className="calculator-contact-field">
                <div className={`calculator-contact-phone-wrap ${contactForm.phone ? "filled" : ""}`}>
                  <div className="calculator-contact-phone-prefix" aria-hidden="true">
                    <span>🇮🇳</span>
                    <span>+91</span>
                  </div>
                  <label htmlFor="contact-phone">Mobile number*</label>
                  <input
                    id="contact-phone"
                    name="phone"
                    type="tel"
                    value={contactForm.phone}
                    onChange={handleContactChange}
                    autoComplete="tel"
                    required
                  />
                </div>
              </div>

              <div className={`calculator-contact-field ${contactForm.email ? "filled" : ""}`}>
                <label htmlFor="contact-email">Email id*</label>
                <input
                  id="contact-email"
                  name="email"
                  type="email"
                  value={contactForm.email}
                  onChange={handleContactChange}
                  autoComplete="email"
                  required
                />
              </div>

              <div className={`calculator-contact-field ${contactForm.city ? "filled" : ""}`}>
                <label htmlFor="contact-city">City*</label>
                <input
                  id="contact-city"
                  name="city"
                  value={contactForm.city}
                  onChange={handleContactChange}
                  autoComplete="address-level2"
                  required
                />
              </div>

              <div className={`calculator-contact-field ${contactForm.company ? "filled" : ""}`}>
                <label htmlFor="contact-company">Company / Firm Name</label>
                <input
                  id="contact-company"
                  name="company"
                  value={contactForm.company}
                  onChange={handleContactChange}
                  autoComplete="organization"
                />
              </div>

              <div className={`calculator-contact-field full ${contactForm.role ? "filled" : ""}`}>
                <label id="contact-role-label" htmlFor="contact-role-trigger">I am a*</label>
                <CustomSelect
                  id="contact-role-trigger"
                  labelId="contact-role-label"
                  value={contactForm.role}
                  options={ROLE_OPTIONS}
                  onChange={(val) => setContactForm((prev) => ({ ...prev, role: String(val) }))}
                  placeholder=""
                />
              </div>

              <div className={`calculator-contact-field full ${contactForm.message ? "filled" : ""}`}>
                <label htmlFor="contact-message">Tell us more</label>
                <textarea
                  id="contact-message"
                  name="message"
                  value={contactForm.message}
                  onChange={handleContactChange}
                />
              </div>

              <label className="calculator-contact-captcha">
                <input
                  type="checkbox"
                  name="captcha"
                  checked={contactForm.captcha}
                  onChange={handleContactChange}
                  required
                />
                <span>I’m not a robot</span>
              </label>

              <button className="calculator-contact-submit" type="submit" disabled={contactSubmitted}>
                Submit form
              </button>

              <p className="calculator-contact-consent">
                By submitting this form, you agree to be contacted by <span>Abby Lighting</span> regarding your enquiry.
              </p>

              {contactSubmitted && (
                <p className="calculator-contact-status" role="status">
                  Thank you. Your enquiry has been noted.
                </p>
              )}
            </form>
          </section>
        </div>
      )}
    </div>
  );
}
