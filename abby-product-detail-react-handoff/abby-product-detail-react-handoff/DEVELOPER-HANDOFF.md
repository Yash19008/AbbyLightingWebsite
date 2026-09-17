# Abby Lighting product-detail handoff

This package contains the current production source for these two pages:

- `/product-detail/circulo`
- `/product-detail/symphony-iv`

Reference deployment: <https://abbylighting-test.arunavadutta.chatgpt.site>

## Primary implementation files

| Page / feature | Source |
| --- | --- |
| Circulo route, page-specific CSS and interactions | `app/product-detail/circulo/route.ts` |
| Symphony IV route, page-specific CSS and interactions | `app/product-detail/symphony-iv/route.ts` |
| Shared captured product-page markup | `app/product-detail/symphony-iv/symphony-html.ts` |
| Two-page vector PDF datasheet generator | `public/assets/symphony-datasheet-vector.js` |
| Circulo product imagery | `public/images/circulo-figma/` |
| Symphony IV product imagery | `public/images/symphony-iv-figma-live/` and `public/images/decorative/` |
| Shared zoom control | `public/images/product-zoom-icon.png` |

## Architecture note

Both URLs are implemented as App Router `GET` route handlers. They return a stable server-rendered HTML shell and inject page-specific CSS and browser-side interaction logic. Circulo reuses the Symphony HTML shell, then replaces copy, specification data, gallery assets and related-product content.

This architecture was retained because the original page came from captured React output. Its stale hydration payload is deliberately removed before the response is returned; restoring that payload will cause click interactions to reconcile against the wrong component tree.

## Included behavior

- Responsive desktop and mobile layouts
- Main gallery, thumbnails, navigation arrows and zoom lightbox
- Featured-colour image swapping for the first gallery image only
- Expandable additional-colours panel with outside-click dismissal
- Size selection and press/crossfade micro-interactions
- Enquiry modal
- Collection banner
- Collapsible Specifications and Downloads sections
- Circulo desktop and mobile size matrix, including native horizontal scrolling on mobile
- Horizontally scrollable related-product carousels
- Direct two-page vector PDF datasheet download
- Motion-reduction and pointer accessibility behavior

## Product-specific data

Product copy and specification transformations currently live inside each route's `applyFigmaRefresh()` function. The browser-side behaviors are in the `figmaDomSync` string. Featured colour-to-image mappings are declared in the route script and affect only gallery index `0`.

The datasheet generator reads the rendered specification DOM, so labels, visible codes and product values must remain semantically structured. Small light-grey codes use `.spec-code`; the generator uses those codes for ordering details when present.

## Integration

1. Copy `app/product-detail/` into the destination App Router project.
2. Copy `public/assets/` and `public/images/` without flattening their directory structure.
3. Preserve the root layout, global styles, package manifest and Vite/Vinext configuration included in this bundle.
4. Run `npm install` and `npm run build`.
5. Verify both routes at desktop and mobile widths, then test gallery navigation, colour selection, accordions and PDF download.

## Important constraints

- Do not re-enable the scripts stripped by `applyFigmaRefresh()` unless the page is first rebuilt as a fully native React component tree.
- Keep the PDF module query version synchronized in both route files whenever `symphony-datasheet-vector.js` changes.
- Preserve mobile overflow rules for the Circulo size matrix and both family carousels.
- Do not replace full-size gallery sources with thumbnail exports; several thumbnail images are intentionally low resolution.

