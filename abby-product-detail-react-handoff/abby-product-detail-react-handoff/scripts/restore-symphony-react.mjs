import fs from "node:fs";

const file = new URL("../public/assets/page-BQ8ze6KM.js", import.meta.url);
let source = fs.readFileSync(file, "utf8");

const replacements = [
  ["Pc=[[`Small`,`350`],[`Medium`,`450`],[`Large`,`560`]]", "Pc=[[`One Size`,`250`]]"],
  ["Fc=[`/images/symphony-iv-exact/thumb-2.png`,`/images/symphony-iv-exact/thumb-3.png`,`/images/symphony-iv-exact/thumb-4.png`,`/images/symphony-iv-exact/thumb-5.png`,`/images/symphony-iv-exact/thumb-6.png`]", "Fc=[`/images/symphony-iv-figma-live/thumb-2.png`,`/images/symphony-iv-figma-live/thumb-3.png`,`/images/symphony-iv-figma-live/thumb-4.png`,`/images/symphony-iv-figma-live/thumb-5.png`,`/images/symphony-iv-figma-live/thumb-6.png`]"],
  ["Ic=[[`Black + White`,`#050505`,`#f4f4f1`,`BLK-WHT`,`/images/symphony-iv-exact/hero.png`],[`Black + Gold`,`#050505`,`#efb45f`,`BLK-GLD`,`/images/decorative/symphonyiv-on.png`],[`Gold + White`,`#efb45f`,`#f4f4f1`,`GLD-WHT`,`/images/decorative/symphonyiv-gold-white.png`],[`Black`,`#050505`,`#050505`,`BLK`,`/images/decorative/symphonyiv-black-black.png`]]", "Ic=[[`Black + White`,`#050505`,`#f4f4f1`,`BLK-WHT`,`/images/symphony-iv-figma-live/hero.png`],[`Black + Gold`,`#050505`,`#efb45f`,`BLK-GLD`,`/images/decorative/symphonyiv-on.png`],[`Gold + White`,`#efb45f`,`#f4f4f1`,`GLD-WHT`,`/images/decorative/symphonyiv-gold-white.png`],[`Black`,`#050505`,`#050505`,`BLK`,`/images/decorative/symphonyiv-black-black.png`]]"],
  ["Mc=[[`Symphony V`,`/images/symphony-iv-figma/family-v.png`],[`Symphony VI`,`/images/symphony-iv-figma/family-vi.png`],[`Symphony VII`,`/images/symphony-iv-figma/family-vii.png`]]", "Mc=[[`Symphony V`,`/images/symphony-iv-figma-live/family-v.png`],[`Symphony VI`,`/images/symphony-iv-figma-live/family-vi.png`],[`Symphony VII`,`/images/symphony-iv-figma-live/family-vii.png`]]"],
  ["children:e&&r<4?`Symphony ${[`I`,`II`,`III`,`V`][r]}`:t", "children:t"],
  ["children:[n,e?` Collections`:``]", "children:[n,e?` Collection`:``]"],
  ["A decorative lighting collection where colour, texture and composition come together in beautiful central themes.", "A decorative lighting collection where colour, material and composition come together in beautifully curated harmony."],
  ["A family of expressive pendants built around colour, rhythm and carefully balanced geometry. Symphony brings confident combinations and crafted metallic detail into contemporary interiors.", "Symphony begins with a choice, not a fixture. Mix forms, colours, and suspension options to create compositions that adapt to every space. The system provides the language. The composition is yours."],
  ["rows:e?[[`Category`,`Pendant Light`],[`Primary Material`,`Aluminium`],[`Primary Finish`,`Black, White, Copper, Black + White, Black + Gold\\n- Format: Top colour + Bottom colour\\n- Inner Body Finish will be white by default\\n- Canopy Finish will be white for any fixture having white in the body finish, black canopy for all colours other than white`],[`Secondary Material`,`Aluminium`],[`Secondary Finish`,`White, Black`],[`Wire Colour`,`White\\n- Wire colour will be white for any fixture having white in the body finish, black for all colours other than white`]]", "rows:e?[[`Category`,`Pendant Light`],[`Primary Material`,`Aluminium`],[`Primary Finish`,`Black, White, Copper, Black + White, Black + Gold\\n- Format: Top colour + Bottom colour\\n- Inner Body Finish will be white by default\\n- Canopy Finish will be white for any fixture having white in the body finish, black canopy for all colours other than white`],[`Secondary Material`,`Aluminium`],[`Secondary Finish`,`White, Black`],[`Wire Colour`,`White\\n- Wire colour will be white for any fixture having white in the body finish, black for all colours other than white`],[`Size`,`Onesize`],[`Height`,`250 mm`],[`Diameter`,`Ø 250 mm`],[`Canopy`,`Ø 95 mm, H 45 mm`],[`Wire Length`,`1.5 m`],[`Light source`,`E27 Compatible (Bulb not included)`],[`Input Voltage`,`220V-240V`],[`Control`,`ON-OFF`]]"],
  ["rows:e?[[`Diameter`,`Ø 250 mm`],[`Height`,`H 250 mm`],[`Canopy`,`Ø 95mm, H 45mm`],[`Cable`,`Standard 1.5 m · Custom lengths available`]]", "rows:e?[[`Size`,`Onesize`],[`Height`,`250 mm`],[`Diameter`,`Ø 250 mm`],[`Canopy`,`Ø 95 mm, H 45 mm`],[`Wire Length`,`1.5 m`]]"],
  ["title:`Light source & specifications`,rows:[[`Light source`,`E27 · LED compatible`],[`Colour temperature`,`2700–3000K warm white`],[`Input`,`220–240V AC · 50/60 Hz`]]", "title:`Light source`,rows:[[`Light source`,`E27 Compatible (Bulb not included)`],[`Input Voltage`,`220V-240V`],[`Control`,`ON-OFF`]]"],
  ["Need bulk orders or custom solutions? ", "Don’t want to wait  "],
  ["children:`Talk to us.`", "children:`Talk to us immediately`"],
  ["export{Rc as default};", "export{Rc as default,Bc as generateSymphonyDatasheet};"],
];

for (const [before, after] of replacements) {
  if (!source.includes(before) && !source.includes(after)) {
    throw new Error(`Expected bundle fragment was not found: ${before.slice(0, 80)}`);
  }
  source = source.replaceAll(before, after);
}

fs.writeFileSync(file, source);
