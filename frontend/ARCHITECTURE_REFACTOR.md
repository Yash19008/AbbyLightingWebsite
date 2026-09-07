# Architecture Refactor: Server-Side Data Fetching

## Overview
Refactored the Next.js App Router site to use **server-side data fetching** instead of client-side useEffect calls, eliminating flickering, waterfalls, and improving performance with ISR caching.

## Changes Made

### 1. New Files Created

#### `lib/api/server-fetchers.ts`
- **Purpose**: Centralized server-side data fetching functions
- **Features**:
  - All API calls with Next.js `fetch()` and `next: { revalidate }` caching
  - Individual fetchers for each endpoint
  - `fetchHomePageData()` wrapper that fetches all home data in parallel using `Promise.all`
  
**Revalidation Strategy**:
| Endpoint | Revalidate Time | Reasoning |
|----------|----------------|-----------|
| Categories | 3600s (1 hour) | Rarely change |
| Clients | 3600s (1 hour) | Stable list |
| Projects | 1800s (30 min) | Updated occasionally |
| Sliders | 600s (10 min) | May be updated for promotions |
| News Items | 300s (5 min) | Timely content |
| Manufacturing | 3600s (1 hour) | Rarely changes |
| New Arrivals | 600s (10 min) | Product updates |

#### `components/HeaderClient.tsx`
- **Purpose**: Client component for Header with interactivity
- **Props**: `categories: DecorativeCategory[]`
- **Client Features**:
  - CSS hover for mega dropdown (no JavaScript state needed)
  - All rendering handled by React (no DOM manipulation)
  - Fallback static content only shown if server fetch fails

#### `components/HomePageClient.tsx`
- **Purpose**: Client component for Home page with all interactive features
- **Props**: All home page data (sliders, projects, clients, news, manufacturing, newArrivals)
- **Client Features**:
  - Hero carousel logic (autoplay, touch, dots, arrows)
  - New arrivals tab switching
  - Spotlight cursor effect
  - Intersection Observer for reveal animations
  - All rendering handled by React

### 2. Modified Files

#### `app/layout.tsx`
- **Changed from**: Client Component
- **Changed to**: **Server Component**
- **Changes**:
  - Added `async` to component
  - Fetches `categories` via `fetchDecorativeCategories()`
  - Passes categories to `<HeaderClient />` as props
  - Categories fetched once per navigation, shared across all pages

#### `app/page.tsx`
- **Changed from**: Client Component with 6+ useEffect fetches
- **Changed to**: **Server Component**
- **Changes**:
  - Removed all `useState`, `useEffect`, `useRef`
  - Removed all client-side fetch logic
  - Added `async` to component
  - Calls `fetchHomePageData()` which fetches all data in parallel
  - Renders `<HomePageClient />` with all data as props

#### `components/Header.tsx`
- **Status**: **DEPRECATED** - replaced by `HeaderClient.tsx`
- **Old Issues**:
  - Used `useEffect` to fetch categories
  - Used `innerHTML` and refs for DOM manipulation
  - Caused flickering when categories loaded
- **New Solution**: `HeaderClient.tsx` receives data as props, no fetching

### 3. Deleted Files (Recommended)
- `components/Header.tsx` - replaced by `HeaderClient.tsx`
- `components/MegaDropdown.tsx` - if not used elsewhere

## Architecture Pattern

### Before (Client-Side Fetching)
```
User visits page
├─ React renders with empty state
├─ Browser paints empty/fallback UI ❌ FLASH
├─ useEffect fires after mount
├─ Fetch starts (client → server)
├─ Response received
├─ setState triggers re-render
└─ Real content shows ❌ FLASH
```

### After (Server-Side Fetching)
```
User visits page
├─ Server fetches all data in parallel
├─ Server renders complete HTML with real data
├─ Browser receives fully-rendered page
└─ Client hydrates with same data ✅ NO FLASH
```

## Benefits

1. **No Flickering**: Data is present on first paint
2. **Faster Load**: Parallel fetching on server (fast connection)
3. **ISR Caching**: Revalidate strategy means most requests are instant
4. **Better SEO**: Real content in HTML, not client-rendered
5. **Simpler Code**: No useEffect waterfalls, no loading states
6. **No DOM Manipulation**: React owns the DOM, no innerHTML hacks

## Testing

### Test Scenarios
1. **Fresh page load**: Should show real data immediately, no flicker
2. **Mega dropdown hover**: Should show/hide smoothly, categories visible
3. **Hero carousel**: Should autoplay, respond to arrows/dots/touch
4. **New arrivals tabs**: Should switch categories smoothly
5. **Failed fetch**: Should show fallback static content gracefully

### Expected Performance
- **First Contentful Paint**: Improved (data already in HTML)
- **Time to Interactive**: Similar (same client JS)
- **Subsequent visits**: Much faster (ISR cache hits)

## Migration Notes

### If You Need to Add More Data Fetching
1. Add fetcher function to `lib/api/server-fetchers.ts` with appropriate revalidate time
2. Call fetcher in server component (`page.tsx` or `layout.tsx`)
3. Pass data as props to client component
4. **Never** fetch in client component useEffect

### If You Need Client Interactivity
1. Keep component as "use client"
2. Accept data as props (fetched by parent server component)
3. Use useState/useEffect ONLY for UI state (tabs, modals, etc), NOT for data fetching

### Revalidation Time Guidelines
- **Static content (rarely changes)**: 3600s+ (1 hour+)
- **Semi-dynamic (daily/weekly updates)**: 600-1800s (10-30 min)
- **Dynamic content (hourly updates)**: 300-600s (5-10 min)
- **Real-time (must be fresh)**: Use `cache: 'no-store'` (loses ISR benefit)

## Rollback Plan

If issues arise:
1. Rename `components/Header.tsx.backup` back to `Header.tsx` (if you backed it up)
2. Revert `app/layout.tsx` to use old `Header`
3. Revert `app/page.tsx` to old client component
4. Keep new files for reference

## Next Steps

### Recommended
1. Apply same pattern to other pages (if any have client-side fetching)
2. Monitor ISR hit rates in production
3. Adjust revalidate times based on actual content update frequency
4. Consider adding error boundaries for graceful degradation

### Optional Improvements
1. Add loading.tsx for Suspense boundaries
2. Add error.tsx for error handling
3. Use React Server Components for more granular streaming
4. Add `generateStaticParams` for any dynamic routes

## Questions?

- **Why not use client components everywhere?** Server components are faster, better for SEO, and eliminate waterfalls.
- **What about real-time data?** Use server components with `cache: 'no-store'` or WebSocket updates on client.
- **Can I still use useState/useEffect?** Yes, for UI state only (modals, tabs, etc), not data fetching.
- **What if my API is slow?** Server fetching is faster (server→API is fast), and ISR caching makes subsequent loads instant.
