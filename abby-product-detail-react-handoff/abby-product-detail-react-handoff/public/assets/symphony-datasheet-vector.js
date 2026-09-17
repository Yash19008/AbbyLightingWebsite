const PAGE_W = 595;
const PAGE_H = 786;

const latinBytes = value => {
  const text = String(value);
  const out = new Uint8Array(text.length);
  for (let i = 0; i < text.length; i += 1) out[i] = text.charCodeAt(i) & 255;
  return out;
};

const escapePdf = value => String(value ?? '')
  .replace(/\\/g, '\\\\')
  .replace(/\(/g, '\\(')
  .replace(/\)/g, '\\)')
  .replace(/[–—]/g, '-')
  .replace(/’/g, "'")
  .replace(/Ø/g, String.fromCharCode(216));

const firstListed = value => {
  if (!value) return null;
  const firstLine = String(value).split(/\n|<br\s*\/?\s*>/i)[0];
  const first = firstLine.split(',')[0].trim();
  return first || null;
};

const normaliseKey = value => String(value || '').toLowerCase().replace(/[^a-z0-9]+/g, ' ').trim();

const readProductData = () => {
  const specs = {};
  const specRows = [];
  const specOptions = {};
  const specOptionCodes = {};
  const primarySpecBody = document.querySelector('.spec-grid>.spec-accordion:first-child .spec-body') || document;
  primarySpecBody.querySelectorAll('p,.figma-spec-row').forEach(row => {
    const key = row.querySelector('b')?.textContent?.trim();
    const valueNode = row.querySelector('span');
    if (!key || !valueNode) return;
    const clone = valueNode.cloneNode(true);
    clone.querySelectorAll('small').forEach(note => note.remove());
    clone.querySelectorAll('.spec-code').forEach(code => code.remove());
    const value = clone.textContent.replace(/\s+/g, ' ').trim();
    const note = valueNode.querySelector('small')?.textContent?.replace(/\s+/g, ' ').trim() || '';
    const displayValue = value;
    const normalKey = normaliseKey(key);
    if (value && !specs[normalKey]) {
      specs[normalKey] = value;
      specRows.push({ key, normalKey, value, displayValue, note });
      const options = valueNode.dataset.values?.split('|').map(item => item.trim()).filter(Boolean);
      if (options?.length) {
        specOptions[normalKey] = options;
        const optionNodes = [...valueNode.children].filter(node => node.tagName === 'SPAN');
        specOptionCodes[normalKey] = options.map((_, index) => optionNodes[index]?.querySelector('.spec-code')?.textContent?.trim() || null);
      } else {
        const inlineCode = valueNode.querySelector('.spec-code')?.textContent?.trim();
        if (inlineCode) specOptionCodes[normalKey] = [inlineCode];
      }
    }
  });
  const find = (...keys) => {
    for (const key of keys) {
      const exact = specs[normaliseKey(key)];
      if (exact) return exact;
      const match = Object.entries(specs).find(([entry]) => entry.includes(normaliseKey(key)));
      if (match) return match[1];
    }
    return null;
  };
  const product = document.querySelector('.product-info h1')?.textContent?.trim() || 'Product';
  const collection = document.querySelector('.product-tag')?.textContent?.split(/\s+COLLECTION/i)[0]?.trim() || '';
  const pdfTitle = collection && !product.toUpperCase().includes(collection.toUpperCase()) ? `${collection} ${product}` : product;
  const sizeButtons = [...document.querySelectorAll('.size-options button')];
  const selectedSizeButton = sizeButtons.find(button => button.classList.contains('active')) || sizeButtons[0];
  const selectedSizeIndex = Math.max(0, sizeButtons.indexOf(selectedSizeButton));
  const selectedSize = selectedSizeButton?.textContent?.trim() || find('size');
  const selectedFinish = document.querySelector('.combination-swatches button.active span')?.textContent?.trim() || '';
  const optionFor = key => specOptions[normaliseKey(key)]?.[selectedSizeIndex] || firstListed(find(key));
  const codeFor = (key, index = selectedSizeIndex) => specOptionCodes[normaliseKey(key)]?.[index] || null;
  const firstOptionFor = (...keys) => {
    for (const key of keys) {
      const options = specOptions[normaliseKey(key)];
      if (options?.[0]) return options[0];
    }
    return firstListed(find(...keys));
  };
  const stage = document.querySelector('.product-stage>img');
  return {
    product,
    pdfTitle,
    selectedSize: selectedSize || 'One Size',
    selectedSizeIndex,
    selectedFinish,
    stageImage: stage?.dataset.datasheetPrimary || stage?.src || '/images/symphony-iv-figma-live/hero.png',
    drawingImage: document.documentElement.dataset.datasheetDrawing || document.querySelector('.product-thumbs button:nth-child(2) img')?.src || '/images/symphony-iv-figma-live/thumb-2.png',
    specs,
    specRows,
    specOptions,
    specOptionCodes,
    order: {
      product: product.toUpperCase(),
      size: (codeFor('size') || firstListed(selectedSize || find('size'))?.replace(/\s+/g, ''))?.toUpperCase() || null,
      wattage: (codeFor('wattage') || optionFor('wattage'))?.toUpperCase() || null,
      cct: (codeFor('cct', 0) || codeFor('colour temperature', 0) || codeFor('color temperature', 0) || firstOptionFor('colour temperature', 'color temperature', 'cct'))?.toUpperCase() || null,
      primaryFinish: (codeFor('primary finish', 0) || firstListed(find('primary finish')))?.toUpperCase() || null,
      secondaryFinish: (codeFor('secondary finish', 0) || firstListed(find('secondary finish')))?.toUpperCase() || null,
      control: (codeFor('control', 0) || firstOptionFor('control'))?.toUpperCase() || null,
    },
  };
};

