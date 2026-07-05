import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "Winnicode Frontend",
  description: "Next.js frontend for Laravel API",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="id">
      <body>{children}</body>
    </html>
  );
}
