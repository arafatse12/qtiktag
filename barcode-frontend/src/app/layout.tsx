export const metadata = {
  title: 'QLIKTAG Product Details',
  description: 'GTIN product identity pages',
};

import './globals.css';

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