export const inspectProductDatasheet = readProductData;

const loadJpeg = async (src, options = {}) => {
  const image = new Image();
  image.crossOrigin = 'anonymous';
  await new Promise((resolve, reject) => {
    image.onload = resolve;
    image.onerror = reject;
    image.src = src;
  });
  const maxWidth = options.maxWidth || 1000;
  const scale = Math.min(1, maxWidth / image.naturalWidth);
  const width = Math.max(1, Math.round(image.naturalWidth * scale));
  const height = Math.max(1, Math.round(image.naturalHeight * scale));
  const canvas = document.createElement('canvas');
  canvas.width = width;
  canvas.height = height;
  const ctx = canvas.getContext('2d');
  ctx.fillStyle = options.background || '#ffffff';
  ctx.fillRect(0, 0, width, height);
  ctx.drawImage(image, 0, 0, width, height);
  const base64 = canvas.toDataURL('image/jpeg', options.quality || 0.84).split(',')[1];
  const binary = atob(base64);
  const bytes = new Uint8Array(binary.length);
  for (let i = 0; i < binary.length; i += 1) bytes[i] = binary.charCodeAt(i);
  return { bytes, width, height };
};

class PageCanvas {
  constructor() { this.ops = []; }
  push(value) { this.ops.push(value); }
  fill(r, g, b) { this.push(`${r} ${g} ${b} rg`); }
  stroke(r, g, b) { this.push(`${r} ${g} ${b} RG`); }
  lineWidth(value) { this.push(`${value} w`); }
  rect(x, y, width, height, mode = 'S') { this.push(`${x} ${PAGE_H - y - height} ${width} ${height} re ${mode}`); }
  line(x1, y1, x2, y2) { this.push(`${x1} ${PAGE_H - y1} m ${x2} ${PAGE_H - y2} l S`); }
  text(value, x, y, size = 8, font = 'F1', colour = [0, 0, 0], align = 'left') {
    const safe = escapePdf(value);
    const estimatedWidth = safe.length * size * (font === 'F2' ? 0.54 : 0.49);
    let tx = x;
    if (align === 'center') tx -= estimatedWidth / 2;
    if (align === 'right') tx -= estimatedWidth;
    this.push(`${colour[0]} ${colour[1]} ${colour[2]} rg BT /${font} ${size} Tf 1 0 0 1 ${tx.toFixed(2)} ${(PAGE_H - y).toFixed(2)} Tm (${safe}) Tj ET`);
  }
  wrapped(value, x, y, width, size = 7, font = 'F1', colour = [0.45, 0.45, 0.45], lineHeight = size * 1.25) {
    const words = String(value || '').split(/\s+/);
    const lines = [];
    let line = '';
    const maxChars = Math.max(8, Math.floor(width / (size * 0.49)));
    words.forEach(word => {
      const candidate = line ? `${line} ${word}` : word;
      if (candidate.length > maxChars && line) { lines.push(line); line = word; }
      else line = candidate;
    });
    if (line) lines.push(line);
    lines.forEach((entry, index) => this.text(entry, x, y + index * lineHeight, size, font, colour));
    return lines.length * lineHeight;
  }
  image(name, x, y, width, height) { this.push(`q ${width} 0 0 ${height} ${x} ${PAGE_H - y - height} cm /${name} Do Q`); }
  stream() { return latinBytes(`${this.ops.join('\n')}\n`); }
}

