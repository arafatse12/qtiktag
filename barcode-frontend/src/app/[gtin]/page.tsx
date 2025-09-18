'use client';

import React, { Fragment, useEffect, useMemo, useState } from 'react';

import FabMenu from '../../components/FabMenu';

/* ===================== TYPES ===================== */

type RouteKey =
  | 'home'
  | 'product'
  | 'manufacturing'
  | 'materials'
  | 'custody'
  | 'usage'
  | 'certs'
  | 'sustain'
  | 'impact';

type ApiPayload = {
  gtin_16?: string;
  qr_image?: string;
  product?: {
    name?: string;
    description?: string;
    barcode?: string;
    sku_code?: string;
    model?: string;
    image_url?: string;
    image?: string;
    photo_url?: string;
  };
  manufacturing?: {
    batch?: string;
    manufactured_on?: string;
    packaged_on?: string;
    factory?: string;
    address?: string;
    origin?: string;
  };
  info_accuracy?: {
    updated_at?: string;
    publisher?: string;
  };
};

type ViewModel = {
  gtin: string;
  name: string;
  description: string;
  barcode: string;
  sku: string;
  serial: string;
  batch: string;
  mfgDate: string;
  packDate: string;
  factory?: string;
  address?: string;
  origin?: string;
  infoUpdated?: string;
  publisher?: string;
  qrImage?: string;
  productImage?: string;
};

type Err =
  | { code: 'errorB'; message: 'gtin 16b error' }
  | { code: string; message: string };

/* ===== Content blocks coming from Laravel JSON column ===== */

type RichBlock = { kind: 'rich'; heading?: string; paragraphs: string[] };
type ImageBlock = { kind: 'image'; name: string };
type ListBlock = {
  kind: 'list';
  title?: string;
  items: { group: string; rows: { key: string; value: string | number | boolean }[] }[];
};
type MetricsGridBlock = { kind: 'metricsGrid'; cards: { label: string; value: string; note?: string }[] };
type EventLogBlock = {
  kind: 'eventLog';
  title?: string;
  events: { actor: string; status: string; at: string | null; lat: number | null; lng: number | null }[];
};
type CardGridBlock = { kind: 'cardGrid'; title?: string; cards: { title: string; text: string; href?: string }[] };
type QrBlock = { kind: 'qr'; size?: number; caption?: string };
type KvBlock = { kind: 'kv'; title?: string; rows: { label: string; value: string }[] };

type Block = RichBlock | ImageBlock | ListBlock | MetricsGridBlock | EventLogBlock | CardGridBlock | QrBlock | KvBlock;

type SectionContent = { title: string; blocks: Block[] };
type SectionDoc = {
  id: number;
  name: string;
  slug: string;
  content: SectionContent;
  published: boolean;
  created_at: string;
  updated_at: string;
};

/* ===================== UTILS ===================== */

function normalizeDigits(s: string | null | undefined) {
  return (s ?? '').replace(/\D+/g, '');
}

