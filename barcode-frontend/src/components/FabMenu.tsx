'use client';

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

const MENU: { key: RouteKey; label: string }[] = [
  { key: 'product', label: 'Product ID' },
  { key: 'manufacturing', label: 'Manufacturing Process' },
  { key: 'materials', label: 'Key Materials & Components' },
  { key: 'custody', label: 'Chain of Custody' },
  { key: 'usage', label: 'Use • Care • Recycle' },
  { key: 'certs', label: 'Certifications' },
  { key: 'sustain', label: 'Sustainability' },
  { key: 'impact', label: 'Environmental Impact' },
];

const ICON_PARTS: Record<RouteKey, JSX.Element> = {
  home: <path d="M3 12l9-9 9 9M5 10v10h14V10" />,
  product: <path d="M3 7v10M6 7v10M10 7v10M14 7v10M17 7v10M21 7v10" />,
  manufacturing: (
    <>
      <circle cx="12" cy="12" r="3.5" />
      <path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M19.4 4.6l-2.1 2.1M6.7 17.3l-2.1 2.1" />
    </>
  ),
  materials: (
    <>
      <path d="M12 3l7 4v6l-7 4-7-4V7l7-4z" />
      <path d="M5 7l7 4 7-4" />
      <path d="M12 11v6" />
    </>
  ),
  custody: (
    <>
      <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0z" />
      <circle cx="12" cy="10" r="3" />
    </>
  ),
  usage: (
    <>
      <path d="M14.5 5a3.5 3.5 0 1 1-5 5L4 15v5h5l5.5-5.5" />
      <path d="M19 12v7l3-1.5 3 1.5v-7" />
    </>
  ),
  certs: <path d="M12 2l2.5 4.9L20 8l-4 3.9L17 18l-5-2.6L7 18l1-6.1L4 8l5.5-.9L12 2z" />,
  sustain: (
    <>
      <path d="M3 21s7-1 11-5 5-11 5-11-7 1-11 5-5 11-5 11z" />
      <path d="M14 10L3 21" />
    </>
  ),
  impact: <path d="M7.5 4c-2 0-3.5 2-3.5 4 0 2.2 1.3 3.3 2.5 4.5 1.1 1.1 1.5 2.5 1.5 4.5h4c0-2.6-.7-4.4-2-5.7 1.2-1.9.5-7.8 2.5-7.8z" />,
};

export default function FabMenu({
  isOpen,
  onOpen,
  onClose,
  onNavigate,
}: {
  isOpen: boolean;
  onOpen: () => void;
  onClose: () => void;
  onNavigate: (key: RouteKey) => void;
}) {
  return (
    <>
      {/* Floating 3-dot (menu trigger) */}
      <button
        className="fab"
        type="button"
        aria-label="Open menu"
        aria-haspopup="dialog"
        aria-expanded={isOpen}
        onClick={onOpen}
      >
        <span className="dots">
          <span />
          <span />
          <span />
        </span>
      </button>

      {/* Bottom-centered modal */}
      <div
        role="dialog"
        aria-modal="true"
        aria-hidden={!isOpen}
        className={`modal bottom-center ${isOpen ? 'show' : ''}`}
        onClick={(e) => {
          if (e.target === e.currentTarget) onClose();
        }}
      >
        {/* ⬇️ required wrapper */}
        <div className="modal-dialog">
          <div className="modal-content sheet">
            <div className="sheet-header">
              <div className="menu-title">Menu</div>
            </div>

            <div className="sheet-body">
              <ul className="menu list-unstyled">
                {MENU.map((m) => (
                  <li key={m.key}>
                    <a
                      href={`#${m.key}`}
                      data-target={m.key}
                      className={`route ${typeof window !== 'undefined' && window.location.hash === '#' + m.key ? 'active' : ''}`}
                      onClick={(e) => {
                        e.preventDefault();
                        onNavigate(m.key);
                      }}
                    >
                      <svg className="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.7">
                        {ICON_PARTS[m.key]}
                      </svg>
                      {m.label}
                    </a>
                  </li>
                ))}
              </ul>
            </div>

            <div className="sheet-footer">
              <button type="button" className="btn-cancel" onClick={onClose}>
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