const footer = canvas => {
  canvas.stroke(0, 0, 0); canvas.lineWidth(0.45); canvas.line(41, 742, 554, 742);
  canvas.wrapped('Abby Lighting & Switchgear Limited 802 A, Fortune Terraces, New Link Road, Opp City Mall, Andheri West, Mumbai - 400053, India.', 41, 756, 155, 5.5, 'F1', [.45, .45, .45], 6.6);
  canvas.wrapped('Abby reserves the right to discontinue any product from its collection at any time whatsoever and without prior notice', 220, 756, 158, 5.5, 'F1', [.45, .45, .45], 6.6);
  canvas.wrapped('frontdesk@abbylighting.com,  +91 9833645212  www.abbylighting.com', 422, 756, 132, 5.5, 'F1', [.45, .45, .45], 6.6);
};

const pageFrame = canvas => {
  canvas.fill(0, 0, 0); canvas.rect(0, 0, PAGE_W, 70, 'f');
  canvas.image('Logo', 503, 11, 62, 43);
  canvas.stroke(.35, .35, .35); canvas.lineWidth(.25); canvas.rect(10, 82, 575, 694, 'S');
  footer(canvas);
};

const specRow = (canvas, label, value, y, options = {}) => {
  const grey = [.45, .45, .45];
  canvas.text(label, options.indent ? 68 : 48, y, 6.3, 'F1', grey);
  canvas.text(value, options.indent ? 176 : 168, y, 6.3, 'F1', [0, 0, 0]);
  if (!options.noLine) { canvas.stroke(.86, .86, .86); canvas.lineWidth(.3); canvas.line(48, y + 8, 548, y + 8); }
};

const textWithCode = (canvas, value, code, x, y, size = 6.3) => {
  canvas.text(value, x, y, size, 'F1', [0, 0, 0]);
  if (code) {
    const estimated = String(value).length * size * .49;
    canvas.text(code, x + estimated + 5, y - 1, Math.max(4.2, size - 1.6), 'F2', [.72, .72, .72]);
  }
};

export const buildPageOne = data => {
  const canvas = new PageCanvas();
  pageFrame(canvas);
  canvas.text((data.pdfTitle || data.product).toUpperCase(), 50, 106, 15, 'F2');
  canvas.stroke(0, 0, 0); canvas.lineWidth(.4); canvas.line(50, 118, 556, 118);
  canvas.image('Product', 46, 138, 176, 127);
  canvas.image('Drawing', 274, 138, 264, 127);
  canvas.text('Specifications', 46, 286, 7.1, 'F2');
  const dimensionKeys = new Set(['size', 'wattage', 'height', 'diameter', 'canopy', 'wire length']);
  const lightingKeys = new Set(['light source', 'cct', 'colour temperature', 'color temperature', 'input voltage', 'control']);
  const coreRows = data.specRows.filter(row => !dimensionKeys.has(row.normalKey) && !lightingKeys.has(row.normalKey));
  const dimensionRows = data.specRows.filter(row => dimensionKeys.has(row.normalKey));
  const lightingRows = data.specRows.filter(row => lightingKeys.has(row.normalKey));
  let y = 307;
  coreRows.forEach(row => {
    const valueLineHeight = 7.2;
    const noteLineHeight = 6;
    const valueLines = Math.max(1, Math.ceil(row.displayValue.length / 116));
    const noteLines = row.note ? Math.max(1, Math.ceil(row.note.length / 145)) : 0;
    const noteStart = noteLines ? valueLines * valueLineHeight + 2 : 0;
    const lastBaselineOffset = noteLines
      ? noteStart + (noteLines - 1) * noteLineHeight
      : (valueLines - 1) * valueLineHeight;
    const height = Math.max(20, lastBaselineOffset + 20);
    const dividerY = y + lastBaselineOffset + 10;
    canvas.text(row.key, 46, y, 5.8, 'F1', [.45, .45, .45]);
    canvas.wrapped(row.displayValue, 166, y, 382, 5.8, 'F1', [0, 0, 0], valueLineHeight);
    if (row.note) canvas.wrapped(row.note, 166, y + noteStart, 382, 4.6, 'F3', [.35, .35, .35], 5.8);
    canvas.stroke(.86, .86, .86); canvas.lineWidth(.3); canvas.line(46, dividerY, 548, dividerY);
    y += height;
  });
  y += 14;
  if (dimensionRows.length) {
    const boxTop = y - 8;
    const rowHeight = 22;
    const boxHeight = dimensionRows.length * rowHeight + 8;
    canvas.stroke(.35, .35, .35); canvas.lineWidth(.25); canvas.rect(46, boxTop, 502, boxHeight, 'S');
    dimensionRows.forEach((row, index) => {
      const rowY = y + index * rowHeight;
      const options = data.specOptions[row.normalKey] || [row.value];
      const codes = data.specOptionCodes[row.normalKey] || [];
      canvas.text(row.key, 70, rowY, 5.8, 'F1', [.45, .45, .45]);
      options.slice(0, 3).forEach((value, optionIndex) => textWithCode(canvas, value, codes[optionIndex], 176 + optionIndex * 82, rowY, 5.8));
      if (index < dimensionRows.length - 1) { canvas.stroke(.86, .86, .86); canvas.lineWidth(.3); canvas.line(57, rowY + 9, 537, rowY + 9); }
    });
    y = boxTop + boxHeight + 28;
  }
  lightingRows.forEach(row => {
    canvas.text(row.key, 46, y, 5.8, 'F1', [.45, .45, .45]);
    const options = data.specOptions[row.normalKey];
    const codes = data.specOptionCodes[row.normalKey] || [];
    if (options?.length) {
      const starts = options.length >= 5 ? [166, 211, 256, 301, 358] : options.length === 2 ? [166, 245] : options.map((_, index) => 166 + index * 82);
      options.forEach((value, index) => textWithCode(canvas, value, codes[index], starts[index] || 166 + index * 72, y, value.length > 23 ? 4.5 : 5.7));
    } else canvas.text(row.value, 166, y, 5.8, 'F1', [0, 0, 0]);
    canvas.stroke(.86, .86, .86); canvas.lineWidth(.3); canvas.line(46, y + 9, 548, y + 9);
    y += 23;
  });
  return canvas.stream();
};

