{{-- Fallback tanpa build: dipakai hanya bila public/build/manifest.json belum ada.
     Isinya sama dengan resources/css/app.css. Untuk produksi, jalankan `npm run build`. --}}
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4.1.11/dist/index.global.js"></script>
@verbatim
<style type="text/tailwindcss">
@import 'tailwindcss';


/* ==========================================================================
   Sistem desain — sisi marketplace (Tailwind)
   Prinsip: netral hangat, SATU aksen hijau tua, hairline border, bayangan
   tipis, radius tidak berlebihan, tanpa gradien/glow/blob dekoratif.

   Catatan kompatibilitas: nama skala `clay`, `forest`, `ink` dipertahankan
   supaya seluruh view lama tetap bekerja tanpa diedit satu per satu.
     ink    -> netral hangat (teks, garis, permukaan)
     clay   -> aksen utama (hijau tua)
     forest -> hijau sekunder (status sukses / penjual)
     rose   -> merah bata redup (error / hapus)
   ========================================================================== */

@theme {
    /* Tipografi: satu keluarga (Geist) + mono untuk angka/label.
       `font-serif` sengaja dipetakan ke Geist agar judul lama tampil
       konsisten tanpa mengganti 97 pemakaian di view. */
    --font-sans: 'Geist', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    --font-serif: 'Geist', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    --font-mono: 'Geist Mono', ui-monospace, SFMono-Regular, Menlo, monospace;

    /* Bobot lebih halus: "bold" lama terasa berat, turunkan sedikit. */
    --font-weight-semibold: 580;
    --font-weight-bold: 640;

    /* Netral hangat */
    --color-ink-50: #f7f6f3;
    --color-ink-100: #efede8;
    --color-ink-200: #e3e0d9;
    --color-ink-300: #cdc9bf;
    --color-ink-400: #77746c;
    --color-ink-500: #5f5d56;
    --color-ink-600: #4a4843;
    --color-ink-700: #34332f;
    --color-ink-800: #232220;
    --color-ink-900: #171614;

    /* Aksen utama: hijau tua yang tenang */
    --color-clay-50: #f0f6f3;
    --color-clay-100: #deebe5;
    --color-clay-200: #bfd8ce;
    --color-clay-300: #94beaf;
    --color-clay-400: #5e9c88;
    --color-clay-500: #2f7c66;
    --color-clay-600: #1f6652;
    --color-clay-700: #185343;
    --color-clay-800: #134236;
    --color-clay-900: #0e3128;

    /* Hijau sekunder (sukses) */
    --color-forest-50: #f1f6ee;
    --color-forest-100: #e1ecda;
    --color-forest-200: #c5daba;
    --color-forest-300: #a0c28f;
    --color-forest-400: #78a465;
    --color-forest-500: #5a8a47;
    --color-forest-600: #476f38;
    --color-forest-700: #38572d;

    /* Merah bata redup untuk error */
    --color-rose-50: #fbf1f0;
    --color-rose-100: #f6dedc;
    --color-rose-200: #edbfbb;
    --color-rose-300: #e0958f;
    --color-rose-400: #d06b63;
    --color-rose-500: #bc4a41;
    --color-rose-600: #a53a32;
    --color-rose-700: #86302a;

    /* Radius sedikit lebih rapat = kesan lebih dewasa */
    --radius-lg: 0.625rem;
    --radius-xl: 0.75rem;
    --radius-2xl: 1rem;
    --radius-3xl: 1.25rem;

    /* Bayangan tipis berlapis */
    --shadow-soft: 0 1px 2px rgb(23 22 20 / 0.04), 0 1px 1px rgb(23 22 20 / 0.03);
    --shadow-card: 0 1px 2px rgb(23 22 20 / 0.04), 0 10px 28px -14px rgb(23 22 20 / 0.12);
    --shadow-glow: 0 1px 2px rgb(23 22 20 / 0.10), 0 6px 14px -8px rgb(31 102 82 / 0.38);
    --shadow-md: 0 1px 2px rgb(23 22 20 / 0.05), 0 6px 16px -8px rgb(23 22 20 / 0.12);
    --shadow-lg: 0 2px 4px rgb(23 22 20 / 0.04), 0 14px 32px -12px rgb(23 22 20 / 0.16);
    --shadow-xl: 0 2px 6px rgb(23 22 20 / 0.05), 0 22px 44px -16px rgb(23 22 20 / 0.20);
    --shadow-2xl: 0 4px 10px rgb(23 22 20 / 0.06), 0 32px 64px -20px rgb(23 22 20 / 0.24);

    /* Kurva animasi bersama */
    --ease-out: cubic-bezier(0.22, 1, 0.36, 1);
    --ease-in-out: cubic-bezier(0.65, 0, 0.35, 1);
}

/* --------------------------------------------------------------------------
   Pengganti utilitas bergaya "template AI" yang dipakai di view lama.
   Namanya tetap, tampilannya dibuat datar & tenang.
   -------------------------------------------------------------------------- */
@utility gradient-brand {
    background-color: var(--color-clay-600);
}
@utility gradient-mesh {
    background-color: var(--color-ink-50);
}
@utility texture-dots {
    background-image: none;
}
@utility texture-grain {
    background-image: none;
}
@utility glass {
    background-color: color-mix(in oklab, #fff 84%, transparent);
    -webkit-backdrop-filter: saturate(1.3) blur(14px);
    backdrop-filter: saturate(1.3) blur(14px);
}
@utility chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    border-radius: 9999px;
    border: 1px solid var(--color-ink-200);
    background-color: #fff;
    padding: 0.3rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--color-ink-600);
}
@utility btn-tactile {
    transition:
        transform 0.22s var(--ease-out),
        box-shadow 0.22s var(--ease-out),
        background-color 0.2s ease,
        border-color 0.2s ease,
        color 0.2s ease,
        filter 0.2s ease;
    will-change: transform;
    &:active {
        transform: translateY(1px) scale(0.985);
    }
    @media (hover: hover) {
        &:hover {
            transform: translateY(-1px);
        }
    }
}
@utility focus-ring {
    &:focus-visible {
        outline: 2px solid var(--color-clay-400);
        outline-offset: 2px;
    }
}

/* --------------------------------------------------------------------------
   Dasar
   -------------------------------------------------------------------------- */
@layer base {
    html {
        -webkit-text-size-adjust: 100%;
        scroll-behavior: smooth;
    }
    body {
        font-family: var(--font-sans);
        color: var(--color-ink-800);
        background-color: var(--color-ink-50);
        font-feature-settings: 'ss01', 'cv11';
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }
    h1, h2, h3, h4 {
        letter-spacing: -0.022em;
        text-wrap: balance;
    }
    p { text-wrap: pretty; }
    ::selection { background: var(--color-clay-100); color: var(--color-clay-900); }

    /* Form: transisi halus saat fokus */
    input:not([type='checkbox']):not([type='radio']):not([type='range']):not([type='file']),
    select,
    textarea {
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }
    input::placeholder, textarea::placeholder { color: var(--color-ink-300); }
    button, [role='button'], a { -webkit-tap-highlight-color: transparent; }
    :focus-visible { outline: 2px solid var(--color-clay-400); outline-offset: 2px; }

    @media (prefers-reduced-motion: reduce) {
        html { scroll-behavior: auto; }
    }
}

</style>
@endverbatim