function absolutize(base: string, maybeUrl?: string): string | undefined {
  if (!maybeUrl) return undefined;
  if (/^https?:\/\//i.test(maybeUrl)) return maybeUrl;
  const b = base.replace(/\/+$/, '');
  const p = maybeUrl.replace(/^\/+/, '');
  return `${b}/${p}`;
}

/** very small templating for blocks: "Hello {{name}} → Hello VM.name" */
function t(input: string, vm: ViewModel): string {
  return input.replace(/\{\{\s*(\w+)\s*\}\}/g, (_, key) => {
    const v = (vm as any)?.[key];
    return v == null || v === '' ? '—' : String(v);
  });
}

/* ===== Hash aliases & helpers (NEW) ===== */

const HASH_ALIASES: Record<string, RouteKey> = {
  home: 'home',
  '01': 'product',       // AI (01) → Product Identity
  product: 'product',
  manufacturing: 'manufacturing',
  materials: 'materials',
  custody: 'custody',
  usage: 'usage',
  certs: 'certs',
  sustain: 'sustain',
  impact: 'impact',
};

function parseHashToRoute(raw: string): RouteKey | null {
  const h = (raw || '').replace(/^#/, '').trim().toLowerCase();
  return (HASH_ALIASES[h] as RouteKey) || null;
}

/* ===================== DATA FETCH ===================== */

/** Product endpoint (enforce url GTIN = api GTIN) */
async function getData(gtinFromPath: string): Promise<ViewModel> {
  const cleanPathGtin = normalizeDigits(gtinFromPath);
  if (!cleanPathGtin) {
    const e = new Error('gtin 16b error') as Error & { code?: string };
    e.code = 'errorB';
    throw e;
  }

  const base = (process.env.NEXT_PUBLIC_API_BASE_URL ?? '').replace(/\/+$/, '');
  const url = `${base}/api/gtins/${encodeURIComponent(cleanPathGtin)}`;

  const res = await fetch(url, { headers: { Accept: 'application/json' }, cache: 'no-store' });
  if (res.status === 404 || !res.ok) {
    const e = new Error('gtin 16b error') as Error & { code?: string };
    e.code = 'errorB';
    throw e;
  }

  const json: ApiPayload = await res.json();
  const apiGtin16 = normalizeDigits(json.gtin_16);
  if (!apiGtin16 || apiGtin16 !== cleanPathGtin) {
    const e = new Error('gtin 16b error') as Error & { code?: string };
    e.code = 'errorB';
    throw e;
  }

  const p = json.product || {};
  const m = json.manufacturing || {};
  const info = json.info_accuracy || {};
  const imageCandidate = p.image_url || p.image || p.photo_url;

  return {
    gtin: apiGtin16,
    name: p.name || '—',
    description: p.description || '—',
    barcode: p.barcode || '—',
    sku: p.sku_code || '—',
    serial: p.model || 'HM0004588CX000006',
    batch: m.batch || '—',
    mfgDate: m.manufactured_on || '—',
    packDate: m.packaged_on || '—',
    factory: m.factory,
    address: m.address,
    origin: m.origin,
    infoUpdated: info.updated_at,
    publisher: info.publisher,
    qrImage: json.qr_image || undefined,
    productImage: absolutize(base, imageCandidate),
  };
}

/** Fetch a section doc by slug from Laravel JSON column */
async function getSection(slug: string): Promise<SectionDoc | null> {
  const base = (process.env.NEXT_PUBLIC_API_BASE_URL ?? '').replace(/\/+$/, '');
  const res = await fetch(`${base}/api/sections/${slug}`, { cache: 'no-store' });
  if (!res.ok) return null;
  return res.json();
}

/** Fetch multiple sections in parallel and return a map by slug */
async function getSectionsMap(slugs: string[]): Promise<Record<string, SectionDoc | null>> {
  const results = await Promise.all(slugs.map((s) => getSection(s)));
  const map: Record<string, SectionDoc | null> = {};
  slugs.forEach((s, i) => (map[s] = results[i]));
  return map;
}

/* ===================== RENDER HELPERS ===================== */

function SectionRenderer({
  slug,
  title,
  blocks,
  viewCls,
  vm,
}: {
  slug: string;
  title?: string;
  blocks: Block[];
  viewCls: (k: RouteKey) => string;
  vm: ViewModel;
}) {
  return (
    <section id={`view-${slug}`} className={viewCls(slug as RouteKey)} data-route={slug}>
      {title && <h2 className="section-title">{title}</h2>}
      {blocks.map((b, i) => {
        switch (b.kind) {
          case 'image':
            return <div key={i} className={`${b.name}-img mb-3`} />;
          case 'rich':
            return (
              <div key={i} className="mb-6">
                {b.heading && <h3 className="h3">{b.heading}</h3>}
                {b.paragraphs.map((p, j) => (
                  <p key={j} className="mb-3">
                    {t(p, vm)}
                  </p>
                ))}
              </div>
            );
          case 'list':
            return (
              <div key={i} className="mt-2 mb-6">
                {b.title && <h3 className="h3">{t(b.title, vm)}</h3>}
                {b.items.map((g, j) => (
                  <div key={j} className="mb-3">
                    <strong>{t(g.group, vm)}</strong>
                    <ul className="mb-0">
                      {g.rows.map((r, k) => (
                        <li key={k}>
                          {t(r.key as string, vm)} – {String(r.value)}
                        </li>
                      ))}
                    </ul>
                  </div>
                ))}
              </div>
            );
          case 'metricsGrid':
            return (
              <div key={i} className="row g-3 mt-1">
                {b.cards.map((c, j) => (
                  <div key={j} className="col-md-4">
                    <div className="p-3 border rounded text-center">
                      <div className="fw-bold">{t(c.label, vm)}</div>
                      <div className="fs-4">{t(c.value, vm)}</div>
                      {c.note && <div className="text-muted small">{t(c.note, vm)}</div>}
                    </div>
                  </div>
                ))}
              </div>
            );
          case 'eventLog':
            return (
              <div key={i} className="mb-6">
                {b.title && <h3 className="h3">{t(b.title, vm)}</h3>}
                <ul className="mb-0">
                  {b.events.map((e, j) => (
                    <li key={j}>
                      <strong>
                        {t(e.actor, vm)} – {t(e.status, vm)}
                      </strong>
                      {e.at && <> • {new Date(e.at).toISOString().replace('.000Z', 'Z')}</>}
                      {e.lat != null && e.lng != null && <> • {e.lat}, {e.lng}</>}
                    </li>
                  ))}
                </ul>
              </div>
            );
          case 'cardGrid':
            return (
              <div key={i} className="row g-3">
                {b.title && <h3 className="h3">{t(b.title, vm)}</h3>}
                {b.cards.map((c, j) => (
                  <div key={j} className="col-md-4">
                    <div className="p-3 border rounded h-100">
                      <div className="fw-semibold mb-2">{t(c.title, vm)}</div>
                      <p className="mb-2">{t(c.text, vm)}</p>
                      {c.href && (
                        <a className="link-danger fw-semibold" href={c.href}>
                          Learn more →
                        </a>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            );
          case 'qr': {
            const size = (b.size ?? 180) | 0;
            return (
              <div key={i} className="qr-box text-center mb-3">
                {vm.qrImage ? (
                  <img
                    src={vm.qrImage}
                    alt={`QR code for GTIN ${vm.gtin} and Serial ${vm.serial}`}
                    width={size}
                    height={size}
                    style={{ display: 'block', maxWidth: '100%', height: 'auto', margin: '0 auto' }}
                  />
                ) : (
                  QR(size)
                )}
                {b.caption && <div className="qr-caption mt-2">{t(b.caption, vm)}</div>}
              </div>
            );
          }
          case 'kv':
            return (
              <div key={i} className="mb-3">
                {b.title && <h3 className="h3">{t(b.title, vm)}</h3>}
                <div className="kv">
                  {b.rows.map((r, j) => (
                    <Fragment key={j}>
                      <div>
                        <strong>{t(r.label, vm)}</strong>
                      </div>
                      <div>{t(r.value, vm)}</div>
                    </Fragment>
                  ))}
                </div>
              </div>
            );
          default:
            return null;
        }
      })}
    </section>
  );
}

/* ===================== PAGE ===================== */

export default function Page({ params }: { params: Promise<{ gtin?: string }> }) {
  const { gtin = '' } = React.use(params); // unwrap the Promise once
  const [route, setRoute] = useState<RouteKey>('home');
  const [menuOpen, setMenuOpen] = useState(false);

  // data/ux state
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<Err | null>(null);
  const [vm, setVm] = useState<ViewModel | null>(null);

  // dynamic sections loaded from backend (include 'product' so it's dynamic too)
  const sectionSlugs: RouteKey[] = ['product', 'manufacturing', 'materials', 'custody', 'usage', 'certs', 'sustain', 'impact'];
  const [sections, setSections] = useState<Record<string, SectionDoc | null>>({});

  /* ===== Routing behavior (NEW) ===== */

  // initial route from hash (defaults to home)
  useEffect(() => {
    const r = parseHashToRoute(location.hash) || 'home';
    setRoute(r);
  }, []);

  // respond to hash changes (back/forward)
  useEffect(() => {
    const onHash = () => {
      const r = parseHashToRoute(location.hash) || 'home';
      setRoute(r);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    };
    window.addEventListener('hashchange', onHash);
    return () => window.removeEventListener('hashchange', onHash);
  }, []);

  // sync URL with current route (keep Home clean — no #home)
  useEffect(() => {
    const basePath = location.pathname + location.search;
    if (route === 'home') {
      history.replaceState(null, '', basePath);
    } else {
      history.replaceState(null, '', `${basePath}#${route}`);
    }
  }, [route]);

  // fetch product + sections
  useEffect(() => {
    (async () => {
      try {
        const pathGtin = gtin;
        if (!pathGtin) {
          const e = new Error('gtin 16b error') as Error & { code?: string };
          e.code = 'errorB';
          throw e;
        }
        const [productVm, sectionsMap] = await Promise.all([getData(pathGtin), getSectionsMap(sectionSlugs)]);
        setVm(productVm);
        setSections(sectionsMap);
        setError(null);
      } catch (e: any) {
        setVm(null);
        setSections({});
        setError({ code: 'errorB', message: 'gtin 16b error' });
      } finally {
        setLoading(false);
      }
    })();
  }, [gtin]);

  const isHome = route === 'home';
  const viewCls = (k: RouteKey) => `view ${route === k ? 'active' : ''}`;

  // Accessible live-region text
  const liveMsg = useMemo(() => {
    if (loading) return 'Loading…';
    if (error) return `${error.message} (${error.code})`;
    return '';
  }, [loading, error]);

  const shouldShowApp = !!vm && !error;

  return (
    <>
      {/* SR-only live region */}
      <div aria-live="polite" style={{ position: 'absolute', left: -9999, width: 1, height: 1, overflow: 'hidden' }}>
        {liveMsg}
      </div>

      {/* Error banner */}
      {error && (
        <div
          role="alert"
          aria-live="assertive"
          className="container-fixed"
          style={{
            marginTop: 12,
            background: '#FEF2F2',
            border: '1px solid #FCA5A5',
            color: '#991B1B',
            borderRadius: 10,
            padding: '10px 14px',
            fontWeight: 600,
          }}
        >
          {error.message} ({error.code})
        </div>
      )}

      {/* Loading hint */}
      {loading && !error && (
        <div className="container-fixed" style={{ marginTop: 12, opacity: 0.85 }}>
          Loading…
        </div>
      )}

      {/* ======= App ======= */}
      {shouldShowApp && (
        <>
          {/* Header (hidden on Home) */}
          <header id="global-header" className={isHome ? 'is-hidden' : ''}>
            <h1>{vm!.name}</h1>
            <small>{vm!.serial}</small>
          </header>

          <main id="main" className={`container-fixed my-3 ${isHome ? 'topless' : ''}`}>
            {/* ===================== HOME ===================== */}
            <section id="view-home" className={viewCls('home')} data-route="home">
              <div className="home-shell">
                <div className="home-banner">
                  <div className="home-logo">QLIKTAG</div>
                </div>
                <div className="home-sep" />
                <div className="home-stage">
                  <img
                    className="home-img"
                    alt={vm!.name ? `Photo of ${vm!.name}` : 'Product photo'}
                    src={
                      vm!.productImage
                        ? vm!.productImage
                        : 'https://cdn.qliktag.io/production/63ee03119cde45000847eb1c/HM%20Baby%20Trousers.png'
                    }
                    onError={(e) => {
                      (e.currentTarget as HTMLImageElement).src =
                        'https://cdn.qliktag.io/production/63ee03119cde45000847eb1c/HM%20Baby%20Trousers.png';
                    }}
                  />
                </div>
                <div className="home-caption">
                  <div className="home-title">{vm!.name}</div>

                  {/* Serial number opens the menu (NEW) */}
                  <button
                    type="button"
                    className="home-serial"
                    onClick={() => setMenuOpen(true)}
                    style={{ background: 'none', border: 0, padding: 0, cursor: 'pointer' }}
                    aria-label="Open menu"
                    title="Open menu"
                  >
                    {vm!.serial}
                  </button>
                </div>

                {/* At-a-glance */}
                <div className="glance">
                  {/* Identity quick facts */}
                  <div className="card span-8">
                    <div className="card-body">
                      <div className="d-flex align-items-center justify-content-between mb-2">
                        <div className="fw-semibold">Item Identification</div>
                        <span className="badge text-bg-light border">Product ID</span>
                      </div>
                      <div className="kv-mini">
                        <div className="muted">BARCODE NUMBER</div>
                        <div>
                          <strong>{vm!.barcode}</strong>
                        </div>
                        <div className="muted">SERIAL NUMBER</div>
                        <div>{vm!.serial}</div>
                        <div className="muted">ITEM SKU CODE</div>
                        <div>{vm!.sku}</div>
                        <div className="muted">ITEM NAME</div>
                        <div>{vm!.name}</div>
                        <div className="muted">ITEM DESCRIPTION</div>
                        <div>{vm!.description}</div>
                      </div>
                    </div>
                  </div>

                  {/* QR preview */}
                  <div className="card span-4">
                    <div className="card-body text-center">
                      <div className="qr-box text-center">
                        {vm!.qrImage ? (
                          <img
                            src={vm!.qrImage}
                            alt={`QR code for GTIN ${vm!.gtin} and Serial ${vm!.serial}`}
                            width={180}
                            height={180}
                            style={{ display: 'block', maxWidth: '100%', height: 'auto' }}
                          />
                        ) : (
                          QR(180)
                        )}
                      </div>
                    </div>
                  </div>

                  {/* Batch timing */}
                  <div className="card span-6">
                    <div className="card-body">
                      <div className="fw-semibold mb-2">Batch Identification</div>
                      <div className="kv-mini">
                        <div className="muted">BATCH / LOT NUMBER</div>
                        <div>{vm!.batch}</div>
                        <div className="muted">DATE OF MANUFACTURE</div>
                        <div>{vm!.mfgDate}</div>
                        <div className="muted">DATE OF PACKAGING</div>
                        <div>{vm!.packDate}</div>
                      </div>
                    </div>
                  </div>

                  {/* Manufacturer box */}
                  <div className="card span-6">
                    <div className="card-body">
                      <div className="fw-semibold mb-2">Manufacturer Information</div>
                      <div className="kv-mini">
                        <div className="muted">MANUFACTURED BY</div>
                        <div>{vm!.factory ?? '—'}</div>
                        <div className="muted">ADDRESS</div>
                        <div>{vm!.address ?? '—'}</div>
                        <div className="muted">ORIGIN</div>
                        <div>{vm!.origin ?? '—'}</div>
                      </div>
                    </div>
                  </div>

                  {/* Accuracy / publisher */}
                  <div className="card span-12">
                    <div className="card-body d-flex flex-wrap gap-3 align-items-center">
                      <div className="me-auto">
                        <div className="small text-uppercase text-muted">Information Last Updated On</div>
                        <div className="fw-semibold">{vm!.infoUpdated ?? '—'}</div>
                      </div>
                      <div>
                        <div className="small text-uppercase text-muted">Information Published By</div>
                        <div className="fw-semibold">{vm!.publisher ?? '—'}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            {/* ===================== PRODUCT — dynamic if available, else fallback ===================== */}
            {sections['product'] ? (
              <SectionRenderer
                slug="product"
                title={sections['product']?.content?.title || 'Product Identity'}
                blocks={sections['product']?.content?.blocks ?? []}
                viewCls={viewCls}
                vm={vm!}
              />
            ) : (
              <section id="view-product" className={viewCls('product')} data-route="product">
                <h2 className="section-title">Product Identity</h2>

                <div className="row g-4 align-items-start">
                  <div className="col-md-5">
                    <div className="qr-box text-center">
                      {vm!.qrImage ? (
                        <img
                          src={vm!.qrImage}
                          alt={`QR code for GTIN ${vm!.gtin} and Serial ${vm!.serial}`}
                          width={180}
                          height={180}
                          style={{ display: 'block', maxWidth: '100%', height: 'auto' }}
                        />
                      ) : (
                        QR(180)
                      )}
                    </div>
                  </div>

                  <div className="col-md-7">
                    <h3 className="h3">Item Identification</h3>
                    <div className="kv">
                      <div><strong>BARCODE NUMBER</strong></div><div>{vm!.barcode}</div>
                      <div><strong>SERIAL NUMBER</strong></div><div>{vm!.serial}</div>
                      <div><strong>ITEM SKU CODE</strong></div><div>{vm!.sku}</div>
                      <div><strong>ITEM NAME</strong></div><div>{vm!.name}</div>
                      <div><strong>ITEM DESCRIPTION</strong></div><div>{vm!.description}</div>
                    </div>

                    <h3 className="h3 mt-3">GTIN (AI 01) &amp; Serial (AI 21)</h3>
                    <div className="kv">
                      <div><strong>GTIN</strong></div><div>{vm!.gtin}</div>
                      <div><strong>Serial</strong></div><div>{vm!.serial}</div>
                    </div>
                  </div>
                </div>

                <h3 className="h3 mt-4">Batch Identification</h3>
                <div className="kv">
                  <div><strong>BATCH / LOT NUMBER</strong></div><div>{vm!.batch}</div>
                  <div><strong>DATE OF MANUFACTURE</strong></div><div>{vm!.mfgDate}</div>
                  <div><strong>DATE OF PACKAGING</strong></div><div>{vm!.packDate}</div>
                </div>
              </section>
            )}

            {/* ===================== OTHER DYNAMIC SECTIONS (from Laravel) ===================== */}
            {(['manufacturing', 'materials', 'custody', 'usage', 'certs', 'sustain', 'impact'] as RouteKey[]).map(
              (slug) => {
                const doc = sections[slug];
                if (!doc) return null; // hide if not found/unpublished
                return (
                  <SectionRenderer
                    key={slug}
                    slug={slug}
                    title={doc.content?.title}
                    blocks={doc.content?.blocks ?? []}
                    viewCls={viewCls}
                    vm={vm!}
                  />
                );
              }
            )}
          </main>

          {/* Floating 3-dot menu (component) */}
          <FabMenu
            isOpen={menuOpen}
            onOpen={() => setMenuOpen(true)}
            onClose={() => setMenuOpen(false)}
            onNavigate={(key) => {
              setRoute(key as RouteKey);
              setMenuOpen(false);
            }}
            currentRoute={route}   // NEW: keep menu highlight in sync
          />
        </>
      )}
    </>
  );
}

/* ===== Inline QR identical to your SVG ===== */
function QR(size = 180) {
  return (
    <svg width={size} height={size} viewBox="0 0 110 110" xmlns="http://www.w3.org/2000/svg" aria-label="QR">
      <rect width="110" height="110" fill="#fff" />
      <rect x="5" y="5" width="28" height="28" fill="#000" />
      <rect x="77" y="5" width="28" height="28" fill="#000" />
      <rect x="5" y="77" width="28" height="28" fill="#000" />
      <g fill="#000">
        <rect x="45" y="15" width="6" height="6" />
        <rect x="57" y="15" width="6" height="6" />
        <rect x="69" y="15" width="6" height="6" />
        <rect x="45" y="27" width="6" height="6" />
        <rect x="57" y="27" width="6" height="6" />
        <rect x="69" y="27" width="6" height="6" />
        <rect x="45" y="39" width="6" height="6" />
        <rect x="57" y="39" width="6" height="6" />
        <rect x="69" y="39" width="6" height="6" />
        <rect x="33" y="51" width="6" height="6" />
        <rect x="45" y="51" width="6" height="6" />
        <rect x="57" y="51" width="6" height="6" />
        <rect x="69" y="51" width="6" height="6" />
        <rect x="81" y="51" width="6" height="6" />
        <rect x="33" y="63" width="6" height="6" />
        <rect x="45" y="63" width="6" height="6" />
        <rect x="57" y="63" width="6" height="6" />
        <rect x="69" y="63" width="6" height="6" />
        <rect x="81" y="63" width="6" height="6" />
        <rect x="45" y="75" width="6" height="6" />
        <rect x="57" y="75" width="6" height="6" />
        <rect x="69" y="75" width="6" height="6" />
      </g>
      <rect x="9" y="9" width="20" height="20" fill="#fff" />
      <rect x="81" y="9" width="20" height="20" fill="#fff" />
      <rect x="9" y="81" width="20" height="20" fill="#fff" />
      <rect x="12" y="12" width="14" height="14" fill="#000" />
      <rect x="84" y="12" width="14" height="14" fill="#000" />
      <rect x="12" y="84" width="14" height="14" fill="#000" />
    </svg>
  );
}