export const buildPageTwo = data => {
  const canvas = new PageCanvas();
  pageFrame(canvas);
  const label = (text, x, y) => canvas.text(text.toUpperCase(), x, y, 5, 'F1', [.33, .33, .33]);
  const box = (x, y, width, height) => { canvas.stroke(.6, .6, .6); canvas.lineWidth(.25); canvas.rect(x, y, width, height, 'S'); };
  canvas.text('Project Details', 37, 108, 7.2, 'F2');
  label('Project Name', 37, 128); box(37, 132, 521, 20);
  label('Architect', 37, 162); box(37, 166, 258, 20);
  label('Quantity', 302, 162); box(302, 166, 256, 20);
  label('Location', 37, 196); box(37, 200, 258, 20); box(302, 200, 256, 20);
  label('Additional Comments', 37, 230); box(37, 234, 521, 43);

  canvas.text('Ordering Details', 37, 311, 7.2, 'F2');
  canvas.stroke(0, 0, 0); canvas.lineWidth(.45); canvas.line(37, 322, 558, 322);
  const cols = ['PRODUCT', 'SIZE', 'WATTAGE', 'CCT', 'P. FINISH', 'S. FINISH', 'CONTROL'];
  const values = [data.order.product, data.order.size, data.order.wattage, data.order.cct, data.order.primaryFinish, data.order.secondaryFinish, data.order.control];
  const x0 = 85;
  const colW = 67.55;
  canvas.text('Code', 37, 353, 6.4, 'F1'); canvas.text('Example', 37, 361, 6.4, 'F1');
  cols.forEach((column, index) => canvas.text(column, x0 + index * colW + colW / 2, 347, 5.8, 'F1', [.3, .3, .3], 'center'));
  cols.forEach((_, index) => box(x0 + index * colW, 352, colW - 7, 26));

  canvas.fill(.97, .97, .97); canvas.rect(37, 405, 521, 119, 'f');
  canvas.text('Example', 53, 433, 6.3, 'F2');
  canvas.text('Code', 53, 460, 5.8, 'F1'); canvas.text('Example', 53, 468, 5.8, 'F1');
  cols.forEach((column, index) => canvas.text(column === 'P. FINISH' ? 'PRIMARY FINISH' : column === 'S. FINISH' ? 'SECONDARY FINISH' : column, x0 + index * colW + colW / 2, 450, 5.1, 'F1', [.3, .3, .3], 'center'));
  values.forEach((value, index) => {
    const x = x0 + index * colW;
    box(x, 456, colW - 7, 25);
    if (value) canvas.text(value, x + (colW - 7) / 2, 471, value.length > 13 ? 4.2 : 5.1, 'F2', [0, 0, 0], 'center');
    else canvas.text('X', x + (colW - 7) / 2, 475, 18, 'F1', [0, 0, 0], 'center');
  });
  const finalCode = values.filter(Boolean).join(' ');
  canvas.text('Final Code', 53, 505, 5.8, 'F1');
  canvas.text(finalCode, 96, 505, finalCode.length > 58 ? 4.7 : 5.5, 'F2');
  return canvas.stream();
};

