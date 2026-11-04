<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="RADNET-CSIRT — Computer Security Incident Response Team PT Radnet Digital Indonesia. Pusat pelaporan insiden siber, advisori keamanan, dan perlindungan infrastruktur digital 24/7." />
  <meta name="theme-color" content="#ffffff" />
  <title>RADNET-CSIRT | PT Radnet Digital Indonesia</title>
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%23ecfeff'/%3E%3Cpath d='M32 10 15 17v13c0 11 7.2 19.2 17 24 9.8-4.8 17-13 17-24V17L32 10Z' fill='%230e7490'/%3E%3Cpath d='m23.5 31.5 5.4 5.4L41 24.5' fill='none' stroke='%23ffffff' stroke-width='4.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      color-scheme: light;
      --white: #ffffff;
      --slate-50: #f8fafc;
      --slate-100: #f1f5f9;
      --slate-200: #e2e8f0;
      --slate-300: #cbd5e1;
      --slate-400: #94a3b8;
      --slate-500: #64748b;
      --slate-600: #475569;
      --slate-700: #334155;
      --slate-800: #1e293b;
      --slate-900: #0f172a;
      --cyan-50: #ecfeff;
      --cyan-100: #cffafe;
      --cyan-200: #a5f3fc;
      --cyan-600: #0891b2;
      --cyan-700: #0e7490;
      --cyan-800: #155e75;
      --emerald-50: #ecfdf5;
      --emerald-200: #a7f3d0;
      --emerald-600: #059669;
      --emerald-700: #047857;
      --red-50: #fef2f2;
      --red-200: #fecaca;
      --red-600: #dc2626;
      --red-700: #b91c1c;
      --amber-50: #fffbeb;
      --amber-200: #fde68a;
      --amber-700: #b45309;
      --radius-sm: 8px;
      --radius-md: 12px;
      --radius-lg: 16px;
      --shadow-sm: 0 1px 2px rgba(15, 23, 42, .04);
      --shadow-md: 0 12px 32px -18px rgba(15, 23, 42, .18);
      --font-sans: "Inter", "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      --font-display: "Plus Jakarta Sans", "Inter", "Segoe UI", system-ui, sans-serif;
      --font-mono: "JetBrains Mono", "SFMono-Regular", Consolas, monospace;
    }
    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; scroll-padding-top: 96px; -webkit-text-size-adjust: 100%; }
    section { scroll-margin-top: 92px; }
    body {
      margin: 0; background: var(--white); color: var(--slate-900);
      font-family: var(--font-sans); font-size: 16px; line-height: 1.65;
      -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;
    }
    body.modal-open { overflow: hidden; }
    a { color: inherit; text-decoration: none; }
    button, input, select, textarea { font: inherit; }
    button { cursor: pointer; }
    img, svg { display: block; }
    h1, h2, h3, h4, p { margin-top: 0; }
    ul, ol { margin-top: 0; }
    ::selection { background: var(--cyan-100); color: var(--slate-900); }
    :focus-visible { outline: 3px solid var(--cyan-600); outline-offset: 3px; border-radius: 4px; }
    .container { width: min(1140px, calc(100% - 48px)); margin-inline: auto; }
    .sr-only {
      position: absolute !important; width: 1px !important; height: 1px !important;
      padding: 0 !important; margin: -1px !important; overflow: hidden !important;
      clip: rect(0, 0, 0, 0) !important; white-space: nowrap !important; border: 0 !important;
    }
    .skip-link {
      position: fixed; left: 16px; top: -100px; z-index: 999; padding: 10px 16px;
      border-radius: 8px; background: var(--slate-900); color: #fff; font-weight: 700;
      transition: top .2s ease;
    }
    .skip-link:focus { top: 16px; }
    .icon-sprite { position: absolute; width: 0; height: 0; overflow: hidden; }
    .icon {
      width: 20px; height: 20px; flex: 0 0 auto; fill: none; stroke: currentColor;
      stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }
    .icon-sm { width: 16px; height: 16px; }
    .icon-lg { width: 24px; height: 24px; }

    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      min-height: 46px; padding: 11px 19px; border: 1px solid transparent; border-radius: 10px;
      font-size: 14px; font-weight: 700; line-height: 1.25; white-space: nowrap;
      transition: transform .2s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease, color .2s ease;
      will-change: transform;
    }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 24px -14px rgba(15, 23, 42, .45); }
    .btn:active { transform: translateY(0); }
    .btn-primary { background: var(--cyan-700); border-color: var(--cyan-700); color: #fff; }
    .btn-primary:hover { background: var(--cyan-800); border-color: var(--cyan-800); }
    .btn-danger { background: var(--red-600); border-color: var(--red-600); color: #fff; }
    .btn-danger:hover { background: var(--red-700); border-color: var(--red-700); }
    .btn-outline { background: #fff; border-color: var(--slate-300); color: var(--slate-700); }
    .btn-outline:hover { background: var(--cyan-50); border-color: var(--cyan-200); color: var(--cyan-800); }
    .btn-ghost { background: var(--slate-50); border-color: var(--slate-200); color: var(--slate-700); }
    .btn-ghost:hover { background: var(--slate-100); }
    .btn-sm { min-height: 38px; padding: 8px 13px; font-size: 12.5px; border-radius: 8px; }
    .btn-block { width: 100%; }

    .topbar { background: var(--slate-50); border-bottom: 1px solid var(--slate-200); color: var(--slate-600); font-size: 12.5px; }
    .topbar-inner { min-height: 40px; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
    .topbar-links { display: flex; align-items: center; gap: 18px; flex-wrap: wrap; }
    .topbar-link { display: inline-flex; align-items: center; gap: 6px; font-weight: 600; transition: color .18s ease; }
    .topbar-link:hover { color: var(--cyan-700); }
    .topbar-link .icon { color: var(--cyan-700); }
    .topbar-status { display: flex; align-items: center; gap: 10px; white-space: nowrap; }
    .soc-dot {
      width: 7px; height: 7px; border-radius: 50%; background: var(--emerald-600);
      box-shadow: 0 0 0 0 rgba(5, 150, 105, .45); animation: pulseDot 2.2s infinite;
    }
    @keyframes pulseDot {
      0% { box-shadow: 0 0 0 0 rgba(5, 150, 105, .45); }
      70% { box-shadow: 0 0 0 8px rgba(5, 150, 105, 0); }
      100% { box-shadow: 0 0 0 0 rgba(5, 150, 105, 0); }
    }
    .status-text { color: var(--emerald-700); font-weight: 700; }
    .live-clock { font-family: var(--font-mono); font-size: 11.5px; color: var(--slate-500); }

    .security-ticker { background: var(--slate-50); border-bottom: 1px solid var(--slate-200); overflow: hidden; }
    .ticker-inner { min-height: 42px; display: flex; align-items: center; gap: 14px; }
    .ticker-label {
      display: inline-flex; align-items: center; gap: 7px; flex: 0 0 auto;
      color: var(--cyan-800); font-size: 10.5px; font-weight: 800;
      letter-spacing: .08em; text-transform: uppercase; white-space: nowrap;
    }
    .ticker-live { width: 6px; height: 6px; border-radius: 50%; background: var(--emerald-600); }
    .ticker-viewport {
      flex: 1 1 auto; min-width: 0; overflow: hidden;
      -webkit-mask-image: linear-gradient(90deg, transparent, #000 3%, #000 97%, transparent);
      mask-image: linear-gradient(90deg, transparent, #000 3%, #000 97%, transparent);
    }
    .ticker-track { display: flex; width: max-content; will-change: transform; }
    .ticker-track.is-animated { animation: tickerScroll 52s linear infinite; }
    .security-ticker:hover .ticker-track.is-animated,
    .security-ticker:focus-within .ticker-track.is-animated { animation-play-state: paused; }
    .ticker-sequence { display: flex; align-items: center; flex: 0 0 auto; }
    .ticker-item {
      display: inline-flex; align-items: center; gap: 8px; padding: 3px 26px 3px 0;
      color: var(--slate-600); font-size: 12px; font-weight: 500; white-space: nowrap;
      transition: color .18s ease;
    }
    .ticker-item:hover { color: var(--cyan-700); }
    .ticker-item::after { content: ""; width: 4px; height: 4px; margin-left: 18px; border-radius: 50%; background: var(--slate-300); }
    .ticker-text { font-weight: 600; }
    .ticker-item:hover .ticker-text { text-decoration: underline; text-underline-offset: 3px; }
    .ticker-sev { padding: 1px 6px; border-radius: 999px; font-size: 8.5px; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; }
    .ticker-sev.critical { background: var(--red-50); color: var(--red-700); border: 1px solid var(--red-200); }
    .ticker-sev.high { background: var(--amber-50); color: var(--amber-700); border: 1px solid var(--amber-200); }
    .ticker-sev.medium { background: var(--cyan-50); color: var(--cyan-700); border: 1px solid var(--cyan-200); }
    .ticker-meta { color: var(--slate-400); font-family: var(--font-mono); font-size: 9.5px; }
    .ticker-more { flex: 0 0 auto; display: inline-flex; align-items: center; gap: 4px; color: var(--cyan-700); font-size: 11px; font-weight: 700; }
    .ticker-more:hover { color: var(--cyan-800); }
    .ticker-more .icon { width: 12px; height: 12px; transition: transform .18s ease; }
    .ticker-more:hover .icon { transform: translateX(2px); }
    @keyframes tickerScroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }

    .site-header {
      position: sticky; top: 0; z-index: 60; background: rgba(255, 255, 255, .96);
      border-bottom: 1px solid var(--slate-200);
      backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
      transition: box-shadow .2s ease;
    }
    .site-header.scrolled { box-shadow: 0 8px 24px -18px rgba(15, 23, 42, .25); }
    .navbar { min-height: 72px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .brand { display: flex; align-items: center; gap: 11px; min-width: 0; }
    .brand-mark {
      width: 40px; height: 40px; flex: 0 0 auto; display: grid; place-items: center;
      border-radius: 10px; background: var(--cyan-700); color: #fff;
    }
    .brand-mark svg { width: 23px; height: 23px; }
    .brand-text { min-width: 0; }
    .brand-name {
      display: block; font-family: var(--font-display); font-size: 19px; font-weight: 800;
      line-height: 1.1; letter-spacing: -.03em; color: var(--slate-900); white-space: nowrap;
    }
    .brand-name span { color: var(--cyan-700); }
    .brand-sub { display: block; margin-top: 2px; color: var(--slate-500); font-size: 10.5px; font-weight: 600; white-space: nowrap; }
    .nav-links { display: flex; align-items: center; gap: 4px; }
    .nav-link {
      position: relative; display: inline-flex; align-items: center; padding: 8px 11px;
      border: 1px solid transparent; border-radius: 8px;
      color: var(--slate-600); font-size: 13px; font-weight: 600; white-space: nowrap;
      transition: background-color .18s ease, color .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }
    .nav-link:hover { background: var(--slate-50); border-color: var(--slate-200); color: var(--cyan-800); transform: translateY(-1px); }
    .nav-link:active { transform: translateY(0) scale(.98); }
    .nav-link.active {
      background: var(--cyan-50); border-color: var(--cyan-200); color: var(--cyan-800);
      box-shadow: inset 0 -3px 0 var(--cyan-700), 0 1px 2px rgba(15, 23, 42, .04);
    }
    .nav-link.active:hover { background: var(--cyan-100); border-color: var(--cyan-200); }
    .nav-actions { display: flex; align-items: center; gap: 9px; }
    .mobile-toggle {
      display: none; width: 42px; height: 42px; align-items: center; justify-content: center;
      border: 1px solid var(--slate-300); border-radius: 9px; background: #fff; color: var(--slate-700);
    }
    .mobile-toggle:hover { background: var(--slate-50); }
    .mobile-drawer { display: none; border-top: 1px solid var(--slate-200); background: #fff; padding: 8px 0 16px; }
    .mobile-drawer.open { display: block; }
    .mobile-nav { display: grid; gap: 2px; }
    .mobile-link {
      display: flex; align-items: center; justify-content: space-between; padding: 11px 14px;
      border: 1px solid transparent; border-radius: 8px;
      color: var(--slate-700); font-size: 14px; font-weight: 600;
      transition: background-color .18s ease, color .18s ease, border-color .18s ease, box-shadow .18s ease;
    }
    .mobile-link:hover { background: var(--slate-50); border-color: var(--slate-200); color: var(--cyan-800); }
    .mobile-link.active {
      background: var(--cyan-50); border-color: var(--cyan-200); color: var(--cyan-800);
      font-weight: 700; box-shadow: inset 3px 0 0 var(--cyan-700);
    }
    .mobile-link .icon { color: var(--slate-400); }
    .mobile-link.active .icon { color: var(--cyan-700); }
    .mobile-cta { margin-top: 8px; }

    .section { padding: 84px 0; }
    .section-alt { background: var(--slate-50); border-top: 1px solid var(--slate-200); border-bottom: 1px solid var(--slate-200); }
    .section-head { max-width: 720px; margin-bottom: 38px; }
    .section-head.center { margin-inline: auto; text-align: center; }
    .eyebrow {
      display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px;
      color: var(--cyan-700); font-size: 11.5px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase;
    }
    .eyebrow::before { content: ""; width: 22px; height: 2px; border-radius: 2px; background: var(--cyan-600); }
    .section-title {
      margin-bottom: 12px; font-family: var(--font-display); font-size: clamp(1.9rem, 3vw, 2.4rem);
      line-height: 1.15; letter-spacing: -.035em; font-weight: 800; color: var(--slate-900);
    }
    .section-title .accent { color: var(--cyan-700); }
    .section-desc { margin: 0; max-width: 680px; color: var(--slate-600); font-size: 15.5px; line-height: 1.75; }
    .section-head.center .section-desc { margin-inline: auto; }
    .muted { color: var(--slate-500); }
    .reveal { opacity: 1; transform: none; }
    .js .reveal { opacity: 0; transform: translateY(14px); transition: opacity .55s ease, transform .55s ease; }
    .js .reveal.is-visible { opacity: 1; transform: translateY(0); }

    .hero { padding: 72px 0 60px; background: linear-gradient(180deg, #fff 0%, var(--slate-50) 100%); border-bottom: 1px solid var(--slate-200); }
    .hero-grid { display: grid; grid-template-columns: 1.12fr .88fr; gap: 48px; align-items: center; }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 7px; margin-bottom: 18px; padding: 6px 11px;
      border: 1px solid var(--cyan-200); border-radius: 999px; background: var(--cyan-50);
      color: var(--cyan-800); font-size: 11.5px; font-weight: 700;
    }
    .hero-title {
      margin-bottom: 18px; font-family: var(--font-display); font-size: clamp(2.25rem, 4.2vw, 3.5rem);
      line-height: 1.08; letter-spacing: -.045em; font-weight: 800; color: var(--slate-900);
    }
    .hero-title .accent { color: var(--cyan-700); }
    .hero-desc { max-width: 590px; margin-bottom: 26px; color: var(--slate-600); font-size: 16.5px; line-height: 1.8; }
    .hero-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 24px; }
    .hero-points { display: flex; flex-wrap: wrap; gap: 10px 20px; margin: 0; padding: 0; list-style: none; color: var(--slate-600); font-size: 12.5px; font-weight: 600; }
    .hero-points li { display: inline-flex; align-items: center; gap: 6px; }
    .hero-points .icon { color: var(--emerald-600); }
    .quick-card {
      padding: 26px; border: 1px solid var(--slate-200); border-radius: var(--radius-lg);
      background: #fff; box-shadow: var(--shadow-md);
      transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .quick-card:hover { transform: translateY(-3px); border-color: var(--cyan-200); box-shadow: 0 18px 40px -24px rgba(15, 23, 42, .3); }
    .quick-kicker { display: block; margin-bottom: 6px; color: var(--cyan-700); font-size: 10.5px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
    .quick-card h2 { margin-bottom: 7px; font-family: var(--font-display); font-size: 21px; line-height: 1.3; letter-spacing: -.02em; font-weight: 800; }
    .quick-card p { margin-bottom: 16px; color: var(--slate-600); font-size: 13px; line-height: 1.65; }
    .quick-links { display: grid; gap: 8px; margin-bottom: 16px; }
    .quick-link {
      display: flex; align-items: center; gap: 10px; padding: 10px 12px;
      border: 1px solid var(--slate-200); border-radius: 9px; background: var(--slate-50);
      transition: border-color .18s ease, background .18s ease, transform .18s ease;
    }
    .quick-link:hover { border-color: var(--cyan-200); background: #fff; transform: translateX(2px); }
    .quick-link-icon { display: grid; place-items: center; width: 32px; height: 32px; flex: 0 0 auto; border-radius: 8px; background: var(--cyan-50); color: var(--cyan-700); }
    .quick-link-text { display: block; min-width: 0; }
    .quick-link-text span { display: block; color: var(--slate-500); font-size: 10px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; }
    .quick-link-text strong { display: block; margin-top: 1px; color: var(--slate-800); font-size: 13px; font-weight: 700; }
    .quick-status { display: flex; align-items: center; gap: 7px; color: var(--emerald-700); font-size: 11.5px; font-weight: 700; }

    .fact-strip {
      display: grid; grid-template-columns: repeat(3, 1fr); margin-top: -28px; position: relative; z-index: 2;
      border: 1px solid var(--slate-200); border-radius: var(--radius-md); background: #fff;
      box-shadow: 0 14px 34px -24px rgba(15, 23, 42, .25); overflow: hidden;
    }
    .fact { padding: 19px 24px; }
    .fact + .fact { border-left: 1px solid var(--slate-200); }
    .fact strong { display: block; font-family: var(--font-display); font-size: 18px; line-height: 1.25; letter-spacing: -.02em; color: var(--slate-900); }
    .fact span { display: block; margin-top: 2px; color: var(--slate-500); font-size: 12px; }

    .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: start; }
    .about-copy p { color: var(--slate-600); font-size: 14.5px; line-height: 1.8; }
    .about-copy p:last-child { margin-bottom: 0; }
    .about-points { display: grid; gap: 10px; margin: 22px 0 0; padding: 0; list-style: none; }
    .about-point { display: flex; gap: 10px; align-items: flex-start; color: var(--slate-600); font-size: 13px; line-height: 1.6; }
    .about-point .icon { width: 16px; height: 16px; margin-top: 2px; color: var(--emerald-600); flex: 0 0 auto; }
    .info-card { padding: 26px; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); background: #fff; box-shadow: var(--shadow-sm); }
    .info-card h3 { margin-bottom: 4px; font-family: var(--font-display); font-size: 18px; font-weight: 800; letter-spacing: -.02em; }
    .info-card > p { margin-bottom: 18px; color: var(--slate-500); font-size: 12.5px; }
    .info-row { display: grid; grid-template-columns: 116px 1fr; gap: 12px; padding: 10px 0; border-top: 1px solid var(--slate-100); font-size: 12.5px; line-height: 1.55; }
    .info-row span { color: var(--slate-400); font-weight: 700; }
    .info-row strong { color: var(--slate-700); font-weight: 600; }
    .info-note { display: flex; gap: 8px; margin-top: 18px; padding: 12px 13px; border-radius: 9px; background: var(--cyan-50); color: var(--cyan-800); font-size: 12px; line-height: 1.55; font-weight: 500; }
    .info-note .icon { color: var(--cyan-700); margin-top: 1px; }

    .service-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .service-card {
      padding: 24px; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); background: #fff;
      box-shadow: var(--shadow-sm); transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .service-card:hover { transform: translateY(-3px); border-color: var(--cyan-200); box-shadow: 0 16px 36px -22px rgba(15, 23, 42, .28); }
    .service-icon { width: 44px; height: 44px; display: grid; place-items: center; margin-bottom: 14px; border-radius: 10px; background: var(--cyan-50); color: var(--cyan-700); }
    .service-icon .icon { width: 22px; height: 22px; }
    .service-card h3 { margin-bottom: 6px; font-family: var(--font-display); font-size: 17px; line-height: 1.35; letter-spacing: -.015em; font-weight: 800; }
    .service-card p { margin: 0; color: var(--slate-600); font-size: 13px; line-height: 1.7; }

    .report-grid { display: grid; grid-template-columns: .85fr 1.15fr; gap: 30px; align-items: start; }
    .report-aside { display: grid; gap: 14px; }
    .help-card { padding: 22px; border: 1px solid var(--red-200); border-radius: var(--radius-lg); background: #fff; box-shadow: var(--shadow-sm); }
    .help-badge { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 10px; color: var(--red-700); font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .help-card h3 { margin-bottom: 6px; font-family: var(--font-display); font-size: 19px; letter-spacing: -.02em; font-weight: 800; }
    .help-card > p { margin-bottom: 14px; color: var(--slate-600); font-size: 12.5px; line-height: 1.65; }
    .help-links { display: grid; gap: 8px; }
    .help-link { display: flex; align-items: center; gap: 9px; padding: 9px 11px; border: 1px solid var(--slate-200); border-radius: 8px; background: var(--slate-50); color: var(--slate-700); font-size: 12.5px; font-weight: 700; transition: border-color .18s ease, background .18s ease; }
    .help-link:hover { border-color: var(--red-200); background: var(--red-50); color: var(--red-700); }
    .help-link .icon { color: var(--red-600); }
    .checklist-card { padding: 20px 22px; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); background: #fff; }
    .checklist-card h3 { margin-bottom: 10px; font-family: var(--font-display); font-size: 14.5px; font-weight: 800; }
    .checklist { display: grid; gap: 8px; margin: 0; padding: 0; list-style: none; }
    .checklist li { display: flex; gap: 8px; align-items: flex-start; color: var(--slate-600); font-size: 12px; line-height: 1.55; }
    .checklist .icon { width: 14px; height: 14px; margin-top: 2px; color: var(--cyan-700); flex: 0 0 auto; }
    .privacy-note { display: flex; gap: 8px; padding: 12px 13px; border-radius: 9px; background: var(--cyan-50); color: var(--cyan-800); font-size: 11.5px; line-height: 1.55; }
    .privacy-note .icon { color: var(--cyan-700); margin-top: 1px; flex: 0 0 auto; }

    .form-card { border: 1px solid var(--slate-200); border-radius: var(--radius-lg); background: #fff; box-shadow: var(--shadow-md); overflow: hidden; }
    .form-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; padding: 22px 24px 18px; border-bottom: 1px solid var(--slate-200); background: var(--slate-50); }
    .form-kicker { display: block; margin-bottom: 4px; color: var(--cyan-700); font-size: 10px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
    .form-head h3 { margin-bottom: 4px; font-family: var(--font-display); font-size: 21px; line-height: 1.25; letter-spacing: -.025em; font-weight: 800; }
    .form-head p { margin: 0; color: var(--slate-500); font-size: 12px; line-height: 1.5; }
    .secure-badge { display: inline-flex; align-items: center; gap: 5px; flex: 0 0 auto; padding: 4px 8px; border: 1px solid var(--emerald-200); border-radius: 6px; background: var(--emerald-50); color: var(--emerald-700); font-size: 9.5px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; }
    .incident-form { padding: 22px 24px 24px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .field-group { margin-bottom: 15px; }
    .field-group label, .field-group legend { display: block; margin-bottom: 5px; color: var(--slate-800); font-size: 12.5px; line-height: 1.4; font-weight: 700; }
    .req { color: var(--red-600); }
    .field { display: block; width: 100%; min-height: 44px; padding: 10px 12px; border: 1px solid var(--slate-300); border-radius: 8px; background: #fff; color: var(--slate-900); font-size: 13.5px; line-height: 1.4; transition: border-color .18s ease, box-shadow .18s ease; }
    .field::placeholder { color: var(--slate-400); opacity: 1; }
    .field:hover { border-color: var(--slate-400); }
    .field:focus { outline: none; border-color: var(--cyan-600); box-shadow: 0 0 0 3px rgba(8, 145, 178, .12); }
    select.field { appearance: none; -webkit-appearance: none; padding-right: 36px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; }
    textarea.field { min-height: 116px; resize: vertical; }
    .field-meta { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 4px; color: var(--slate-400); font-size: 10.5px; }
    .counter { font-family: var(--font-mono); }
    .field-error { display: none; margin-top: 4px; color: var(--red-700); font-size: 11px; line-height: 1.4; font-weight: 600; }
    .field-group.invalid .field { border-color: var(--red-600); box-shadow: 0 0 0 3px rgba(220, 38, 38, .08); }
    .field-group.invalid .field-error { display: block; }
    .severity-group { margin: 0 0 15px; padding: 0; border: 0; }
    .severity-options { display: grid; grid-template-columns: repeat(4, 1fr); gap: 7px; }
    .sev-option { position: relative; display: block; cursor: pointer; }
    .sev-option input { position: absolute; opacity: 0; pointer-events: none; }
    .sev-option span { display: block; padding: 8px 6px; border: 1px solid var(--slate-300); border-radius: 8px; background: #fff; text-align: center; transition: border-color .18s ease, background .18s ease, box-shadow .18s ease; }
    .sev-option b { display: block; color: var(--slate-700); font-size: 11.5px; line-height: 1.3; }
    .sev-option small { display: block; margin-top: 1px; color: var(--slate-400); font-size: 9px; line-height: 1.3; }
    .sev-option:hover span { border-color: var(--slate-400); }
    .sev-option input:checked + span { border-color: var(--cyan-600); background: var(--cyan-50); box-shadow: inset 0 0 0 1px var(--cyan-600); }
    .sev-option[data-level="High"] input:checked + span { border-color: var(--amber-700); background: var(--amber-50); box-shadow: inset 0 0 0 1px var(--amber-700); }
    .sev-option[data-level="Critical"] input:checked + span { border-color: var(--red-600); background: var(--red-50); box-shadow: inset 0 0 0 1px var(--red-600); }
    .severity-group.invalid .field-error { display: block; }
    .file-drop { display: flex; align-items: center; gap: 11px; padding: 13px; border: 1.5px dashed var(--slate-300); border-radius: 9px; background: var(--slate-50); cursor: pointer; transition: border-color .18s ease, background .18s ease; }
    .file-drop:hover { border-color: var(--cyan-600); background: var(--cyan-50); }
    .file-drop-icon { width: 34px; height: 34px; flex: 0 0 auto; display: grid; place-items: center; border-radius: 8px; background: #fff; color: var(--cyan-700); border: 1px solid var(--slate-200); }
    .file-drop-copy { min-width: 0; }
    .file-drop-copy strong { display: block; color: var(--slate-700); font-size: 12px; line-height: 1.4; }
    .file-drop-copy span { display: block; margin-top: 2px; color: var(--slate-400); font-size: 10px; }
    .file-drop input { position: absolute; width: 1px; height: 1px; opacity: 0; overflow: hidden; }
    .consent-row { display: flex; gap: 9px; align-items: flex-start; margin: 4px 0 16px; }
    .consent-row input { width: 16px; height: 16px; margin: 2px 0 0; accent-color: var(--cyan-700); flex: 0 0 auto; }
    .consent-row label { color: var(--slate-600); font-size: 11.5px; line-height: 1.55; }
    .consent-row .field-error { display: none; margin-top: 3px; }
    .consent-row.invalid .field-error { display: block; }
    .submit-btn { position: relative; }
    .spinner { display: none; width: 16px; height: 16px; border: 2px solid rgba(255, 255, 255, .35); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }
    .submit-btn.loading .spinner { display: block; }
    .submit-btn.loading .submit-arrow { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .form-footnote { margin: 10px 0 0; color: var(--slate-400); font-size: 10.5px; text-align: center; }

    .toolbar { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 16px; flex-wrap: wrap; }
    .search-box { position: relative; flex: 1 1 280px; max-width: 380px; }
    .search-box .icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; color: var(--slate-400); pointer-events: none; }
    .search-box .field { padding-left: 38px; }
    .filter-group { display: flex; flex-wrap: wrap; gap: 6px; }
    .filter-btn { padding: 7px 12px; border: 1px solid var(--slate-300); border-radius: 999px; background: #fff; color: var(--slate-600); font-size: 11.5px; font-weight: 700; transition: all .18s ease; }
    .filter-btn:hover { border-color: var(--cyan-200); background: var(--cyan-50); color: var(--cyan-800); }
    .filter-btn.active { border-color: var(--cyan-700); background: var(--cyan-700); color: #fff; }
    .advisory-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 16px; color: var(--slate-500); font-size: 11.5px; }
    .advisory-meta strong { color: var(--slate-700); }
    .advisory-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .advisory-card { display: flex; flex-direction: column; padding: 18px; border: 1px solid var(--slate-200); border-radius: var(--radius-md); background: #fff; box-shadow: var(--shadow-sm); transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
    .advisory-card:hover { transform: translateY(-3px); border-color: var(--cyan-200); box-shadow: 0 14px 32px -22px rgba(15, 23, 42, .3); }
    .advisory-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 10px; }
    .sev-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 7px; border: 1px solid transparent; border-radius: 5px; font-size: 9px; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; }
    .sev-badge .dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
    .sev-critical { border-color: var(--red-200); background: var(--red-50); color: var(--red-700); }
    .sev-high { border-color: var(--amber-200); background: var(--amber-50); color: var(--amber-700); }
    .sev-medium { border-color: var(--cyan-200); background: var(--cyan-50); color: var(--cyan-700); }
    .advisory-date { color: var(--slate-400); font-family: var(--font-mono); font-size: 9px; white-space: nowrap; padding-top: 2px; }
    .advisory-id { margin-bottom: 6px; color: var(--slate-400); font-family: var(--font-mono); font-size: 9px; line-height: 1.4; }
    .advisory-card h3 { margin-bottom: 6px; font-family: var(--font-display); font-size: 14.5px; line-height: 1.4; letter-spacing: -.01em; font-weight: 800; }
    .advisory-card p { margin-bottom: 13px; color: var(--slate-600); font-size: 11.5px; line-height: 1.65; }
    .advisory-foot { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: auto; padding-top: 11px; border-top: 1px solid var(--slate-100); }
    .advisory-cat { padding: 2px 6px; border-radius: 4px; background: var(--slate-100); color: var(--slate-500); font-size: 9px; font-weight: 700; white-space: nowrap; }
    .advisory-link { display: inline-flex; align-items: center; gap: 3px; padding: 0; border: 0; background: transparent; color: var(--cyan-700); font-size: 10.5px; font-weight: 800; }
    .advisory-link:hover { color: var(--cyan-800); }
    .advisory-link .icon { width: 12px; height: 12px; transition: transform .18s ease; }
    .advisory-link:hover .icon { transform: translateX(2px); }
    .advisory-empty { display: none; padding: 48px 24px; border: 1.5px dashed var(--slate-300); border-radius: var(--radius-md); background: rgba(255, 255, 255, .65); text-align: center; }
    .advisory-empty.show { display: block; }
    .advisory-empty-icon { width: 48px; height: 48px; margin: 0 auto 12px; display: grid; place-items: center; border-radius: 12px; background: var(--slate-100); color: var(--slate-500); }
    .advisory-empty h3 { margin-bottom: 4px; font-family: var(--font-display); font-size: 17px; font-weight: 800; }
    .advisory-empty p { margin: 0; color: var(--slate-500); font-size: 12.5px; }

    .docs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .doc-card { border: 1px solid var(--slate-200); border-radius: var(--radius-lg); background: #fff; box-shadow: var(--shadow-sm); overflow: hidden; }
    .doc-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 18px 20px; border-bottom: 1px solid var(--slate-200); }
    .doc-title-wrap { display: flex; align-items: center; gap: 10px; }
    .doc-icon { width: 38px; height: 38px; flex: 0 0 auto; display: grid; place-items: center; border-radius: 9px; background: var(--cyan-50); color: var(--cyan-700); }
    .doc-icon.green { background: var(--emerald-50); color: var(--emerald-700); }
    .doc-title-wrap h3 { margin: 0 0 1px; font-family: var(--font-display); font-size: 15.5px; font-weight: 800; }
    .doc-title-wrap p { margin: 0; color: var(--slate-500); font-size: 10.5px; }
    .doc-status { display: inline-flex; align-items: center; gap: 4px; padding: 3px 7px; border: 1px solid var(--emerald-200); border-radius: 5px; background: var(--emerald-50); color: var(--emerald-700); font-size: 8.5px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; }
    .doc-body { padding: 18px 20px 20px; }
    .rfc-list { margin: 0 0 16px; padding: 0; list-style: none; }
    .rfc-row { display: grid; grid-template-columns: 106px 1fr; gap: 10px; padding: 7px 0; border-bottom: 1px solid var(--slate-100); font-size: 12px; }
    .rfc-row:first-child { border-top: 1px solid var(--slate-100); }
    .rfc-row dt { color: var(--slate-400); font-weight: 700; }
    .rfc-row dd { margin: 0; color: var(--slate-700); }
    .doc-actions { display: flex; flex-wrap: wrap; gap: 7px; }
    .fingerprint { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 11px; padding: 9px 10px; border: 1px solid var(--slate-200); border-radius: 8px; background: var(--slate-50); }
    .fingerprint .icon { width: 14px; height: 14px; color: var(--cyan-700); flex: 0 0 auto; margin-top: 2px; }
    .fingerprint span { display: block; color: var(--slate-400); font-size: 8.5px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; }
    .fingerprint code { display: block; margin-top: 1px; color: var(--slate-700); font-family: var(--font-mono); font-size: 9.5px; line-height: 1.5; word-break: break-word; }
    .pgp-key-box { max-height: 190px; overflow: auto; margin-bottom: 11px; padding: 12px; border: 1px solid var(--slate-200); border-radius: 8px; background: var(--slate-50); }
    .pgp-key { margin: 0; color: var(--slate-600); font-family: var(--font-mono); font-size: 9px; line-height: 1.6; white-space: pre-wrap; word-break: break-all; }
    .pgp-actions { display: flex; flex-wrap: wrap; gap: 7px; align-items: center; }
    .tooltip-wrap { position: relative; display: inline-flex; }
    .copy-tooltip { position: absolute; right: 0; bottom: calc(100% + 7px); z-index: 5; padding: 5px 8px; border-radius: 5px; background: var(--slate-900); color: #fff; font-size: 10px; font-weight: 600; white-space: nowrap; opacity: 0; transform: translateY(3px); pointer-events: none; transition: opacity .18s ease, transform .18s ease; }
    .copy-tooltip::after { content: ""; position: absolute; top: 100%; right: 14px; border: 4px solid transparent; border-top-color: var(--slate-900); }
    .tooltip-wrap.show .copy-tooltip { opacity: 1; transform: translateY(0); }
    .pgp-note { display: flex; gap: 6px; align-items: flex-start; margin: 11px 0 0; color: var(--slate-500); font-size: 10px; line-height: 1.5; }
    .pgp-note .icon { width: 12px; height: 12px; flex: 0 0 auto; margin-top: 1px; color: var(--emerald-600); }

    .office-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 16px; }
    .office-card { padding: 20px; border: 1px solid var(--slate-200); border-radius: var(--radius-md); background: #fff; box-shadow: var(--shadow-sm); transition: transform .2s ease, border-color .2s ease; }
    .office-card:hover { transform: translateY(-2px); border-color: var(--cyan-200); }
    .office-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 9px; }
    .office-city { display: flex; align-items: center; gap: 6px; font-family: var(--font-display); font-size: 15px; font-weight: 800; }
    .office-city .icon { color: var(--cyan-700); }
    .office-hq { padding: 2px 6px; border-radius: 4px; background: var(--cyan-50); color: var(--cyan-800); font-size: 8px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .office-card p { margin: 0; color: var(--slate-600); font-size: 12px; line-height: 1.65; }
    .office-card p + p { margin-top: 6px; }
    .office-card a { color: var(--cyan-700); font-weight: 600; }
    .office-card a:hover { text-decoration: underline; text-underline-offset: 2px; }
    .contact-bar { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1px; border: 1px solid var(--slate-200); border-radius: var(--radius-md); background: var(--slate-200); overflow: hidden; }
    .contact-item { display: flex; align-items: flex-start; gap: 9px; padding: 16px 17px; background: #fff; }
    .contact-item .icon { color: var(--cyan-700); margin-top: 2px; }
    .contact-item span { display: block; color: var(--slate-400); font-size: 9.5px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .contact-item strong { display: block; margin-top: 2px; color: var(--slate-800); font-size: 12px; line-height: 1.45; }
    .contact-item a { color: var(--slate-800); font-weight: 700; }
    .contact-item a:hover { color: var(--cyan-700); }

    .site-footer { background: var(--slate-50); border-top: 1px solid var(--slate-200); }
    .footer-main { padding: 48px 0 34px; }
    .footer-inner { display: grid; grid-template-columns: 1.5fr 1fr 1.2fr; gap: 36px; }
    .footer-brand .brand { margin-bottom: 11px; }
    .footer-brand p { max-width: 340px; margin-bottom: 12px; color: var(--slate-500); font-size: 12px; line-height: 1.7; }
    .footer-social { display: flex; flex-wrap: wrap; gap: 7px; }
    .footer-social a { display: inline-flex; align-items: center; gap: 4px; padding: 4px 7px; border: 1px solid var(--slate-200); border-radius: 5px; background: #fff; color: var(--slate-500); font-size: 10px; font-weight: 600; transition: color .18s ease, border-color .18s ease; }
    .footer-social a:hover { border-color: var(--cyan-200); color: var(--cyan-700); }
    .footer-social .icon { width: 11px; height: 11px; }
    .footer-col h3 { margin: 2px 0 11px; color: var(--slate-400); font-size: 10px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
    .footer-links { display: grid; gap: 7px; margin: 0; padding: 0; list-style: none; }
    .footer-links a { display: inline-flex; align-items: center; gap: 5px; color: var(--slate-600); font-size: 12px; transition: color .16s ease; }
    .footer-links a:hover { color: var(--cyan-700); }
    .footer-links .icon { width: 11px; height: 11px; color: var(--slate-300); }
    .footer-contact-list { display: grid; gap: 8px; margin: 0; padding: 0; list-style: none; }
    .footer-contact-list li { display: flex; gap: 6px; align-items: flex-start; color: var(--slate-600); font-size: 11.5px; line-height: 1.5; }
    .footer-contact-list .icon { width: 12px; height: 12px; margin-top: 2px; color: var(--cyan-700); flex: 0 0 auto; }
    .footer-contact-list a:hover { color: var(--cyan-700); text-decoration: underline; text-underline-offset: 2px; }
    .footer-bottom { border-top: 1px solid var(--slate-200); background: #fff; }
    .footer-bottom-inner { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 15px 0; color: var(--slate-500); font-size: 10.5px; }
    .footer-bottom-inner p { margin: 0; }
    .footer-bottom-links { display: flex; flex-wrap: wrap; gap: 13px; }
    .footer-bottom-links a { color: var(--slate-500); font-weight: 600; }
    .footer-bottom-links a:hover { color: var(--cyan-700); }

    .back-to-top { position: fixed; right: 18px; bottom: 18px; z-index: 50; width: 40px; height: 40px; display: grid; place-items: center; border: 1px solid var(--slate-300); border-radius: 9px; background: rgba(255, 255, 255, .97); color: var(--slate-600); box-shadow: var(--shadow-md); opacity: 0; transform: translateY(6px); pointer-events: none; transition: opacity .2s ease, transform .2s ease, color .2s ease, border-color .2s ease; }
    .back-to-top.visible { opacity: 1; transform: translateY(0); pointer-events: auto; }
    .back-to-top:hover { color: var(--cyan-700); border-color: var(--cyan-200); }

    .toast { position: fixed; left: 50%; bottom: 24px; z-index: 160; display: flex; align-items: center; gap: 7px; max-width: calc(100% - 32px); padding: 10px 14px; border: 1px solid var(--slate-700); border-radius: 8px; background: var(--slate-900); color: #fff; box-shadow: 0 12px 30px rgba(15, 23, 42, .24); font-size: 11.5px; font-weight: 600; opacity: 0; transform: translate(-50%, 10px); pointer-events: none; transition: opacity .22s ease, transform .22s ease; }
    .toast.show { opacity: 1; transform: translate(-50%, 0); }
    .toast .icon { color: #34d399; }

    .modal { position: fixed; inset: 0; z-index: 120; display: none; align-items: center; justify-content: center; padding: 18px; }
    .modal.open { display: flex; }
    .modal-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, .56); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
    .modal-card { position: relative; z-index: 1; width: min(600px, 100%); max-height: min(90vh, 800px); overflow: auto; padding: 28px; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); background: #fff; box-shadow: 0 30px 80px rgba(15, 23, 42, .28); animation: modalIn .22s ease-out both; }
    @keyframes modalIn { from { opacity: 0; transform: translateY(12px) scale(.985); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .modal-close { position: absolute; top: 12px; right: 12px; width: 34px; height: 34px; display: grid; place-items: center; border: 1px solid var(--slate-200); border-radius: 8px; background: #fff; color: var(--slate-500); transition: background .16s ease, color .16s ease; }
    .modal-close:hover { background: var(--slate-50); color: var(--slate-900); }
    .modal-success-icon { width: 60px; height: 60px; margin: 2px auto 15px; display: grid; place-items: center; border: 1px solid var(--emerald-200); border-radius: 50%; background: var(--emerald-50); color: var(--emerald-700); }
    .modal-success-icon .icon { width: 30px; height: 30px; stroke-width: 2.2; }
    .modal-title { margin-bottom: 6px; text-align: center; font-family: var(--font-display); font-size: 23px; line-height: 1.2; letter-spacing: -.03em; font-weight: 800; }
    .modal-subtitle { max-width: 440px; margin: 0 auto 18px; text-align: center; color: var(--slate-600); font-size: 12.5px; line-height: 1.65; }
    .ticket-box { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 14px; padding: 13px 14px; border: 1px solid var(--cyan-200); border-radius: 9px; background: var(--cyan-50); }
    .ticket-left span { display: block; color: var(--cyan-700); font-size: 9px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .ticket-left strong { display: block; margin-top: 1px; color: var(--cyan-800); font-family: var(--font-mono); font-size: 17px; line-height: 1.3; }
    .ticket-copy { display: inline-flex; align-items: center; gap: 4px; min-height: 32px; padding: 5px 9px; border: 1px solid var(--cyan-200); border-radius: 6px; background: #fff; color: var(--cyan-700); font-size: 10px; font-weight: 700; }
    .ticket-copy:hover { border-color: var(--cyan-600); background: var(--cyan-50); }
    .summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 9px; margin: 0 0 14px; padding: 12px; border: 1px solid var(--slate-200); border-radius: 9px; background: var(--slate-50); }
    .summary-grid div { min-width: 0; }
    .summary-grid dt { margin-bottom: 1px; color: var(--slate-400); font-size: 8.5px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .summary-grid dd { margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--slate-800); font-size: 11px; font-weight: 600; }
    .summary-grid dd.mono { font-family: var(--font-mono); font-size: 10px; }
    .modal-note { display: flex; gap: 8px; align-items: flex-start; padding: 10px 12px; border: 1px solid var(--slate-200); border-radius: 8px; color: var(--slate-600); font-size: 11px; line-height: 1.55; }
    .modal-note .icon { color: var(--cyan-700); flex: 0 0 auto; margin-top: 1px; }
    .modal-actions { display: flex; gap: 8px; margin-top: 16px; }
    .modal-actions .btn { flex: 1; }
    .advisory-modal-card { width: min(680px, 100%); }
    .advisory-modal-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; margin-bottom: 11px; padding-right: 32px; }
    .advisory-modal-id { color: var(--slate-400); font-family: var(--font-mono); font-size: 9px; }
    .advisory-modal-title { margin-bottom: 8px; font-family: var(--font-display); font-size: clamp(1.35rem, 2.8vw, 1.7rem); line-height: 1.25; letter-spacing: -.03em; font-weight: 800; }
    .advisory-modal-info { display: flex; flex-wrap: wrap; gap: 7px 14px; margin-bottom: 16px; color: var(--slate-500); font-size: 10.5px; font-weight: 600; }
    .advisory-modal-info span { display: inline-flex; align-items: center; gap: 4px; }
    .advisory-modal-info .icon { width: 12px; height: 12px; color: var(--cyan-700); }
    .advisory-detail { margin-bottom: 14px; padding: 13px 14px; border: 1px solid var(--slate-200); border-radius: 9px; background: var(--slate-50); }
    .advisory-detail h4 { margin: 0 0 4px; color: var(--cyan-700); font-size: 9px; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
    .advisory-detail p { margin: 0; color: var(--slate-700); font-size: 11.5px; line-height: 1.7; }
    .advisory-detail.recommendation { border-color: var(--emerald-200); background: var(--emerald-50); }
    .advisory-detail.recommendation h4 { color: var(--emerald-700); }

    @media (prefers-reduced-motion: reduce) {
      html { scroll-behavior: auto; }
      *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
      .js .reveal { opacity: 1; transform: none; }
      .ticker-track.is-animated { animation: none; }
      .ticker-viewport { overflow-x: auto; -webkit-mask-image: none; mask-image: none; }
    }
    @media (max-width: 1080px) {
      .nav-links { display: none; }
      .mobile-toggle { display: inline-flex; }
      .hero-grid { gap: 34px; }
      .footer-inner { grid-template-columns: 1.4fr 1fr 1.1fr; gap: 28px; }
    }
    @media (max-width: 960px) {
      .hero-grid { grid-template-columns: 1fr; gap: 28px; }
      .hero-copy { max-width: 740px; }
      .quick-card { max-width: 640px; }
      .about-grid { grid-template-columns: 1fr; gap: 28px; }
      .report-grid { grid-template-columns: 1fr; }
      .docs-grid { grid-template-columns: 1fr; }
      .advisory-grid { grid-template-columns: repeat(2, 1fr); }
      .office-grid { grid-template-columns: 1fr 1fr; }
      .contact-bar { grid-template-columns: 1fr 1fr; }
      .footer-inner { grid-template-columns: 1fr 1fr; }
      .footer-brand { grid-column: 1 / -1; }
    }
    @media (max-width: 780px) {
      .container { width: calc(100% - 36px); }
      .topbar-inner { min-height: 56px; padding: 8px 0; align-items: flex-start; }
      .topbar-links { gap: 8px 14px; }
      .live-clock { display: none; }
      .navbar { min-height: 66px; gap: 12px; }
      .brand-mark { width: 38px; height: 38px; }
      .brand-name { font-size: 17.5px; }
      .brand-sub { font-size: 9.5px; }
      .nav-actions .nav-report { display: none; }
      .ticker-inner { min-height: 40px; gap: 10px; }
      .ticker-label { font-size: 9px; }
      .ticker-meta { display: none; }
      .ticker-item { font-size: 11px; padding-right: 20px; }
      .ticker-item::after { margin-left: 13px; }
      .section { padding: 64px 0; }
      .section-head { margin-bottom: 28px; }
      .section-desc { font-size: 14.5px; }
      .hero { padding: 54px 0 48px; }
      .hero-title { font-size: clamp(2.1rem, 7.5vw, 2.8rem); }
      .hero-desc { font-size: 15.5px; }
      .hero-actions .btn { width: 100%; }
      .fact-strip { grid-template-columns: 1fr; margin-top: -20px; }
      .fact + .fact { border-left: 0; border-top: 1px solid var(--slate-200); }
      .service-grid { grid-template-columns: 1fr; }
      .form-row { grid-template-columns: 1fr; gap: 0; }
      .severity-options { grid-template-columns: 1fr 1fr; }
      .form-head { padding: 18px 19px 15px; }
      .form-head h3 { font-size: 19px; }
      .incident-form { padding: 19px 19px 21px; }
      .advisory-grid { grid-template-columns: 1fr; }
      .toolbar { align-items: stretch; }
      .search-box { max-width: none; width: 100%; }
      .office-grid { grid-template-columns: 1fr; }
      .contact-bar { grid-template-columns: 1fr; }
      .footer-inner { grid-template-columns: 1fr; gap: 24px; }
      .footer-main { padding: 38px 0 28px; }
      .footer-bottom-inner { display: block; }
      .footer-bottom-links { margin-top: 6px; }
      .modal-card { padding: 24px 19px 21px; }
      .summary-grid { grid-template-columns: 1fr; }
      .doc-actions .btn, .pgp-actions .btn { flex: 1 1 100%; }
    }
    @media (max-width: 480px) {
      .topbar { font-size: 11px; }
      .topbar-links { gap: 6px 11px; }
      .topbar-link .icon { width: 14px; height: 14px; }
      .status-text { font-size: 10px; }
      .ticker-more { display: none; }
      .hero-title { font-size: clamp(1.95rem, 9vw, 2.45rem); }
      .quick-card { padding: 20px; }
      .hero-points { gap: 8px 14px; font-size: 11.5px; }
      .section-title { font-size: 1.8rem; }
      .rfc-row { grid-template-columns: 1fr; gap: 1px; }
      .modal-actions { flex-direction: column; }
      .footer-social { gap: 5px; }
    }
  </style>
</head>
<body>
  <a class="skip-link" href="#main">Lewati ke konten utama</a>
  <svg class="icon-sprite" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <symbol id="i-shield" viewBox="0 0 24 24"><path d="M12 3 5 5.8v5.4c0 4.3 2.9 7.8 7 9.8 4.1-2 7-5.5 7-9.8V5.8L12 3Z"/><path d="m9 11.8 2 2 4-4.4"/></symbol>
    <symbol id="i-shield-alert" viewBox="0 0 24 24"><path d="M12 3 5 5.8v5.4c0 4.3 2.9 7.8 7 9.8 4.1-2 7-5.5 7-9.8V5.8L12 3Z"/><path d="M12 8v4"/><path d="M12 15.5h.01"/></symbol>
    <symbol id="i-phone" viewBox="0 0 24 24"><path d="M22 16.9v2.5a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 3.7 2 2 0 0 1 4.1 1.5h2.5a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L7.6 9.4a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2Z"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></symbol>
    <symbol id="i-whatsapp" viewBox="0 0 24 24"><path d="M20.5 11.6a8.5 8.5 0 0 1-12.6 7.5L3 20.5l1.4-4.8A8.5 8.5 0 1 1 20.5 11.6Z"/><path d="M8.3 7.8c.3.6.6 1.2 1 1.7.2.3.2.6 0 .8l-.7.8c.5 1.1 1.4 2 2.5 2.5l.8-.7c.2-.2.5-.2.8 0 .5.4 1.1.7 1.7 1 .4.2.5.6.4 1l-.3.8c-.2.5-.7.8-1.2.8-3.3-.1-6.1-2.9-6.2-6.2 0-.5.3-1 .8-1.2l.8-.3c.4-.1.8 0 1 .4Z"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></symbol>
    <symbol id="i-arrow-right" viewBox="0 0 24 24"><path d="M4 12h16"/><path d="m14 6 6 6-6 6"/></symbol>
    <symbol id="i-arrow-up" viewBox="0 0 24 24"><path d="M12 20V4"/><path d="m6 10 6-6 6 6"/></symbol>
    <symbol id="i-download" viewBox="0 0 24 24"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></symbol>
    <symbol id="i-alert" viewBox="0 0 24 24"><path d="M10.3 3.9 1.8 18.3A2 2 0 0 0 3.5 21h17a2 2 0 0 0 1.7-2.7L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></symbol>
    <symbol id="i-lock" viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/><path d="M12 14v3"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></symbol>
    <symbol id="i-copy" viewBox="0 0 24 24"><rect x="8" y="8" width="12" height="12" rx="2"/><path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 6.8"/></symbol>
    <symbol id="i-check-circle" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m8 12.4 2.7 2.7 5.5-6"/></symbol>
    <symbol id="i-file" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h6"/></symbol>
    <symbol id="i-server" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="6" rx="2"/><rect x="3" y="14" width="18" height="6" rx="2"/><path d="M7 7h.01"/><path d="M7 17h.01"/><path d="M11 7h5"/><path d="M11 17h5"/></symbol>
    <symbol id="i-network" viewBox="0 0 24 24"><rect x="9" y="3" width="6" height="5" rx="1"/><rect x="2" y="16" width="6" height="5" rx="1"/><rect x="16" y="16" width="6" height="5" rx="1"/><path d="M12 8v4"/><path d="M5 16v-2h14v2"/><path d="M12 12v2"/></symbol>
    <symbol id="i-radar" viewBox="0 0 24 24"><path d="M19.1 4.9A10 10 0 1 0 12 22"/><path d="M16.2 7.8A6 6 0 1 0 12 18"/><path d="M13 13a2 2 0 1 0-2-2"/><path d="M13 11 21 3"/></symbol>
    <symbol id="i-fingerprint" viewBox="0 0 24 24"><path d="M12 10a3 3 0 0 0-3 3c0 3-1 5-2 6"/><path d="M12 6a7 7 0 0 0-7 7c0 1.5-.2 3-.8 4.3"/><path d="M12 2a11 11 0 0 0-11 11c0 2.1-.4 4.2-1.1 6"/><path d="M15 13a3 3 0 0 0-3-3"/><path d="M12 18c0 2-.4 3.4-1 4.2"/><path d="M18 10a6 6 0 0 0-6-6"/><path d="M16 13c0 4-.7 6.4-2.5 8.5"/><path d="M20 10a8 8 0 0 0-8-8"/><path d="M20 13c0 5.1-1.2 7.4-3.3 9"/></symbol>
    <symbol id="i-activity" viewBox="0 0 24 24"><path d="M3 12h4l2-7 4 14 2-7h6"/></symbol>
    <symbol id="i-globe" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15 15 0 0 1 0 18"/><path d="M12 3a15 15 0 0 0 0 18"/></symbol>
    <symbol id="i-instagram" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/></symbol>
    <symbol id="i-key" viewBox="0 0 24 24"><circle cx="7.5" cy="15.5" r="4.5"/><path d="m10.7 12.3 8.1-8.1"/><path d="m15.5 7.5 2.5 2.5"/><path d="m18 5 2.5 2.5"/></symbol>
    <symbol id="i-building" viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="1"/><path d="M9 21v-4h6v4"/><path d="M8 7h.01"/><path d="M12 7h.01"/><path d="M16 7h.01"/><path d="M8 11h.01"/><path d="M12 11h.01"/><path d="M16 11h.01"/></symbol>
    <symbol id="i-external" viewBox="0 0 24 24"><path d="M14 3h7v7"/><path d="M10 14 21 3"/><path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24"><path d="m6 6 12 12"/><path d="M18 6 6 18"/></symbol>
    <symbol id="i-chevron" viewBox="0 0 24 24"><path d="m9 5 7 7-7 7"/></symbol>
    <symbol id="i-bug" viewBox="0 0 24 24"><rect x="7" y="7" width="10" height="12" rx="5"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/><path d="M3 13h4"/><path d="M17 13h4"/><path d="m5 7 2 2"/><path d="m19 7-2 2"/><path d="m5 19 2-2"/><path d="m19 19-2-2"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    <symbol id="i-headset" viewBox="0 0 24 24"><path d="M4 13v-2a8 8 0 0 1 16 0v2"/><path d="M18 19a2 2 0 0 1-2 2h-3"/><rect x="2" y="12" width="4" height="7" rx="2"/><rect x="18" y="12" width="4" height="7" rx="2"/></symbol>
  </svg>

  <div class="topbar">
    <div class="container topbar-inner">
      <div class="topbar-links">
        <a class="topbar-link" href="https://wa.me/628155044434" target="_blank" rel="noopener noreferrer">
          <svg class="icon icon-sm" aria-hidden="true"><use href="#i-whatsapp"></use></svg>
          <span>WhatsApp <strong>08155044434</strong></span>
        </a>
        <a class="topbar-link" href="mailto:csirt@radnet-digital.id">
          <svg class="icon icon-sm" aria-hidden="true"><use href="#i-mail"></use></svg>
          <span>csirt@radnet-digital.id</span>
        </a>
        <a class="topbar-link" href="https://radnet-digital.id/" target="_blank" rel="noopener noreferrer">
          <svg class="icon icon-sm" aria-hidden="true"><use href="#i-globe"></use></svg>
          <span>radnet-digital.id</span>
        </a>
      </div>
      <div class="topbar-status">
        <span class="soc-dot" aria-hidden="true"></span>
        <span class="status-text">24/7 SOC · Normal / Siaga</span>
        <span class="live-clock" id="liveClock" aria-live="polite">--:--:-- WIB</span>
      </div>
    </div>
  </div>

  <header class="site-header" id="siteHeader">
    <div class="container navbar">
      <a class="brand" href="#beranda" aria-label="RADNET-CSIRT, kembali ke beranda">
        <span class="brand-mark" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 5 5.8v5.4c0 4.3 2.9 7.8 7 9.8 4.1-2 7-5.5 7-9.8V5.8L12 3Z"/><path d="m9 11.8 2 2 4-4.4"/></svg>
        </span>
        <span class="brand-text">
          <span class="brand-name">RADNET<span>-CSIRT</span></span>
          <span class="brand-sub">PT Radnet Digital Indonesia</span>
        </span>
      </a>

      <nav class="nav-links" aria-label="Navigasi utama">
        <a class="nav-link active" href="#beranda" data-nav="beranda">Beranda</a>
        <a class="nav-link" href="#tentang" data-nav="tentang">Tentang Kami</a>
        <a class="nav-link" href="#layanan" data-nav="layanan">Layanan</a>
        <a class="nav-link" href="#lapor" data-nav="lapor">Lapor Insiden</a>
        <a class="nav-link" href="#advisori" data-nav="advisori">Advisori</a>
        <a class="nav-link" href="#rfc" data-nav="rfc">RFC 2350</a>
        <a class="nav-link" href="#kontak" data-nav="kontak">Kontak</a>
      </nav>

      <div class="nav-actions">
        <a class="btn btn-danger btn-sm nav-report" href="#lapor">
          <svg class="icon icon-sm" aria-hidden="true"><use href="#i-alert"></use></svg>
          Lapor Insiden
        </a>
        <button class="mobile-toggle" id="mobileToggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobileDrawer">
          <svg class="icon" id="menuIconOpen" aria-hidden="true"><use href="#i-menu"></use></svg>
          <svg class="icon" id="menuIconClose" style="display:none" aria-hidden="true"><use href="#i-x"></use></svg>
        </button>
      </div>
    </div>

    <div class="mobile-drawer" id="mobileDrawer">
      <div class="container">
        <nav class="mobile-nav" aria-label="Navigasi seluler">
          <a class="mobile-link" href="#beranda" data-nav="beranda">Beranda <svg class="icon icon-sm" aria-hidden="true"><use href="#i-chevron"></use></svg></a>
          <a class="mobile-link" href="#tentang" data-nav="tentang">Tentang Kami <svg class="icon icon-sm" aria-hidden="true"><use href="#i-chevron"></use></svg></a>
          <a class="mobile-link" href="#layanan" data-nav="layanan">Layanan <svg class="icon icon-sm" aria-hidden="true"><use href="#i-chevron"></use></svg></a>
          <a class="mobile-link" href="#lapor" data-nav="lapor">Lapor Insiden <svg class="icon icon-sm" aria-hidden="true"><use href="#i-chevron"></use></svg></a>
          <a class="mobile-link" href="#advisori" data-nav="advisori">Advisori <svg class="icon icon-sm" aria-hidden="true"><use href="#i-chevron"></use></svg></a>
          <a class="mobile-link" href="#rfc" data-nav="rfc">RFC 2350 <svg class="icon icon-sm" aria-hidden="true"><use href="#i-chevron"></use></svg></a>
          <a class="mobile-link" href="#kontak" data-nav="kontak">Kontak <svg class="icon icon-sm" aria-hidden="true"><use href="#i-chevron"></use></svg></a>
          <a class="btn btn-danger btn-block mobile-cta" href="#lapor">
            <svg class="icon icon-sm" aria-hidden="true"><use href="#i-alert"></use></svg>
            Laporkan Insiden
          </a>
        </nav>
      </div>
    </div>
  </header>

  <div class="security-ticker" id="securityTicker" aria-label="Pengumuman dan advisori keamanan terbaru RADNET-CSIRT">
    <div class="container ticker-inner">
      <span class="ticker-label"><span class="ticker-live" aria-hidden="true"></span>Advisori Terbaru</span>
      <div class="ticker-viewport">
        <div class="ticker-track" id="tickerTrack">
          <span class="ticker-sequence">
            <a class="ticker-item" href="#advisori">
              <span class="ticker-sev critical">Critical</span>
              <span class="ticker-text">Kerentanan aktif pada sistem — patch segera</span>
              <span class="ticker-meta">RADNET-ADV-2026</span>
            </a>
            <a class="ticker-item" href="#advisori">
              <span class="ticker-sev high">High</span>
              <span class="ticker-text">Patch update OpenSSL dan layanan edge</span>
              <span class="ticker-meta">Advisori keamanan</span>
            </a>
            <a class="ticker-item" href="#advisori">
              <span class="ticker-sev medium">Info</span>
              <span class="ticker-text">Advisori keamanan terbaru 2026</span>
              <span class="ticker-meta">Diperbarui berkala</span>
            </a>
          </span>
        </div>
      </div>
      <a class="ticker-more" href="#advisori">
        Lihat Semua
        <svg class="icon" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
      </a>
    </div>
  </div>

  <main id="main">
    <section class="hero" id="beranda">
      <div class="container">
        <div class="hero-grid">
          <div class="hero-copy">
            <span class="hero-badge">
              <svg class="icon icon-sm" aria-hidden="true"><use href="#i-shield"></use></svg>
              CSIRT Resmi PT Radnet Digital Indonesia
            </span>
            <h1 class="hero-title">Respons cepat untuk <span class="accent">insiden siber</span> dan keamanan digital.</h1>
            <p class="hero-desc">RADNET-CSIRT menjadi titik koordinasi resmi untuk deteksi, penanganan, pemulihan, dan berbagi informasi keamanan di lingkungan layanan digital Radnet.</p>
            <div class="hero-actions">
              <a class="btn btn-danger" href="#lapor">
                <svg class="icon" aria-hidden="true"><use href="#i-alert"></use></svg>
                Laporkan Insiden Siber
              </a>
              <a class="btn btn-outline" href="#rfc" id="downloadProfileBtn">
                <svg class="icon" aria-hidden="true"><use href="#i-download"></use></svg>
                Unduh Profil RFC 2350
              </a>
            </div>
            <ul class="hero-points" aria-label="Keunggulan layanan">
              <li><svg class="icon icon-sm" aria-hidden="true"><use href="#i-check-circle"></use></svg>Respons awal &lt; 15 menit</li>
              <li><svg class="icon icon-sm" aria-hidden="true"><use href="#i-check-circle"></use></svg>Monitoring 24/7</li>
              <li><svg class="icon icon-sm" aria-hidden="true"><use href="#i-check-circle"></use></svg>Kanal terenkripsi PGP</li>
            </ul>
          </div>

          <aside class="quick-card" aria-label="Kanal darurat RADNET-CSIRT">
            <span class="quick-kicker">Kanal Darurat</span>
            <h2>Butuh bantuan segera?</h2>
            <p>Hubungi tim SOC atau kirim laporan resmi. Untuk insiden aktif, utamakan WhatsApp agar segera masuk antrean triase.</p>
            <div class="quick-links">
              <a class="quick-link" href="https://wa.me/628155044434" target="_blank" rel="noopener noreferrer">
                <span class="quick-link-icon"><svg class="icon icon-sm" aria-hidden="true"><use href="#i-whatsapp"></use></svg></span>
                <span class="quick-link-text"><span>WhatsApp Hotline</span><strong>08155044434</strong></span>
              </a>
              <a class="quick-link" href="mailto:csirt@radnet-digital.id">
                <span class="quick-link-icon"><svg class="icon icon-sm" aria-hidden="true"><use href="#i-mail"></use></svg></span>
                <span class="quick-link-text"><span>Email CSIRT</span><strong>csirt@radnet-digital.id</strong></span>
              </a>
            </div>
            <div class="quick-status"><span class="soc-dot" aria-hidden="true"></span>Operasional 24 jam · WIB</div>
            <a class="btn btn-danger btn-block" href="#lapor" style="margin-top:14px">
              <svg class="icon icon-sm" aria-hidden="true"><use href="#i-alert"></use></svg>
              Kirim Laporan Insiden
            </a>
          </aside>
        </div>
      </div>
    </section>

    <div class="container">
      <div class="fact-strip reveal">
        <div class="fact"><strong>&lt; 15 menit</strong><span>Target respons awal tim SOC</span></div>
        <div class="fact"><strong>24 / 7</strong><span>Monitoring dan kesiapan tanggap</span></div>
        <div class="fact"><strong>RFC 2350</strong><span>Profil dan kanal komunikasi resmi</span></div>
      </div>
    </div>

    <section class="section" id="tentang">
      <div class="container">
        <div class="about-grid">
          <div class="about-copy reveal">
            <span class="eyebrow">Tentang Kami</span>
            <h2 class="section-title">Tim keamanan yang terkoordinasi dan mudah dihubungi.</h2>
            <p>PT Radnet Digital Indonesia bergerak di bidang layanan solusi digital, termasuk konektivitas internet, cloud, DNS, dan keamanan infrastruktur. RADNET-CSIRT hadir sebagai pusat koordinasi penanganan insiden siber untuk perusahaan, pelanggan, dan mitra.</p>
            <p>Kami menjalankan proses triase yang terstruktur, menjaga kerahasiaan informasi pelapor, dan berkoordinasi dengan pemangku kepentingan terkait sesuai kebutuhan penanganan.</p>
            <ul class="about-points">
              <li class="about-point"><svg class="icon" aria-hidden="true"><use href="#i-check-circle"></use></svg><span>Mandat penanganan insiden, koordinasi kerentanan, dan advisori keamanan.</span></li>
              <li class="about-point"><svg class="icon" aria-hidden="true"><use href="#i-check-circle"></use></svg><span>Kanal komunikasi resmi yang tervalidasi dan tercatat dalam sistem tiket.</span></li>
              <li class="about-point"><svg class="icon icon-sm" aria-hidden="true"><use href="#i-check-circle"></use></svg><span>Dukungan tim yang tersebar di Surabaya, Jakarta, dan Bandung.</span></li>
            </ul>
          </div>

          <div class="info-card reveal">
            <h3>Informasi Kelembagaan</h3>
            <p>Ringkasan profil resmi RADNET-CSIRT.</p>
            <div class="info-row"><span>Organisasi</span><strong>PT Radnet Digital Indonesia</strong></div>
            <div class="info-row"><span>Tipe CSIRT</span><strong>Corporate / Enterprise CSIRT</strong></div>
            <div class="info-row"><span>Cakupan</span><strong>Indonesia</strong></div>
            <div class="info-row"><span>Zona waktu</span><strong>Asia/Jakarta (UTC+7)</strong></div>
            <div class="info-row"><span>Kantor pusat</span><strong>Intiland Tower Suites Level 3, Surabaya</strong></div>
            <div class="info-row"><span>Website</span><strong><a href="https://radnet-digital.id/" target="_blank" rel="noopener noreferrer">radnet-digital.id</a></strong></div>
            <div class="info-note">
              <svg class="icon icon-sm" aria-hidden="true"><use href="#i-shield"></use></svg>
              <span>Profil lengkap dan kunci PGP publik tersedia pada bagian RFC 2350.</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section section-alt" id="layanan">
      <div class="container">
        <div class="section-head reveal">
          <span class="eyebrow">Layanan</span>
          <h2 class="section-title">Dukungan keamanan yang <span class="accent">jelas dan terarah.</span></h2>
          <p class="section-desc">Layanan inti RADNET-CSIRT untuk membantu organisasi mendeteksi, merespons, dan memulihkan diri dari insiden siber.</p>
        </div>

        <div class="service-grid">
          <article class="service-card reveal">
            <span class="service-icon"><svg class="icon" aria-hidden="true"><use href="#i-shield-alert"></use></svg></span>
            <h3>Penanganan Insiden Siber</h3>
            <p>Verifikasi, triase, containment, eradikasi, dan pendampingan pemulihan layanan yang terdampak.</p>
          </article>
          <article class="service-card reveal">
            <span class="service-icon"><svg class="icon" aria-hidden="true"><use href="#i-network"></use></svg></span>
            <h3>Koordinasi Kerentanan</h3>
            <p>Koordinasi pengungkapan kerentanan secara bertanggung jawab dengan vendor dan pemangku kepentingan.</p>
          </article>
          <article class="service-card reveal">
            <span class="service-icon"><svg class="icon" aria-hidden="true"><use href="#i-radar"></use></svg></span>
            <h3>Peringatan Dini &amp; Advisori</h3>
            <p>Informasi keamanan yang dapat ditindaklanjuti, mencakup kerentanan aktif dan rekomendasi mitigasi.</p>
          </article>
          <article class="service-card reveal">
            <span class="service-icon"><svg class="icon" aria-hidden="true"><use href="#i-fingerprint"></use></svg></span>
            <h3>Forensik Digital &amp; Edukasi</h3>
            <p>Analisis artefak insiden serta sosialisasi keamanan untuk meningkatkan kesiapan konstituen.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section" id="lapor">
      <div class="container">
        <div class="section-head reveal">
          <span class="eyebrow">Pelaporan Insiden</span>
          <h2 class="section-title">Laporkan insiden. <span class="accent">Kami siap merespons.</span></h2>
          <p class="section-desc">Sampaikan kronologi dan bukti yang tersedia. Tim SOC akan melakukan triase dan menghubungi pelapor sesuai tingkat keparahan.</p>
        </div>

        <div class="report-grid">
          <aside class="report-aside">
            <div class="help-card reveal">
              <span class="help-badge"><span class="soc-dot" aria-hidden="true"></span>Hotline Insiden</span>
              <h3>Terjadi insiden aktif?</h3>
              <p>Untuk kondisi yang berdampak langsung pada layanan atau data sensitif, hubungi kanal darurat kami segera.</p>
              <div class="help-links">
                <a class="help-link" href="https://wa.me/628155044434" target="_blank" rel="noopener noreferrer">
                  <svg class="icon" aria-hidden="true"><use href="#i-whatsapp"></use></svg>
                  <span>08155044434 · WhatsApp 24/7</span>
                </a>
                <a class="help-link" href="mailto:csirt@radnet-digital.id">
                  <svg class="icon" aria-hidden="true"><use href="#i-mail"></use></svg>
                  <span>csirt@radnet-digital.id</span>
                </a>
              </div>
            </div>

            <div class="checklist-card reveal">
              <h3>Informasi yang perlu disiapkan</h3>
              <ul class="checklist">
                <li><svg class="icon" aria-hidden="true"><use href="#i-check"></use></svg>Waktu kejadian dan zona waktu.</li>
                <li><svg class="icon" aria-hidden="true"><use href="#i-check"></use></svg>Sistem, akun, atau layanan yang terdampak.</li>
                <li><svg class="icon" aria-hidden="true"><use href="#i-check"></use></svg>Log, tangkapan layar, atau indikator kompromi.</li>
                <li><svg class="icon" aria-hidden="true"><use href="#i-check"></use></svg>Tindakan yang sudah dilakukan.</li>
              </ul>
            </div>

            <div class="privacy-note reveal">
              <svg class="icon icon-sm" aria-hidden="true"><use href="#i-lock"></use></svg>
              <span>Hindari mengirim kata sandi atau kunci privat melalui formulir ini. Informasi pelapor ditangani secara terbatas.</span>
            </div>
          </aside>

          <div class="form-card reveal">
            <div class="form-head">
              <div>
                <span class="form-kicker">Formulir Resmi</span>
                <h3>Formulir Pelaporan Insiden</h3>
                <p>Field bertanda <span class="req">*</span> wajib diisi. Estimasi pengisian 3–5 menit.</p>
              </div>
              <span class="secure-badge"><svg class="icon" style="width:11px;height:11px" aria-hidden="true"><use href="#i-lock"></use></svg>Kanal Resmi</span>
            </div>

            <form class="incident-form" id="incidentForm" novalidate>
              <div class="form-row">
                <div class="field-group" data-field="name">
                  <label for="reporterName">Nama Pelapor <span class="req">*</span></label>
                  <input class="field" id="reporterName" name="reporterName" type="text" placeholder="Nama lengkap" autocomplete="name" required aria-describedby="errName" />
                  <span class="field-error" id="errName">Nama pelapor wajib diisi.</span>
                </div>
                <div class="field-group" data-field="org">
                  <label for="reporterOrg">Organisasi / Instansi <span class="req">*</span></label>
                  <input class="field" id="reporterOrg" name="reporterOrg" type="text" placeholder="Nama organisasi atau instansi" autocomplete="organization" required aria-describedby="errOrg" />
                  <span class="field-error" id="errOrg">Organisasi atau instansi wajib diisi.</span>
                </div>
              </div>

              <div class="form-row">
                <div class="field-group" data-field="email">
                  <label for="reporterEmail">Email Kontak <span class="req">*</span></label>
                  <input class="field" id="reporterEmail" name="reporterEmail" type="email" placeholder="nama@organisasi.co.id" autocomplete="email" required aria-describedby="errEmail" />
                  <span class="field-error" id="errEmail">Masukkan alamat email yang valid.</span>
                </div>
                <div class="field-group" data-field="phone">
                  <label for="reporterPhone">Telepon Darurat <span class="req">*</span></label>
                  <input class="field" id="reporterPhone" name="reporterPhone" type="tel" placeholder="+62 812 3456 7890" autocomplete="tel" required aria-describedby="errPhone" />
                  <span class="field-error" id="errPhone">Masukkan nomor telepon yang dapat dihubungi.</span>
                </div>
              </div>

              <div class="field-group" data-field="type">
                <label for="incidentType">Jenis Insiden <span class="req">*</span></label>
                <select class="field" id="incidentType" name="incidentType" required aria-describedby="errType">
                  <option value="">Pilih jenis insiden</option>
                  <option>Malware / Ransomware</option>
                  <option>Phishing / Social Engineering</option>
                  <option>DDoS / Gangguan Layanan</option>
                  <option>Web Defacement / Akses Tidak Sah</option>
                  <option>Kebocoran Data</option>
                  <option>Kerentanan Sistem</option>
                  <option>Insiden Lainnya</option>
                </select>
                <span class="field-error" id="errType">Pilih jenis insiden yang paling sesuai.</span>
              </div>

              <fieldset class="field-group severity-group" data-field="severity">
                <legend>Tingkat Keparahan <span class="req">*</span></legend>
                <div class="severity-options">
                  <label class="sev-option" data-level="Low">
                    <input type="radio" name="severity" value="Low" required />
                    <span><b>Low</b><small>Terbatas</small></span>
                  </label>
                  <label class="sev-option" data-level="Medium">
                    <input type="radio" name="severity" value="Medium" />
                    <span><b>Medium</b><small>Perlu tindak lanjut</small></span>
                  </label>
                  <label class="sev-option" data-level="High">
                    <input type="radio" name="severity" value="High" />
                    <span><b>High</b><small>Signifikan</small></span>
                  </label>
                  <label class="sev-option" data-level="Critical">
                    <input type="radio" name="severity" value="Critical" />
                    <span><b>Critical</b><small>Darurat</small></span>
                  </label>
                </div>
                <span class="field-error" id="errSeverity">Pilih tingkat keparahan insiden.</span>
              </fieldset>

              <div class="field-group" data-field="desc">
                <label for="incidentDesc">Deskripsi Kronologi <span class="req">*</span></label>
                <textarea class="field" id="incidentDesc" name="incidentDesc" rows="5" maxlength="2000" minlength="20" placeholder="Jelaskan kronologi, sistem yang terdampak, indikator kompromi, dan tindakan yang sudah dilakukan..." required aria-describedby="errDesc"></textarea>
                <div class="field-meta"><span>Minimal 20 karakter.</span><span class="counter" id="descCount">0 / 2000</span></div>
                <span class="field-error" id="errDesc">Deskripsi kronologi minimal 20 karakter.</span>
              </div>

              <div class="field-group" data-field="file">
                <label for="evidence">Unggah Berkas / Log <span class="muted" style="font-weight:500">(opsional)</span></label>
                <label class="file-drop" for="evidence">
                  <span class="file-drop-icon"><svg class="icon" aria-hidden="true"><use href="#i-file"></use></svg></span>
                  <span class="file-drop-copy"><strong id="fileName">Klik untuk mengunggah log atau tangkapan layar</strong><span id="fileMeta">LOG, TXT, PNG, JPG, PDF, ZIP · Maks. 10 MB</span></span>
                  <input id="evidence" name="evidence" type="file" accept=".log,.txt,.png,.jpg,.jpeg,.pdf,.zip" />
                </label>
                <span class="field-error" id="errFile">Ukuran berkas melebihi 10 MB.</span>
              </div>

              <div class="consent-row" data-field="consent">
                <input id="agree" name="agree" type="checkbox" required aria-describedby="errConsent" />
                <div>
                  <label for="agree">Saya menyatakan laporan ini disampaikan dengan itikad baik dan data yang diberikan benar. Saya memahami pemrosesan data untuk keperluan penanganan insiden.</label>
                  <span class="field-error" id="errConsent">Persetujuan wajib dicentang sebelum mengirim laporan.</span>
                </div>
              </div>

              <button class="btn btn-danger btn-block submit-btn" id="submitBtn" type="submit">
                <span class="spinner" aria-hidden="true"></span>
                <span id="submitLabel">Kirim Laporan Insiden</span>
                <svg class="icon submit-arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
              </button>
              <p class="form-footnote"><svg class="icon" style="width:11px;height:11px;display:inline;vertical-align:-2px;margin-right:3px" aria-hidden="true"><use href="#i-lock"></use></svg>Dengan mengirim laporan, Anda akan menerima kode tiket untuk pelacakan.</p>
            </form>
          </div>
        </div>
      </div>
    </section>

    <section class="section section-alt" id="advisori">
      <div class="container">
        <div class="section-head reveal">
          <span class="eyebrow">Advisori Keamanan</span>
          <h2 class="section-title">Informasi keamanan yang <span class="accent">dapat ditindaklanjuti.</span></h2>
          <p class="section-desc">Pantau advisori terbaru RADNET-CSIRT. Gunakan pencarian atau filter tingkat keparahan untuk menemukan informasi yang relevan.</p>
        </div>

        <div class="toolbar reveal">
          <div class="search-box">
            <svg class="icon" aria-hidden="true"><use href="#i-search"></use></svg>
            <label class="sr-only" for="advisorySearch">Cari advisori keamanan</label>
            <input class="field" id="advisorySearch" type="search" placeholder="Cari judul, CVE, atau kategori..." autocomplete="off" />
          </div>
          <div class="filter-group" role="group" aria-label="Filter tingkat keparahan advisori">
            <button class="filter-btn active" type="button" data-filter="all">Semua</button>
            <button class="filter-btn" type="button" data-filter="critical">Critical</button>
            <button class="filter-btn" type="button" data-filter="high">High</button>
            <button class="filter-btn" type="button" data-filter="medium">Medium</button>
          </div>
        </div>

        <div class="advisory-meta reveal" aria-live="polite">
          <span>Menampilkan <strong id="advisoryCount">6</strong> advisori</span>
          <span aria-hidden="true">·</span>
          <span>Diperbarui berkala oleh tim SOC</span>
        </div>

        <div class="advisory-grid" id="advisoryGrid"></div>

        <div class="advisory-empty" id="advisoryEmpty">
          <span class="advisory-empty-icon"><svg class="icon icon-lg" aria-hidden="true"><use href="#i-search"></use></svg></span>
          <h3>Advisori tidak ditemukan</h3>
          <p>Coba kata kunci lain atau ubah filter tingkat keparahan.</p>
        </div>
      </div>
    </section>

    <section class="section" id="rfc">
      <div class="container">
        <div class="section-head reveal">
          <span class="eyebrow">Dokumen Resmi</span>
          <h2 class="section-title">Profil RFC 2350 &amp; <span class="accent">kunci PGP publik.</span></h2>
          <p class="section-desc">Dokumen ini membantu konstituen, mitra, dan otoritas memahami mandat, cakupan, kebijakan, serta kanal komunikasi resmi RADNET-CSIRT.</p>
        </div>

        <div class="docs-grid">
          <div class="doc-card reveal">
            <div class="doc-head">
              <div class="doc-title-wrap">
                <span class="doc-icon"><svg class="icon" aria-hidden="true"><use href="#i-file"></use></svg></span>
                <div>
                  <h3>RFC 2350 Profile</h3>
                  <p>RADNET-CSIRT-RFC2350 · Versi 2.0</p>
                </div>
              </div>
              <span class="doc-status"><svg class="icon" style="width:9px;height:9px" aria-hidden="true"><use href="#i-check"></use></svg>Aktif</span>
            </div>
            <div class="doc-body">
              <dl class="rfc-list">
                <div class="rfc-row"><dt>Nama tim</dt><dd>RADNET-CSIRT</dd></div>
                <div class="rfc-row"><dt>Organisasi</dt><dd>PT Radnet Digital Indonesia</dd></div>
                <div class="rfc-row"><dt>Tipe CSIRT</dt><dd>Corporate / Enterprise CSIRT</dd></div>
                <div class="rfc-row"><dt>Konstituen</dt><dd>Perusahaan, pelanggan, dan mitra Radnet</dd></div>
                <div class="rfc-row"><dt>Cakupan</dt><dd>Indonesia</dd></div>
                <div class="rfc-row"><dt>Pembaruan</dt><dd>30 Oktober 2026</dd></div>
              </dl>
              <div class="doc-actions">
                <button class="btn btn-primary btn-sm" id="downloadRfcBtn" type="button">
                  <svg class="icon icon-sm" aria-hidden="true"><use href="#i-download"></use></svg>
                  Unduh RFC 2350
                </button>
                <button class="btn btn-outline btn-sm" id="downloadPgpBtn" type="button">
                  <svg class="icon icon-sm" aria-hidden="true"><use href="#i-key"></use></svg>
                  Unduh Kunci PGP
                </button>
              </div>
            </div>
          </div>

          <div class="doc-card reveal">
            <div class="doc-head">
              <div class="doc-title-wrap">
                <span class="doc-icon green"><svg class="icon" aria-hidden="true"><use href="#i-key"></use></svg></span>
                <div>
                  <h3>PGP Public Key</h3>
                  <p>Enkripsi laporan sensitif · Key ID B3D7 7C28</p>
                </div>
              </div>
              <span class="doc-status"><svg class="icon" style="width:9px;height:9px" aria-hidden="true"><use href="#i-lock"></use></svg>Public</span>
            </div>
            <div class="doc-body">
              <div class="fingerprint">
                <svg class="icon" aria-hidden="true"><use href="#i-fingerprint"></use></svg>
                <div>
                  <span>Fingerprint</span>
                  <code>A2B6 AA04 57FB 12C6 E870 BFE5 BD55 FF8A B3D7 7C28</code>
                </div>
              </div>
              <div class="pgp-key-box">
                <pre class="pgp-key" id="pgpKeyBlock">-----BEGIN PGP PUBLIC KEY BLOCK-----

mDMEausojxYJKwYBBAHaRw8BAQdAulSW6PosXrXuhLnizDL+pBU+G9ao4ghucFLU
7tmjKj20OlJBRE5FVC1DU0lSVCAoSW5jaWRlbnQgUmVzcG9uc2UpIDxjc2lydEBy
YWRuZXQtZGlnaXRhbC5pZD6IkAQTFgoAOBYhBKK2qgRX+xLG6HC/5b1V/4qz13wo
BQJq6yiPAhsDBQsJCAcCBhUKCQgLAgQWAgMBAh4BAheAAAoJEL1V/4qz13woM3cA
/RPrprysBJu7wT1GCFMg8k1sspHidJ474crvulvdkoYsAQDLixWPXkM5bKADz03A
kGeobGyYeVTAvDMJaDc9ApNqCLg4BGrrKI8SCisGAQQBl1UBBQEBB0ArbAVijII7
9e3+8qi01JlkyqmtJ2L2QLa4CUyYz5rWYQMBCAeIeAQYFgoAIBYhBKK2qgRX+xLG
6HC/5b1V/4qz13woBQJq6yiPAhsMAAoJEL1V/4qz13woeEgA/04+fqIamTE2N2J/
zWWt3XtukjHT6mIb9iz2q1XEa6gcAQCGwlfYjXsfPhTwPYFC/OYRfBI9y4N+5E8U
RIcRSnB0AQ==
=edJ7
-----END PGP PUBLIC KEY BLOCK-----</pre>
              </div>
              <div class="pgp-actions">
                <span class="tooltip-wrap" id="pgpTooltipWrap">
                  <button class="btn btn-primary btn-sm" id="copyPgpBtn" type="button">
                    <svg class="icon icon-sm" aria-hidden="true"><use href="#i-copy"></use></svg>
                    Salin Kunci PGP
                  </button>
                  <span class="copy-tooltip" id="copyTooltip" role="status">Berhasil Disalin!</span>
                </span>
                <button class="btn btn-outline btn-sm" id="copyFingerprintBtn" type="button">
                  <svg class="icon icon-sm" aria-hidden="true"><use href="#i-fingerprint"></use></svg>
                  Salin Fingerprint
                </button>
              </div>
              <p class="pgp-note"><svg class="icon" aria-hidden="true"><use href="#i-shield"></use></svg><span>Gunakan kunci ini untuk melindungi informasi sensitif sebelum dikirim ke csirt@radnet-digital.id. Jangan pernah membagikan kunci privat.</span></p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer" id="kontak">
    <section class="footer-main">
      <div class="container">
        <div class="section-head reveal" style="margin-bottom:26px">
          <span class="eyebrow">Kontak Resmi</span>
          <h2 class="section-title">Jaringan kantor dan kanal komunikasi.</h2>
          <p class="section-desc">Tim kami tersebar di Surabaya, Jakarta, dan Bandung. Untuk laporan insiden, utamakan kanal CSIRT agar tervalidasi dan tercatat.</p>
        </div>

        <div class="office-grid">
          <article class="office-card reveal">
            <div class="office-top">
              <span class="office-city"><svg class="icon icon-sm" aria-hidden="true"><use href="#i-pin"></use></svg>Surabaya</span>
              <span class="office-hq">HQ</span>
            </div>
            <p>Intiland Tower Suites Level 3<br>Jl. Panglima Sudirman Kav. 101-103<br>Surabaya 60271</p>
            <p><a href="https://maps.google.com/?q=Intiland+Tower+Suites+Surabaya" target="_blank" rel="noopener noreferrer">Lihat lokasi</a></p>
          </article>
          <article class="office-card reveal">
            <div class="office-top">
              <span class="office-city"><svg class="icon icon-sm" aria-hidden="true"><use href="#i-pin"></use></svg>Jakarta</span>
            </div>
            <p>Graha Induk KUD lantai 8<br>Jl. Warung Buncit Raya No. 18-20<br>Pejaten, Jakarta Selatan</p>
            <p><a href="https://maps.google.com/?q=Graha+Induk+KUD+Warung+Buncit+Jakarta" target="_blank" rel="noopener noreferrer">Lihat lokasi</a></p>
          </article>
          <article class="office-card reveal">
            <div class="office-top">
              <span class="office-city"><svg class="icon icon-sm" aria-hidden="true"><use href="#i-pin"></use></svg>Bandung</span>
            </div>
            <p>Jalan Kayu Agung I No. 65A<br>Kel. Turangga, Kec. Lengkong<br>Kota Bandung 40264</p>
            <p><a href="tel:+622230505808">Telp. (022) 30505808</a></p>
          </article>
        </div>

        <div class="contact-bar reveal">
          <div class="contact-item">
            <svg class="icon" aria-hidden="true"><use href="#i-whatsapp"></use></svg>
            <div><span>WhatsApp 24/7</span><strong><a href="https://wa.me/628155044434" target="_blank" rel="noopener noreferrer">08155044434</a></strong></div>
          </div>
          <div class="contact-item">
            <svg class="icon" aria-hidden="true"><use href="#i-mail"></use></svg>
            <div><span>Email CSIRT</span><strong><a href="mailto:csirt@radnet-digital.id">csirt@radnet-digital.id</a></strong></div>
          </div>
          <div class="contact-item">
            <svg class="icon" aria-hidden="true"><use href="#i-instagram"></use></svg>
            <div><span>Instagram</span><strong><a href="https://instagram.com/radnetdigital" target="_blank" rel="noopener noreferrer">@radnetdigital</a></strong></div>
          </div>
          <div class="contact-item">
            <svg class="icon" aria-hidden="true"><use href="#i-globe"></use></svg>
            <div><span>Website</span><strong><a href="https://radnet-digital.id/" target="_blank" rel="noopener noreferrer">radnet-digital.id</a></strong></div>
          </div>
        </div>
      </div>
    </section>

    <div class="footer-inner container">
      <div class="footer-brand">
        <a class="brand" href="#beranda" aria-label="RADNET-CSIRT">
          <span class="brand-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 5 5.8v5.4c0 4.3 2.9 7.8 7 9.8 4.1-2 7-5.5 7-9.8V5.8L12 3Z"/><path d="m9 11.8 2 2 4-4.4"/></svg>
          </span>
          <span class="brand-text">
            <span class="brand-name">RADNET<span>-CSIRT</span></span>
            <span class="brand-sub">PT Radnet Digital Indonesia</span>
          </span>
        </a>
        <p>Computer Security Incident Response Team PT Radnet Digital Indonesia. Mendukung keamanan layanan digital, konektivitas, cloud, dan infrastruktur telekomunikasi.</p>
        <div class="footer-social">
          <a href="https://radnet-digital.id/" target="_blank" rel="noopener noreferrer">Website <svg class="icon" aria-hidden="true"><use href="#i-external"></use></svg></a>
          <a href="https://instagram.com/radnetdigital" target="_blank" rel="noopener noreferrer"><svg class="icon" aria-hidden="true"><use href="#i-instagram"></use></svg>@radnetdigital</a>
          <a href="mailto:sales@radnet-digital.id">sales@radnet-digital.id</a>
        </div>
      </div>

      <div class="footer-col">
        <h3>Navigasi</h3>
        <ul class="footer-links">
          <li><a href="#beranda"><svg class="icon" aria-hidden="true"><use href="#i-chevron"></use></svg>Beranda</a></li>
          <li><a href="#tentang"><svg class="icon" aria-hidden="true"><use href="#i-chevron"></use></svg>Tentang Kami</a></li>
          <li><a href="#layanan"><svg class="icon" aria-hidden="true"><use href="#i-chevron"></use></svg>Layanan</a></li>
          <li><a href="#lapor"><svg class="icon" aria-hidden="true"><use href="#i-chevron"></use></svg>Lapor Insiden</a></li>
          <li><a href="#advisori"><svg class="icon" aria-hidden="true"><use href="#i-chevron"></use></svg>Advisori</a></li>
          <li><a href="#rfc"><svg class="icon" aria-hidden="true"><use href="#i-chevron"></use></svg>RFC 2350</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h3>Kontak</h3>
        <ul class="footer-contact-list">
          <li><svg class="icon" aria-hidden="true"><use href="#i-mail"></use></svg><span><a href="mailto:csirt@radnet-digital.id">csirt@radnet-digital.id</a><br><a href="mailto:sales@radnet-digital.id">sales@radnet-digital.id</a></span></li>
          <li><svg class="icon" aria-hidden="true"><use href="#i-whatsapp"></use></svg><span><a href="https://wa.me/628155044434" target="_blank" rel="noopener noreferrer">08155044434</a> (24/7)</span></li>
          <li><svg class="icon" aria-hidden="true"><use href="#i-instagram"></use></svg><span><a href="https://instagram.com/radnetdigital" target="_blank" rel="noopener noreferrer">@radnetdigital</a></span></li>
          <li><svg class="icon" aria-hidden="true"><use href="#i-clock"></use></svg><span>Operasional 24/7 · WIB</span></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <p>&copy; <span id="currentYear">2026</span> PT Radnet Digital Indonesia. Seluruh hak cipta dilindungi.</p>
        <div class="footer-bottom-links">
          <a href="#rfc">RFC 2350</a>
          <a href="#rfc">PGP Key</a>
          <a href="#lapor">Lapor Insiden</a>
          <a href="#beranda">Kembali ke atas</a>
        </div>
      </div>
    </div>
  </footer>

  <button class="back-to-top" id="backToTop" type="button" aria-label="Kembali ke atas">
    <svg class="icon" aria-hidden="true"><use href="#i-arrow-up"></use></svg>
  </button>

  <div class="toast" id="toast" role="status" aria-live="polite">
    <svg class="icon icon-sm" aria-hidden="true"><use href="#i-check-circle"></use></svg>
    <span id="toastMessage">Tersalin</span>
  </div>

  <div class="modal" id="successModal" aria-hidden="true">
    <div class="modal-backdrop" data-close-modal></div>
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="successTitle">
      <button class="modal-close" type="button" data-close-modal aria-label="Tutup dialog">
        <svg class="icon icon-sm" aria-hidden="true"><use href="#i-x"></use></svg>
      </button>
      <div class="modal-success-icon"><svg class="icon" aria-hidden="true"><use href="#i-check-circle"></use></svg></div>
      <h2 class="modal-title" id="successTitle">Laporan Berhasil Diterima</h2>
      <p class="modal-subtitle">Terima kasih. Laporan Anda telah masuk ke antrean triase RADNET-CSIRT. Simpan kode tiket berikut untuk pelacakan dan komunikasi lanjutan.</p>
      <div class="ticket-box">
        <div class="ticket-left"><span>Kode Tiket</span><strong id="ticketNumber">#INC-2026-0000</strong></div>
        <button class="ticket-copy" id="copyTicketBtn" type="button">
          <svg class="icon" style="width:12px;height:12px" aria-hidden="true"><use href="#i-copy"></use></svg>
          Salin
        </button>
      </div>
      <dl class="summary-grid">
        <div><dt>Jenis Insiden</dt><dd id="successType">-</dd></div>
        <div><dt>Tingkat Keparahan</dt><dd id="successSeverity">-</dd></div>
        <div><dt>Email Pelapor</dt><dd class="mono" id="successEmail">-</dd></div>
        <div><dt>Waktu Laporan</dt><dd class="mono" id="successTime">-</dd></div>
      </dl>
      <div class="modal-note">
        <svg class="icon icon-sm" aria-hidden="true"><use href="#i-clock"></use></svg>
        <span>Tim SOC akan melakukan verifikasi awal maksimal 15 menit untuk laporan dengan tingkat keparahan High atau Critical. Pantau email dan telepon yang Anda daftarkan.</span>
      </div>
      <div class="modal-actions">
        <button class="btn btn-danger" type="button" data-close-modal>Tutup</button>
        <a class="btn btn-outline" href="#advisori" data-close-modal>Lihat Advisori</a>
      </div>
    </div>
  </div>

  <div class="modal" id="advisoryModal" aria-hidden="true">
    <div class="modal-backdrop" data-close-modal></div>
    <div class="modal-card advisory-modal-card" role="dialog" aria-modal="true" aria-labelledby="advisoryModalTitle">
      <button class="modal-close" type="button" data-close-modal aria-label="Tutup detail advisori">
        <svg class="icon icon-sm" aria-hidden="true"><use href="#i-x"></use></svg>
      </button>
      <div class="advisory-modal-meta">
        <span class="sev-badge sev-high" id="advisoryModalSeverity">High</span>
        <span class="advisory-modal-id" id="advisoryModalId">RADNET-ADV-2026-000</span>
      </div>
      <h2 class="advisory-modal-title" id="advisoryModalTitle">Judul advisori</h2>
      <div class="advisory-modal-info">
        <span><svg class="icon" aria-hidden="true"><use href="#i-clock"></use></svg><span id="advisoryModalDate">-</span></span>
        <span><svg class="icon" aria-hidden="true"><use href="#i-fingerprint"></use></svg><span id="advisoryModalCve">-</span></span>
        <span><svg class="icon" aria-hidden="true"><use href="#i-activity"></use></svg><span id="advisoryModalCvss">-</span></span>
      </div>
      <div class="advisory-detail">
        <h4>Ringkasan</h4>
        <p id="advisoryModalSummary">-</p>
      </div>
      <div class="advisory-detail">
        <h4>Sistem Terdampak</h4>
        <p id="advisoryModalAffected">-</p>
      </div>
      <div class="advisory-detail recommendation">
        <h4>Rekomendasi Mitigasi</h4>
        <p id="advisoryModalAction">-</p>
      </div>
      <div class="modal-actions">
        <button class="btn btn-primary" type="button" data-close-modal>Mengerti</button>
        <a class="btn btn-outline" href="#lapor" data-close-modal>Laporkan Insiden Terkait</a>
      </div>
    </div>
  </div>

  <script>
    (function () {
      "use strict";

      document.documentElement.classList.add("js");

      const $ = (selector, scope = document) => scope.querySelector(selector);
      const $$ = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));
      const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      const delay = (ms) => new Promise((resolve) => window.setTimeout(resolve, ms));
      const year = new Date().getFullYear();
      const PGP_FINGERPRINT = "A2B6 AA04 57FB 12C6 E870 BFE5 BD55 FF8A B3D7 7C28";

      /* ---------- Live clock ---------- */
      const clockEl = $("#liveClock");
      function updateClock() {
        if (!clockEl) return;
        const time = new Intl.DateTimeFormat("id-ID", {
          timeZone: "Asia/Jakarta",
          hour: "2-digit",
          minute: "2-digit",
          second: "2-digit",
          hour12: false
        }).format(new Date());
        clockEl.textContent = time.replaceAll(".", ":") + " WIB";
      }
      updateClock();
      window.setInterval(updateClock, 1000);

      const yearEl = $("#currentYear");
      if (yearEl) yearEl.textContent = String(year);

      /* ---------- Header, scroll spy, back to top ---------- */
      const header = $("#siteHeader");
      const backToTop = $("#backToTop");
      const navLinks = $$(".nav-link[data-nav]");
      const mobileLinks = $$(".mobile-link[data-nav]");
      const sectionIds = ["beranda", "tentang", "layanan", "lapor", "advisori", "rfc", "kontak"];
      const sectionEls = sectionIds.map((id) => document.getElementById(id)).filter(Boolean);

      function setActiveNav(id) {
        [...navLinks, ...mobileLinks].forEach((link) => {
          const active = link.dataset.nav === id;
          link.classList.toggle("active", active);
          if (active) link.setAttribute("aria-current", "page");
          else link.removeAttribute("aria-current");
        });
      }

      [...navLinks, ...mobileLinks].forEach((link) => {
        link.addEventListener("click", () => {
          if (link.dataset.nav) setActiveNav(link.dataset.nav);
        });
      });

      function updateScrollUI() {
        const y = window.scrollY;
        if (header) header.classList.toggle("scrolled", y > 8);
        if (backToTop) backToTop.classList.toggle("visible", y > 600);
        let current = "beranda";
        const offset = 150;
        sectionEls.forEach((section) => {
          if (section.offsetTop - offset <= y) current = section.id;
        });
        if (y + window.innerHeight >= document.documentElement.scrollHeight - 4) current = "kontak";
        setActiveNav(current);
      }
      updateScrollUI();
      window.addEventListener("scroll", updateScrollUI, { passive: true });

      if (backToTop) {
        backToTop.addEventListener("click", () => {
          window.scrollTo({ top: 0, behavior: prefersReducedMotion ? "auto" : "smooth" });
        });
      }

      /* ---------- Mobile menu ---------- */
      const mobileToggle = $("#mobileToggle");
      const mobileDrawer = $("#mobileDrawer");
      const menuIconOpen = $("#menuIconOpen");
      const menuIconClose = $("#menuIconClose");

      function setMobileMenu(open) {
        if (!mobileToggle || !mobileDrawer) return;
        mobileDrawer.classList.toggle("open", open);
        mobileToggle.setAttribute("aria-expanded", String(open));
        mobileToggle.setAttribute("aria-label", open ? "Tutup menu navigasi" : "Buka menu navigasi");
        if (menuIconOpen) menuIconOpen.style.display = open ? "none" : "block";
        if (menuIconClose) menuIconClose.style.display = open ? "block" : "none";
      }

      if (mobileToggle) {
        mobileToggle.addEventListener("click", () => setMobileMenu(!mobileDrawer.classList.contains("open")));
      }
      $$("#mobileDrawer a").forEach((link) => link.addEventListener("click", () => setMobileMenu(false)));
      window.addEventListener("resize", () => {
        if (window.innerWidth > 1180) setMobileMenu(false);
      });

      /* ---------- Reveal on scroll ---------- */
      const revealEls = $$(".reveal");
      if (!prefersReducedMotion && "IntersectionObserver" in window) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          });
        }, { threshold: 0.08, rootMargin: "0px 0px -35px 0px" });
        revealEls.forEach((el) => revealObserver.observe(el));
      } else {
        revealEls.forEach((el) => el.classList.add("is-visible"));
      }

      /* ---------- Copy, toast, download ---------- */
      const toast = $("#toast");
      const toastMessage = $("#toastMessage");
      let toastTimer = null;

      function showToast(message, tone) {
        if (!toast) return;
        if (toastMessage) toastMessage.textContent = message;
        toast.classList.add("show");
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(() => toast.classList.remove("show"), 2800);
        if (tone === "error") {
          const icon = toast.querySelector(".icon use");
          if (icon) icon.setAttribute("href", "#i-alert");
        } else {
          const icon = toast.querySelector(".icon use");
          if (icon) icon.setAttribute("href", "#i-check-circle");
        }
      }

      async function copyText(text) {
        try {
          if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(text);
            return true;
          }
        } catch (error) {
          /* Fall through to legacy method. */
        }
        try {
          const area = document.createElement("textarea");
          area.value = text;
          area.setAttribute("readonly", "");
          area.style.position = "fixed";
          area.style.opacity = "0";
          area.style.top = "-9999px";
          document.body.appendChild(area);
          area.select();
          const ok = document.execCommand("copy");
          document.body.removeChild(area);
          return ok;
        } catch (error) {
          return false;
        }
      }

      function downloadFile(filename, content, mimeType) {
        const blob = new Blob([content], { type: mimeType || "text/plain;charset=utf-8" });
        const url = URL.createObjectURL(blob);
        const anchor = document.createElement("a");
        anchor.href = url;
        anchor.download = filename;
        anchor.style.display = "none";
        document.body.appendChild(anchor);
        anchor.click();
        document.body.removeChild(anchor);
        window.setTimeout(() => URL.revokeObjectURL(url), 1500);
      }

      const pgpKeyEl = $("#pgpKeyBlock");
      const PGP_KEY = pgpKeyEl ? pgpKeyEl.textContent.trim() : "";

      const RFC_TEXT = `RADNET-CSIRT — PT RADNET DIGITAL INDONESIA
COMPUTER SECURITY INCIDENT RESPONSE TEAM
PROFIL CSIRT SESUAI RFC 2350

Document ID      : RADNET-CSIRT-RFC2350
Version          : 2.0
Publication Date : 30 Oktober 2026
Classification   : PUBLIC
Language         : Bahasa Indonesia / English
Status           : Active

1. DOCUMENT INFORMATION
Dokumen ini menjelaskan profil, mandat, kebijakan, layanan, dan kanal
komunikasi RADNET-CSIRT sesuai struktur yang direkomendasikan RFC 2350.

2. ORGANIZATION AND CONSTITUENCY
Organization     : PT Radnet Digital Indonesia
CSIRT Name       : RADNET-CSIRT
CSIRT Type       : Corporate / Enterprise CSIRT
Constituency     : PT Radnet Digital Indonesia, pelanggan, mitra, dan
                   konstituen dalam ekosistem layanan digital Radnet.
Coverage         : Indonesia
Timezone         : Asia/Jakarta (UTC+7)

3. CONTACT INFORMATION
Email CSIRT      : csirt@radnet-digital.id
Email Sales      : sales@radnet-digital.id
WhatsApp         : 08155044434
Operational Hours: 24 hours / 7 days
Website          : https://radnet-digital.id

Head Office - Surabaya
Intiland Tower Suites Level 3
Jl. Panglima Sudirman Kav. 101-103
Surabaya 60271

Jakarta Office
Graha Induk KUD lantai 8
Jl. Warung Buncit Raya No.18-20
Pejaten, Jakarta Selatan

Bandung Office
Jalan Kayu Agung I No. 65A
Kel. Turangga, Kec. Lengkong
Kota Bandung 40264

4. CHARTER AND MANDATE
- Menerima, memverifikasi, dan menangani laporan insiden siber.
- Mengoordinasikan respons teknis, containment, eradikasi, dan pemulihan.
- Melakukan triase, analisis forensik, dan pelaporan pasca-insiden.
- Mengoordinasikan pengungkapan kerentanan secara bertanggung jawab.
- Menerbitkan peringatan dini dan advisori keamanan.
- Meningkatkan kesadaran dan kesiapan keamanan konstituen.

5. SERVICES
- Penanganan Insiden Siber dan Triase
- Koordinasi Kerentanan Sistem dan Infrastruktur
- Peringatan Dini dan Advisori Keamanan
- Analisis Forensik Digital dan Artefak
- Sosialisasi dan Edukasi Keamanan Siber

6. POLICIES
- Kerahasiaan informasi pelapor dijaga sesuai kebutuhan operasional.
- Informasi sensitif dapat dikirim menggunakan enkripsi PGP.
- Pengungkapan kerentanan dilakukan secara terkoordinasi dengan vendor.
- Klasifikasi berbagi informasi menggunakan format TLP.
- Aktivitas penanganan insiden dicatat dalam jejak audit.

7. PGP FINGERPRINT
${PGP_FINGERPRINT}

PGP PUBLIC KEY
${PGP_KEY}

8. DISCLAIMER
Dokumen ini merupakan profil publik dan dapat diperbarui sewaktu-waktu.
Versi terbaru selalu tersedia melalui portal resmi RADNET-CSIRT.

© ${year} PT Radnet Digital Indonesia. Seluruh hak cipta dilindungi.
`;

      const downloadProfileBtn = $("#downloadProfileBtn");
      if (downloadProfileBtn) {
        downloadProfileBtn.addEventListener("click", (event) => {
          event.preventDefault();
          downloadFile("RADNET-CSIRT-RFC2350-PGP.txt", RFC_TEXT + "\n\n" + PGP_KEY);
          showToast("Profil RFC 2350 dan kunci PGP berhasil diunduh.");
        });
      }

      const downloadRfcBtn = $("#downloadRfcBtn");
      if (downloadRfcBtn) {
        downloadRfcBtn.addEventListener("click", () => {
          downloadFile("RADNET-CSIRT-RFC2350.txt", RFC_TEXT);
          showToast("Dokumen RFC 2350 berhasil diunduh.");
        });
      }

      const downloadPgpBtn = $("#downloadPgpBtn");
      if (downloadPgpBtn) {
        downloadPgpBtn.addEventListener("click", () => {
          downloadFile("radnet-csirt-public.asc", PGP_KEY, "application/pgp-keys");
          showToast("Kunci PGP publik berhasil diunduh.");
        });
      }

      const pgpTooltipWrap = $("#pgpTooltipWrap");
      const copyTooltip = $("#copyTooltip");
      let tooltipTimer = null;

      function showTooltip(message, success) {
        if (!pgpTooltipWrap || !copyTooltip) return;
        copyTooltip.textContent = message;
        pgpTooltipWrap.classList.add("show");
        window.clearTimeout(tooltipTimer);
        tooltipTimer = window.setTimeout(() => pgpTooltipWrap.classList.remove("show"), 2200);
        showToast(message, success ? undefined : "error");
      }

      const copyPgpBtn = $("#copyPgpBtn");
      if (copyPgpBtn) {
        copyPgpBtn.addEventListener("click", async () => {
          const ok = await copyText(PGP_KEY);
          showTooltip(ok ? "Berhasil Disalin!" : "Gagal menyalin kunci", ok);
        });
      }

      const copyFingerprintBtn = $("#copyFingerprintBtn");
      if (copyFingerprintBtn) {
        copyFingerprintBtn.addEventListener("click", async () => {
          const ok = await copyText(PGP_FINGERPRINT);
          showToast(ok ? "Fingerprint PGP berhasil disalin." : "Gagal menyalin fingerprint.", ok ? undefined : "error");
        });
      }

      /* ---------- Modals ---------- */
      const modals = $$(".modal");
      let lastFocusedElement = null;

      function openModal(modal) {
        if (!modal) return;
        lastFocusedElement = document.activeElement;
        modal.classList.add("open");
        modal.setAttribute("aria-hidden", "false");
        document.body.classList.add("modal-open");
        window.setTimeout(() => {
          const closeButton = modal.querySelector(".modal-close");
          if (closeButton) closeButton.focus();
          else modal.querySelector("button, a")?.focus();
        }, 40);
      }

      function closeModal(modal) {
        if (!modal || !modal.classList.contains("open")) return;
        modal.classList.remove("open");
        modal.setAttribute("aria-hidden", "true");
        if (!modals.some((item) => item.classList.contains("open"))) {
          document.body.classList.remove("modal-open");
        }
        if (lastFocusedElement && typeof lastFocusedElement.focus === "function") {
          lastFocusedElement.focus({ preventScroll: true });
        }
      }

      document.addEventListener("click", (event) => {
        const closeTrigger = event.target.closest("[data-close-modal]");
        if (closeTrigger) {
          const modal = closeTrigger.closest(".modal");
          closeModal(modal);
        }
      });

      document.addEventListener("keydown", (event) => {
        const open = modals.find((modal) => modal.classList.contains("open"));
        if (!open) return;
        if (event.key === "Escape") {
          closeModal(open);
          return;
        }
        if (event.key === "Tab") {
          const focusable = $$("button, [href], input, select, textarea, [tabindex]:not([tabindex='-1'])", open)
            .filter((el) => !el.hasAttribute("disabled") && el.offsetParent !== null);
          if (!focusable.length) return;
          const first = focusable[0];
          const last = focusable[focusable.length - 1];
          if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
          } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
          }
        }
      });

      /* ---------- Incident form ---------- */
      const form = $("#incidentForm");
      const submitBtn = $("#submitBtn");
      const submitLabel = $("#submitLabel");
      const evidenceInput = $("#evidence");
      const fileNameEl = $("#fileName");
      const fileMetaEl = $("#fileMeta");
      const descInput = $("#incidentDesc");
      const descCount = $("#descCount");
      const successModal = $("#successModal");
      let lastTicket = "";

      function getGroup(name) {
        return form ? form.querySelector(`[data-field="${name}"]`) : null;
      }

      function setFieldError(name, message) {
        const group = getGroup(name);
        if (!group) return;
        group.classList.add("invalid");
        const error = group.querySelector(".field-error");
        if (error && message) error.textContent = message;
        $$("input, select, textarea", group).forEach((field) => field.setAttribute("aria-invalid", "true"));
      }

      function clearFieldError(name) {
        const group = getGroup(name);
        if (!group) return;
        group.classList.remove("invalid");
        $$("input, select, textarea", group).forEach((field) => field.removeAttribute("aria-invalid"));
      }

      function validateForm() {
        let firstInvalid = null;
        const mark = (name, condition, message) => {
          if (condition) {
            clearFieldError(name);
            return true;
          }
          setFieldError(name, message);
          if (!firstInvalid) {
            const group = getGroup(name);
            firstInvalid = group ? group.querySelector("input, select, textarea") || group : null;
          }
          return false;
        };

        const name = $("#reporterName");
        const org = $("#reporterOrg");
        const email = $("#reporterEmail");
        const phone = $("#reporterPhone");
        const type = $("#incidentType");
        const desc = $("#incidentDesc");
        const agree = $("#agree");
        const severity = form ? form.querySelector('input[name="severity"]:checked') : null;
        const file = evidenceInput && evidenceInput.files ? evidenceInput.files[0] : null;

        const validName = mark("name", name && name.value.trim().length >= 2, "Nama pelapor minimal 2 karakter.");
        const validOrg = mark("org", org && org.value.trim().length >= 2, "Organisasi atau instansi wajib diisi.");
        const validEmail = mark("email", email && /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email.value.trim()), "Masukkan alamat email yang valid.");
        const validPhone = mark("phone", phone && /^[+()\d\s-]{8,20}$/.test(phone.value.trim()), "Masukkan nomor telepon yang valid (8–20 digit).");
        const validType = mark("type", type && type.value !== "", "Pilih jenis insiden yang paling sesuai.");
        const validSeverity = mark("severity", Boolean(severity), "Pilih tingkat keparahan insiden.");
        const validDesc = mark("desc", desc && desc.value.trim().length >= 20, "Deskripsi kronologi minimal 20 karakter.");
        const validFile = mark("file", !file || file.size <= 10 * 1024 * 1024, "Ukuran berkas melebihi 10 MB. Pilih berkas yang lebih kecil.");
        const validConsent = mark("consent", agree && agree.checked, "Persetujuan wajib dicentang sebelum mengirim laporan.");

        const isValid = validName && validOrg && validEmail && validPhone && validType && validSeverity && validDesc && validFile && validConsent;
        if (!isValid && firstInvalid) {
          firstInvalid.focus({ preventScroll: false });
          const target = firstInvalid.closest("[data-field]");
          if (target) target.scrollIntoView({ behavior: prefersReducedMotion ? "auto" : "smooth", block: "center" });
        }
        return isValid;
      }

      if (form) {
        form.addEventListener("input", (event) => {
          const group = event.target.closest("[data-field]");
          if (group) clearFieldError(group.dataset.field);
        });
        form.addEventListener("change", (event) => {
          const group = event.target.closest("[data-field]");
          if (group) clearFieldError(group.dataset.field);
        });
      }

      if (descInput && descCount) {
        descInput.addEventListener("input", () => {
          descCount.textContent = descInput.value.length + " / 2000";
        });
      }

      function resetFileUI() {
        if (fileNameEl) fileNameEl.textContent = "Klik untuk mengunggah log atau tangkapan layar";
        if (fileMetaEl) fileMetaEl.textContent = "LOG, TXT, PNG, JPG, PDF, ZIP · Maks. 10 MB";
        if (evidenceInput) evidenceInput.value = "";
      }

      if (evidenceInput) {
        evidenceInput.addEventListener("change", () => {
          const file = evidenceInput.files && evidenceInput.files[0];
          if (!file) {
            clearFieldError("file");
            resetFileUI();
            return;
          }
          if (file.size > 10 * 1024 * 1024) {
            setFieldError("file", "Ukuran berkas melebihi 10 MB. Pilih berkas yang lebih kecil.");
            showToast("Ukuran lampiran melebihi 10 MB.", "error");
            resetFileUI();
            return;
          }
          clearFieldError("file");
          const sizeMb = file.size / (1024 * 1024);
          if (fileNameEl) fileNameEl.textContent = file.name;
          if (fileMetaEl) {
            fileMetaEl.textContent = (sizeMb < 0.1 ? "< 0.1" : sizeMb.toFixed(2)) + " MB · Siap diunggah";
          }
        });
      }

      if (form) {
        form.addEventListener("submit", async (event) => {
          event.preventDefault();
          if (!validateForm()) {
            showToast("Periksa kembali field yang ditandai merah.", "error");
            return;
          }

          const type = $("#incidentType");
          const email = $("#reporterEmail");
          const severity = form.querySelector('input[name="severity"]:checked');
          const selectedType = type ? type.value : "-";
          const selectedSeverity = severity ? severity.value : "-";
          const selectedEmail = email ? email.value.trim() : "-";

          submitBtn.disabled = true;
          submitBtn.classList.add("loading");
          if (submitLabel) submitLabel.textContent = "Mengirim Laporan...";

          await delay(1500);

          const ticket = "#INC-" + year + "-" + String(Math.floor(1000 + Math.random() * 9000));
          lastTicket = ticket;

          try {
            const key = "radnetCsirtReports";
            const reports = JSON.parse(localStorage.getItem(key) || "[]");
            reports.push({
              ticket,
              type: selectedType,
              severity: selectedSeverity,
              email: selectedEmail,
              createdAt: new Date().toISOString()
            });
            localStorage.setItem(key, JSON.stringify(reports.slice(-25)));
          } catch (error) {
            /* Local storage may be unavailable; the demo flow still succeeds. */
          }

          const ticketEl = $("#ticketNumber");
          const typeEl = $("#successType");
          const severityEl = $("#successSeverity");
          const emailEl = $("#successEmail");
          const timeEl = $("#successTime");
          if (ticketEl) ticketEl.textContent = ticket;
          if (typeEl) typeEl.textContent = selectedType;
          if (severityEl) severityEl.textContent = selectedSeverity;
          if (emailEl) emailEl.textContent = selectedEmail;
          if (timeEl) {
            timeEl.textContent = new Intl.DateTimeFormat("id-ID", {
              timeZone: "Asia/Jakarta",
              day: "2-digit",
              month: "short",
              year: "numeric",
              hour: "2-digit",
              minute: "2-digit",
              hour12: false
            }).format(new Date()) + " WIB";
          }

          form.reset();
          $$("[data-field]", form).forEach((group) => group.classList.remove("invalid"));
          $$("[aria-invalid]", form).forEach((field) => field.removeAttribute("aria-invalid"));
          resetFileUI();
          if (descCount) descCount.textContent = "0 / 2000";
          submitBtn.disabled = false;
          submitBtn.classList.remove("loading");
          if (submitLabel) submitLabel.textContent = "Kirim Laporan Insiden";
          openModal(successModal);
        });
      }

      const copyTicketBtn = $("#copyTicketBtn");
      if (copyTicketBtn) {
        copyTicketBtn.addEventListener("click", async () => {
          if (!lastTicket) return;
          const ok = await copyText(lastTicket);
          showToast(ok ? "Kode tiket berhasil disalin." : "Gagal menyalin kode tiket.", ok ? undefined : "error");
        });
      }

      /* ---------- Advisories ---------- */
      const advisories = [
        {
          id: "RADNET-ADV-2026-006",
          title: "Kerentanan Eksekusi Kode Jarak Jauh pada Gateway VPN Edge",
          severity: "Critical",
          date: "28 Okt 2026",
          cve: "CVE-2026-31842",
          cvss: "CVSS 9.8",
          category: "Infrastruktur",
          summary: "Kerentanan remote code execution pada gateway VPN dapat dieksploitasi tanpa autentikasi untuk menjalankan perintah arbitrer pada perangkat yang terekspos ke internet.",
          affected: "Gateway VPN versi 8.2.x hingga 8.4.1 dengan fitur administrasi jarak jauh aktif.",
          action: "Segera terapkan patch vendor versi 8.4.2. Batasi akses panel administrasi, aktifkan MFA, dan periksa log akses mencurigakan pada 72 jam terakhir."
        },
        {
          id: "RADNET-ADV-2026-005",
          title: "Kampanye Phishing Bertema Reset Password Microsoft 365",
          severity: "High",
          date: "24 Okt 2026",
          cve: "Tanpa CVE",
          cvss: "CVSS 8.1",
          category: "Phishing",
          summary: "Kampanye phishing menargetkan pengguna Microsoft 365 melalui email reset password palsu yang mengarah ke halaman login tiruan untuk mencuri kredensial dan sesi.",
          affected: "Pengguna email korporat, khususnya akun dengan akses administratif.",
          action: "Aktifkan MFA, blokir domain pengirim yang teridentifikasi, dan lakukan reset kredensial bagi pengguna yang telah memasukkan data pada halaman mencurigakan."
        },
        {
          id: "RADNET-ADV-2026-004",
          title: "Eksploitasi Aktif Kerentanan Apache Struts 2",
          severity: "Critical",
          date: "21 Okt 2026",
          cve: "CVE-2026-30417",
          cvss: "CVSS 9.1",
          category: "Aplikasi",
          summary: "Aktivitas eksploitasi aktif terdeteksi pada aplikasi berbasis Apache Struts 2 yang belum dipatch, berpotensi menyebabkan eksekusi kode dari jarak jauh.",
          affected: "Aplikasi web berbasis Struts 2 versi 6.x sebelum pembaruan keamanan terbaru.",
          action: "Terapkan pembaruan vendor, lakukan review konfigurasi OGNL, dan cari indikator kompromi pada log aplikasi serta proses webserver."
        },
        {
          id: "RADNET-ADV-2026-003",
          title: "Peningkatan Serangan DDoS Volumetrik pada Layanan DNS",
          severity: "High",
          date: "17 Okt 2026",
          cve: "CVE-2026-27754",
          cvss: "CVSS 7.5",
          category: "Jaringan",
          summary: "Peningkatan signifikan serangan DDoS volumetrik teramati pada layanan DNS publik. Serangan memanfaatkan refleksi dan amplifikasi untuk membanjiri bandwidth target.",
          affected: "Layanan DNS publik, resolver terbuka, dan infrastruktur dengan konfigurasi rate limiting lemah.",
          action: "Aktifkan perlindungan DDoS, nonaktifkan rekursi terbuka, terapkan response rate limiting, dan koordinasikan mitigasi dengan penyedia transit."
        },
        {
          id: "RADNET-ADV-2026-002",
          title: "Infostealer Menyamar sebagai Pembaruan Browser Chrome",
          severity: "High",
          date: "12 Okt 2026",
          cve: "CVE-2026-31509",
          cvss: "CVSS 8.2",
          category: "Malware",
          summary: "Malware infostealer disebarkan melalui iklan pencarian palsu dan halaman unduhan tiruan yang menyerupai pembaruan browser resmi untuk mencuri kredensial tersimpan.",
          affected: "Pengguna Windows dan macOS yang mengunduh browser dari sumber tidak resmi.",
          action: "Hanya unduh browser dari situs resmi, hapus ekstensi mencurigakan, reset kata sandi akun penting, dan pindai endpoint dengan EDR terbaru."
        },
        {
          id: "RADNET-ADV-2026-001",
          title: "Information Disclosure pada Plugin WordPress Populer",
          severity: "Medium",
          date: "07 Okt 2026",
          cve: "CVE-2026-26811",
          cvss: "CVSS 6.5",
          category: "Aplikasi",
          summary: "Kerentanan information disclosure pada plugin WordPress memungkinkan pengguna tanpa hak istimewa mengakses data konfigurasi sensitif melalui endpoint yang tidak terproteksi.",
          affected: "Situs WordPress yang menggunakan plugin terkait versi 3.4.x hingga 3.6.0.",
          action: "Perbarui plugin ke versi 3.6.1, tinjau akses endpoint, dan rotasi kredensial yang mungkin terekspos dalam file konfigurasi."
        }
      ];

      /* ---------- Security announcement ticker ---------- */
      const tickerTrack = $("#tickerTrack");
      const tickerSeverity = {
        Critical: "critical",
        High: "high",
        Medium: "medium"
      };

      function renderTicker() {
        if (!tickerTrack) return;
        const items = advisories.map((item) => `
          <a class="ticker-item" href="#advisori" data-advisory-id="${item.id}" aria-label="Advisori ${item.severity}: ${item.title}">
            <span class="ticker-sev ${tickerSeverity[item.severity] || "medium"}">${item.severity}</span>
            <span class="ticker-text">${item.title}</span>
            <span class="ticker-meta">${item.cve} · ${item.date}</span>
          </a>
        `).join("");

        const sequence = document.createElement("span");
        sequence.className = "ticker-sequence";
        sequence.innerHTML = items;

        const clone = sequence.cloneNode(true);
        clone.setAttribute("aria-hidden", "true");
        clone.querySelectorAll("a").forEach((link) => {
          link.tabIndex = -1;
          link.setAttribute("aria-hidden", "true");
        });

        tickerTrack.replaceChildren(sequence, clone);
        tickerTrack.classList.add("is-animated");
      }

      renderTicker();

      const severityClass = {
        Critical: "sev-critical",
        High: "sev-high",
        Medium: "sev-medium"
      };

      const advisoryGrid = $("#advisoryGrid");
      const advisoryEmpty = $("#advisoryEmpty");
      const advisorySearch = $("#advisorySearch");
      const advisoryCount = $("#advisoryCount");
      const filterButtons = $$(".filter-btn[data-filter]");
      let activeFilter = "all";

      function renderAdvisories() {
        if (!advisoryGrid) return;
        const query = advisorySearch ? advisorySearch.value.trim().toLowerCase() : "";
        const filtered = advisories.filter((item) => {
          const matchesFilter = activeFilter === "all" || item.severity.toLowerCase() === activeFilter;
          const haystack = [item.id, item.title, item.cve, item.category, item.summary, item.affected].join(" ").toLowerCase();
          return matchesFilter && (!query || haystack.includes(query));
        });

        advisoryGrid.innerHTML = filtered.map((item) => `
          <article class="advisory-card">
            <div class="advisory-top">
              <span class="sev-badge ${severityClass[item.severity]}"><span class="dot"></span>${item.severity}</span>
              <span class="advisory-date">${item.date}</span>
            </div>
            <div class="advisory-id">${item.id} · ${item.cve}</div>
            <h3>${item.title}</h3>
            <p>${item.summary}</p>
            <div class="advisory-foot">
              <span class="advisory-cat">${item.category}</span>
              <button class="advisory-link" type="button" data-advisory-id="${item.id}" aria-label="Baca advisori ${item.title}">
                Baca Selengkapnya
                <svg class="icon" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
              </button>
            </div>
          </article>
        `).join("");

        if (advisoryCount) advisoryCount.textContent = String(filtered.length);
        if (advisoryEmpty) advisoryEmpty.classList.toggle("show", filtered.length === 0);
      }

      filterButtons.forEach((button) => {
        button.addEventListener("click", () => {
          activeFilter = button.dataset.filter || "all";
          filterButtons.forEach((other) => {
            const active = other === button;
            other.classList.toggle("active", active);
            other.setAttribute("aria-pressed", String(active));
          });
          renderAdvisories();
        });
      });

      if (advisorySearch) advisorySearch.addEventListener("input", renderAdvisories);

      function openAdvisoryDetail(item) {
        if (!item) return;
        const modal = $("#advisoryModal");
        const severityEl = $("#advisoryModalSeverity");
        if (severityEl) {
          severityEl.className = "sev-badge " + severityClass[item.severity];
          severityEl.textContent = item.severity;
        }
        $("#advisoryModalId").textContent = item.id;
        $("#advisoryModalTitle").textContent = item.title;
        $("#advisoryModalDate").textContent = item.date;
        $("#advisoryModalCve").textContent = item.cve;
        $("#advisoryModalCvss").textContent = item.cvss + " · " + item.category;
        $("#advisoryModalSummary").textContent = item.summary;
        $("#advisoryModalAffected").textContent = item.affected;
        $("#advisoryModalAction").textContent = item.action;
        openModal(modal);
      }

      function handleAdvisoryClick(event, preventNavigation) {
        const trigger = event.target.closest("[data-advisory-id]");
        if (!trigger) return;
        const item = advisories.find((advisory) => advisory.id === trigger.dataset.advisoryId);
        if (!item) return;
        if (preventNavigation) event.preventDefault();
        openAdvisoryDetail(item);
      }

      if (advisoryGrid) {
        advisoryGrid.addEventListener("click", (event) => handleAdvisoryClick(event, false));
      }

      if (tickerTrack) {
        tickerTrack.addEventListener("click", (event) => handleAdvisoryClick(event, true));
      }

      renderAdvisories();
      filterButtons.forEach((button) => button.setAttribute("aria-pressed", String(button.classList.contains("active"))));
    })();
  </script>
</body>
</html>
