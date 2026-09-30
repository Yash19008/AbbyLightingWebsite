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
    clone.querySelectorAll('sup').forEach(sup => sup.remove());
    clone.innerHTML = clone.innerHTML
      .replace(/<br\s*\/?>/gi, '\n')
      .replace(/<\/?(p|div)>/gi, '\n')
      .replace(/<li>/gi, '\n• ')
      .replace(/<\/li>/gi, '')
      .replace(/<ul>|<\/ul>/gi, '\n');
    let rawText = clone.textContent;
    const value = rawText
      .replace(/[ \t\r]+/g, ' ')
      .replace(/\n\s+/g, '\n')
      .replace(/\s+\n/g, '\n')
      .replace(/\n+/g, '\n')
      .trim();
    const noteClone = valueNode.querySelector('small')?.cloneNode(true);
    let note = '';
    if (noteClone) {
      noteClone.innerHTML = noteClone.innerHTML.replace(/<br\s*\/?>/gi, '\n');
      note = noteClone.textContent.replace(/[ \t\r]+/g, ' ').replace(/\n\s+/g, '\n').replace(/\s+\n/g, '\n').replace(/\n+/g, '\n').trim();
    }
    const displayValue = clone.innerHTML
      .replace(/[ \t\r]+/g, ' ')
      .replace(/\n\s+/g, '\n')
      .replace(/\s+\n/g, '\n')
      .replace(/\n+/g, '\n')
      .trim();
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
  const pdfTitle = product;
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
  wrapped(value, x, y, width, size = 7, font = 'F1', colour = [0.45, 0.45, 0.45], lineHeight = size * 1.25, align = 'left') {
    const rawLines = String(value || '').split(/\n/);
    const lines = [];
    const maxChars = Math.max(8, Math.floor(width / (size * 0.49)));

    rawLines.forEach(rawLine => {
      const words = rawLine.split(/\s+/);
      let line = '';
      words.forEach(word => {
        const candidate = line ? `${line} ${word}` : word;
        if (candidate.length > maxChars && line) { lines.push(line); line = word; }
        else line = candidate;
      });
      if (line) lines.push(line);
    });

    lines.forEach((entry, index) => this.text(entry, x, y + index * lineHeight, size, font, colour, align));
    return lines.length * lineHeight;
  }
  richWrapped(htmlString, x, y, width, size = 7, defaultFont = 'F1', defaultColour = [0.45, 0.45, 0.45], lineHeight = size * 1.25) {
    const div = document.createElement('div');
    div.innerHTML = htmlString;

    let lines = [];
    let currentLine = [];
    let currentLineWidth = 0;

    const parseColor = color => {
      if (!color) return defaultColour;
      const hex = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(color);
      if (hex) return [parseInt(hex[1], 16) / 255, parseInt(hex[2], 16) / 255, parseInt(hex[3], 16) / 255];
      const rgb = color.match(/\d+/g);
      if (rgb && rgb.length >= 3) return [parseInt(rgb[0]) / 255, parseInt(rgb[1]) / 255, parseInt(rgb[2]) / 255];
      return defaultColour;
    };

    const walk = (node, currentFont, currentColour) => {
      let nextFont = currentFont; let nextColour = currentColour;
      if (node.nodeType === 1) {
        const tag = node.tagName.toLowerCase();
        if (tag === 'em' || tag === 'i') nextFont = 'F3';
        if (tag === 'strong' || tag === 'b') nextFont = 'F2';
        if (node.style.color) nextColour = parseColor(node.style.color);
        node.childNodes.forEach(child => walk(child, nextFont, nextColour));
      } else if (node.nodeType === 3) {
        const tokens = node.textContent.split(/(\n|[ \t]+)/);
        tokens.forEach(word => {
          if (!word) return;
          if (word === '\n') {
            lines.push(currentLine);
            currentLine = [];
            currentLineWidth = 0;
            return;
          }
          if (word.trim() === '') {
            if (currentLineWidth > 0 && currentLine.length > 0) {
              currentLine[currentLine.length - 1].text += ' ';
              currentLineWidth += size * (currentLine[currentLine.length - 1].font === 'F2' ? 0.54 : 0.49);
            }
            return;
          }

          const charMultiplier = nextFont === 'F2' ? 0.54 : 0.49;
          const wordWidth = word.length * size * charMultiplier;

          if (currentLineWidth + wordWidth > width && currentLineWidth > 0) {
            lines.push(currentLine);
            currentLine = [];
            currentLineWidth = 0;
          }

          if (currentLine.length > 0 && currentLine[currentLine.length - 1].font === nextFont && currentLine[currentLine.length - 1].color === nextColour) {
            currentLine[currentLine.length - 1].text += word;
          } else {
            currentLine.push({ text: word, font: nextFont, color: nextColour });
          }
          currentLineWidth += wordWidth;
        });
      }
    };

    walk(div, defaultFont, defaultColour);
    if (currentLine.length > 0) lines.push(currentLine);

    let ty = y;
    lines.forEach(line => {
      if (line.length === 0) { ty += lineHeight; return; }
      let tx = x;
      line.forEach(chunk => {
        this.text(chunk.text, tx, ty, size, chunk.font, chunk.color);
        tx += chunk.text.length * size * (chunk.font === 'F2' ? 0.54 : 0.49);
      });
      ty += lineHeight;
    });

    return ty - y;
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

export const buildPageOne = (data, product, drawing) => {
  const canvas = new PageCanvas();
  pageFrame(canvas);
  canvas.text((data.pdfTitle || data.product).toUpperCase(), 50, 106, 15, 'F2');
  canvas.stroke(0, 0, 0); canvas.lineWidth(.4); canvas.line(50, 118, 556, 118);
  
  if (product) {
    let pWidth = 176;
    let pHeight = 176 / (product.width / product.height);
    if (pHeight > 127) { pHeight = 127; pWidth = 127 * (product.width / product.height); }
    canvas.image('Product', 46 + (176 - pWidth) / 2, 138 + (127 - pHeight) / 2, pWidth, pHeight);
  }
  
  if (drawing) {
    let dWidth = 264;
    let dHeight = 264 / (drawing.width / drawing.height);
    if (dHeight > 127) { dHeight = 127; dWidth = 127 * (drawing.width / drawing.height); }
    canvas.image('Drawing', 274 + (264 - dWidth) / 2, 138 + (127 - dHeight) / 2, dWidth, dHeight);
  }
  
  canvas.text('Specifications', 46, 286, 7.1, 'F2');
  let y = 307;
  let inBox = false;
  let boxTop = 0;
  let boxRows = [];

  const flushBox = () => {
    if (boxRows.length) {
      const rowHeight = 22;
      const boxHeight = boxRows.length * rowHeight;
      canvas.stroke(.7, .7, .7); canvas.lineWidth(.25); canvas.rect(46, boxTop, 502, boxHeight, 'S');
      boxRows.forEach((row, index) => {
        const rowY = boxTop + index * rowHeight + (rowHeight / 2) - 2.9;
        const options = data.specOptions[row.normalKey] || [row.value];
        const codes = data.specOptionCodes[row.normalKey] || [];
        canvas.text(row.key, 54, rowY, 5.8, 'F1', [.45, .45, .45]);
        options.slice(0, 5).forEach((value, optionIndex) => textWithCode(canvas, value, codes[optionIndex], 166 + optionIndex * 78, rowY, 5.8));
        if (index < boxRows.length - 1) { 
          canvas.stroke(.88, .88, .88); 
          canvas.lineWidth(.3); 
          const lineY = boxTop + (index + 1) * rowHeight;
          canvas.line(54, lineY, 540, lineY); 
        }
      });
      y = boxTop + boxHeight + 14;
      boxRows = [];
      inBox = false;
    }
  };

  let shouldBoxRemaining = false;

  data.specRows.forEach(row => {
    if (row.normalKey === 'size') shouldBoxRemaining = true;

    const options = data.specOptions[row.normalKey];
    const isComparison = shouldBoxRemaining || (options && options.length > 1);

    if (isComparison) {
      if (!inBox) {
        inBox = true;
        boxTop = y;
      }
      boxRows.push(row);
    } else {
      flushBox();

      canvas.text(row.key, 46, y, 5.8, 'F1', [.45, .45, .45]);

      const valueHeight = canvas.richWrapped(row.displayValue, 166, y, 382, 5.8, 'F1', [0, 0, 0], 7.2);
      const lastValueBaseline = valueHeight - 7.2;

      let noteHeight = 0;
      if (row.note) {
        noteHeight = canvas.wrapped(row.note, 166, y + valueHeight + 2, 382, 4.6, 'F3', [.35, .35, .35], 5.8);
      }
      const lastNoteBaseline = noteHeight ? noteHeight - 5.8 : 0;

      const lastBaselineOffset = noteHeight > 0
        ? valueHeight + 2 + lastNoteBaseline
        : lastValueBaseline;

      const height = Math.max(20, lastBaselineOffset + 20);
      const dividerY = y + lastBaselineOffset + 10;

      canvas.stroke(.86, .86, .86); canvas.lineWidth(.3); canvas.line(46, dividerY, 548, dividerY);
      y += height;
    }
  });
  flushBox();

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
  label('Location', 302, 162); box(302, 166, 256, 20);
  label('Additional Comments', 37, 196); box(37, 200, 521, 43);

  canvas.text('Ordering Details', 37, 277, 7.2, 'F2');
  canvas.stroke(0, 0, 0); canvas.lineWidth(.45); canvas.line(37, 288, 558, 288);
  const cols = ['PRODUCT', 'SIZE', 'WATTAGE', 'CCT', 'PRIMARY FINISH', 'SECONDARY FINISH', 'CONTROL'];
  const values = [data.order.product, data.order.size, data.order.wattage, data.order.cct, data.order.primaryFinish, data.order.secondaryFinish, data.order.control];
  const x0 = 85;
  const colW = 67.55;
  canvas.text('Code', 37, 319, 6.4, 'F1'); canvas.text('Example', 37, 327, 6.4, 'F1');
  cols.forEach((column, index) => canvas.text(column, x0 + index * colW + (colW - 7) / 2, 313, 5.8, 'F1', [.3, .3, .3], 'center'));
  cols.forEach((_, index) => box(x0 + index * colW, 318, colW - 7, 26));

  const getLines = (value, width, size) => {
    const safeValue = String(value || '').replace(/\//g, '/ ').replace(/,/g, ', ').replace(/\s+/g, ' ');
    const rawLines = safeValue.split(/\n/);
    const lines = [];
    const maxChars = Math.max(5, Math.floor(width / (size * 0.54)));
    rawLines.forEach(rawLine => {
      const words = [];
      rawLine.split(/\s+/).forEach(w => {
        if (w.length > maxChars) {
          for (let i = 0; i < w.length; i += maxChars) words.push(w.substring(i, i + maxChars));
        } else {
          words.push(w);
        }
      });

      let line = '';
      words.forEach(word => {
        const candidate = line ? `${line} ${word}` : word;
        if (candidate.length > maxChars && line) { lines.push(line); line = word; }
        else line = candidate;
      });
      if (line) lines.push(line);
    });
    return lines;
  };

  const textWidth = colW - 9;
  const cellData = values.map(value => {
    if (!value) return { value, lines: [], size: 4.5, lh: 5.4 };
    const safeValue = String(value).replace(/\//g, '/ ').replace(/,/g, ', ').replace(/\s+/g, ' ');
    const longestWord = safeValue.split(/\s+/).reduce((max, w) => Math.max(max, w.length), 0);
    const size = longestWord > 14 ? 3.8 : 4.5;
    const lh = size * 1.25;
    return { value, lines: getLines(value, textWidth, size), size, lh };
  });

  const maxContentHeight = Math.max(...cellData.map(c => c.lines.length * c.lh));
  const boxHeight = Math.max(25, maxContentHeight + 8);
  const extraHeight = boxHeight - 25;

  canvas.fill(.97, .97, .97); canvas.rect(37, 371, 521, 119 + extraHeight, 'f');
  canvas.text('Example', 53, 399, 6.3, 'F2');
  canvas.text('Code', 53, 426, 5.8, 'F1'); canvas.text('Example', 53, 434, 5.8, 'F1');
  cols.forEach((column, index) => canvas.text(column === 'P. FINISH' ? 'PRIMARY FINISH' : column === 'S. FINISH' ? 'SECONDARY FINISH' : column, x0 + index * colW + (colW - 7) / 2, 416, 5.1, 'F1', [.3, .3, .3], 'center'));

  cellData.forEach((c, index) => {
    const x = x0 + index * colW;
    box(x, 422, colW - 7, boxHeight);
    if (c.value) {
      const totalH = c.lines.length * c.lh;
      const startY = 422 + (boxHeight - totalH) / 2 + c.size * 0.75;
      c.lines.forEach((line, i) => {
        canvas.text(line, x + (colW - 7) / 2, startY + i * c.lh, c.size, 'F2', [0, 0, 0], 'center');
      });
    } else {
      canvas.text('X', x + (colW - 7) / 2, 422 + boxHeight / 2 + 6, 18, 'F1', [0, 0, 0], 'center');
    }
  });

  const finalCode = values.filter(Boolean).join(' ');
  canvas.text('Final Code', 53, 471 + extraHeight, 5.8, 'F1');
  canvas.text(finalCode, 96, 471 + extraHeight, finalCode.length > 58 ? 4.7 : 5.5, 'F2');
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
    loadJpeg(data.drawingImage, { background: '#ffffff', maxWidth: 1920, quality: 1.0 }),
  ]);
  const blob = buildPdf({ pageOne: buildPageOne(data, product, drawing), pageTwo: buildPageTwo(data), logo, product, drawing });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `${data.product.replace(/\s+/g, '-')}-datasheet.pdf`;
  document.body.append(link);
  link.click();
  link.remove();
  setTimeout(() => URL.revokeObjectURL(url), 2000);
}