export const buildPdf = ({ pageOne, pageTwo, logo, product, drawing }) => {
  const objects = [];
  const add = value => { objects.push(value); return objects.length; };
  const catalogId = add('');
  const pagesId = add('');
  const fontRegularId = add('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>');
  const fontBoldId = add('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>');
  const fontItalicId = add('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Oblique /Encoding /WinAnsiEncoding >>');
  const logoId = add({ stream: logo.bytes, dict: `/Type /XObject /Subtype /Image /Width ${logo.width} /Height ${logo.height} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode` });
  const productId = add({ stream: product.bytes, dict: `/Type /XObject /Subtype /Image /Width ${product.width} /Height ${product.height} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode` });
  const drawingId = add({ stream: drawing.bytes, dict: `/Type /XObject /Subtype /Image /Width ${drawing.width} /Height ${drawing.height} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode` });
  const streamOneId = add({ stream: pageOne, dict: '' });
  const streamTwoId = add({ stream: pageTwo, dict: '' });
  const resourceOne = `<< /Font << /F1 ${fontRegularId} 0 R /F2 ${fontBoldId} 0 R /F3 ${fontItalicId} 0 R >> /XObject << /Logo ${logoId} 0 R /Product ${productId} 0 R /Drawing ${drawingId} 0 R >> >>`;
  const resourceTwo = `<< /Font << /F1 ${fontRegularId} 0 R /F2 ${fontBoldId} 0 R /F3 ${fontItalicId} 0 R >> /XObject << /Logo ${logoId} 0 R >> >>`;
  const pageOneId = add(`<< /Type /Page /Parent ${pagesId} 0 R /MediaBox [0 0 ${PAGE_W} ${PAGE_H}] /Resources ${resourceOne} /Contents ${streamOneId} 0 R >>`);
  const pageTwoId = add(`<< /Type /Page /Parent ${pagesId} 0 R /MediaBox [0 0 ${PAGE_W} ${PAGE_H}] /Resources ${resourceTwo} /Contents ${streamTwoId} 0 R >>`);
  objects[catalogId - 1] = `<< /Type /Catalog /Pages ${pagesId} 0 R >>`;
  objects[pagesId - 1] = `<< /Type /Pages /Kids [${pageOneId} 0 R ${pageTwoId} 0 R] /Count 2 >>`;

  const parts = [];
  const offsets = [0];
  let length = 0;
  const push = bytes => { parts.push(bytes); length += bytes.length; };
  push(latinBytes('%PDF-1.4\n%\xE2\xE3\xCF\xD3\n'));
  objects.forEach((object, index) => {
    offsets.push(length);
    push(latinBytes(`${index + 1} 0 obj\n`));
    if (typeof object === 'string') push(latinBytes(`${object}\nendobj\n`));
    else {
      push(latinBytes(`<< ${object.dict} /Length ${object.stream.length} >>\nstream\n`));
      push(object.stream);
      push(latinBytes('\nendstream\nendobj\n'));
    }
  });
  const xref = length;
  push(latinBytes(`xref\n0 ${objects.length + 1}\n0000000000 65535 f \n`));
  offsets.slice(1).forEach(offset => push(latinBytes(`${String(offset).padStart(10, '0')} 00000 n \n`)));
  push(latinBytes(`trailer\n<< /Size ${objects.length + 1} /Root ${catalogId} 0 R >>\nstartxref\n${xref}\n%%EOF`));
  return new Blob(parts, { type: 'application/pdf' });
};

export async function generateProductDatasheet() {
  const data = readProductData();
  const [logo, product, drawing] = await Promise.all([
    loadJpeg('/images/abby-logo.png', { background: '#000000', maxWidth: 500, quality: .9 }),
    loadJpeg(data.stageImage, { background: '#ffffff', maxWidth: 1000, quality: .82 }),
    loadJpeg(data.drawingImage, { background: '#ffffff', maxWidth: 500, quality: .9 }),
  ]);
  const blob = buildPdf({ pageOne: buildPageOne(data), pageTwo: buildPageTwo(data), logo, product, drawing });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `${data.product.replace(/\s+/g, '-')}-datasheet.pdf`;
  document.body.append(link);
  link.click();
  link.remove();
  setTimeout(() => URL.revokeObjectURL(url), 2000);
}
