export default function Home() {
  const apiUrl = process.env.NEXT_PUBLIC_API_URL ?? "/api";

  return (
    <main
      style={{
        minHeight: "100vh",
        padding: "48px",
        fontFamily: "Arial, sans-serif",
        background: "#f7f7f7",
      }}
    >
      <section
        style={{
          maxWidth: "760px",
          margin: "0 auto",
          padding: "32px",
          borderRadius: "20px",
          background: "#ffffff",
          boxShadow: "0 10px 30px rgba(0,0,0,0.08)",
        }}
      >
        <p style={{ margin: 0, color: "#666" }}>Laravel API + Next.js</p>
        <h1 style={{ marginTop: "12px", fontSize: "40px" }}>
          Winnicode Next.js Frontend
        </h1>
        <p style={{ fontSize: "18px", lineHeight: 1.7 }}>
          Frontend Next.js berhasil dibuat otomatis oleh Docker.
        </p>
        <p>
          Laravel API:
          <code
            style={{
              display: "inline-block",
              marginLeft: "8px",
              padding: "4px 8px",
              borderRadius: "8px",
              background: "#f0f0f0",
            }}
          >
            {apiUrl}
          </code>
        </p>
      </section>
    </main>
  );
}
