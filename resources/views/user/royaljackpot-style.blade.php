<style>
/* ============================================================
   ROYAL JACKPOT — SHARED DESIGN SYSTEM (Jet Black + Gold, zero blue)
   ============================================================ */
body, .master-app-wrap, .hud-app-shell, .hud-scroll-area { background: #000 !important; }
.cyber-video-bg-container { background: #000 !important; }
.cyber-nebula-orb, #cyberMatrixCanvas { display: none !important; }
.mecha-inner-body {
    padding: 0 10px !important;
    gap: 12px !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
}

:root {
    --rj-gold: #F5A623;
    --rj-gold-hi: #FFD700;
    --rj-card: #0A0C11;
    --rj-line: rgba(255,255,255,.075);
    --rj-dim: #8A94A6;
    --rj-mute: #5B6475;
    --rj-green: #00FF88;
}

/* ---------- Tier themes ---------- */
.t1 { --accent: #D98A4A; --accent-soft: rgba(217,138,74,.14); --accent-line: rgba(217,138,74,.38); --accent-grad: linear-gradient(135deg,#E8A061 0%,#A9622B 100%); }
.t2 { --accent: #D5DCE6; --accent-soft: rgba(213,220,230,.12); --accent-line: rgba(213,220,230,.34); --accent-grad: linear-gradient(135deg,#F4F6F9 0%,#9AA5B5 100%); }
.t3 { --accent: #FFD23F; --accent-soft: rgba(255,210,63,.13); --accent-line: rgba(255,210,63,.38); --accent-grad: linear-gradient(135deg,#FFE680 0%,#F0A500 100%); }
.t4 { --accent: #F7A072; --accent-soft: rgba(247,160,114,.14); --accent-line: rgba(247,160,114,.40); --accent-grad: linear-gradient(135deg,#FFC09A 0%,#E4572E 100%); }

/* ---------- Slim top strip (breadcrumb + back) ---------- */
.rjx-strip {
    margin: 6px 10px 2px;
    display: flex; align-items: center; justify-content: space-between; gap: 10px;
}
.rjx-crumbs { font-size: 10px; font-weight: 600; letter-spacing: .5px; text-transform: uppercase; color: var(--rj-mute); }
.rjx-crumbs a { color: var(--rj-dim); text-decoration: none; }
.rjx-crumbs a:hover { color: var(--rj-gold); }
.rjx-crumbs b { color: var(--rj-gold); font-weight: 700; }
.rjx-back {
    display: inline-flex; align-items: center; gap: 6px;
    height: 28px; padding: 0 11px; border-radius: 8px; text-decoration: none;
    background: #0A0C11; border: 1px solid var(--rj-line); color: #CBD5E1;
    font-size: 11px; font-weight: 700; transition: .15s;
}
.rjx-back:hover { border-color: var(--rj-gold); color: var(--rj-gold); }

/* ---------- Hero (lobby) ---------- */
.rjx-hero {
    position: relative; overflow: hidden;
    background: linear-gradient(160deg, #11100A 0%, #0A0B0F 55%, #060608 100%);
    border: 1px solid rgba(245,166,35,.28);
    border-radius: 16px; padding: 16px 16px 14px;
    box-shadow: 0 10px 40px rgba(0,0,0,.8), inset 0 1px 0 rgba(255,215,0,.08);
}
.rjx-hero::before {
    content: ''; position: absolute; top: -70px; right: -50px; width: 220px; height: 220px; border-radius: 50%;
    background: radial-gradient(circle, rgba(245,166,35,.22) 0%, transparent 65%); pointer-events: none;
}
.rjx-hero-head { display: flex; align-items: center; gap: 12px; position: relative; }
.rjx-crown {
    width: 46px; height: 46px; border-radius: 13px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 20px; color: #1A1203;
    background: linear-gradient(135deg, #FFE680 0%, #F0A500 100%);
    box-shadow: 0 6px 18px rgba(245,166,35,.35);
}
.rjx-hero h1 { margin: 0; font-size: 22px; font-weight: 900; letter-spacing: .3px; color: #fff; line-height: 1.1; }
.rjx-hero h1 span { background: linear-gradient(135deg,#FFE680,#F5A623); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.rjx-hero p { margin: 3px 0 0; font-size: 11px; color: var(--rj-dim); font-weight: 500; }

.rjx-wallet {
    position: relative; margin-top: 14px;
    display: grid; grid-template-columns: 1.25fr 1fr 1fr; gap: 8px;
}
.rjx-wbox {
    background: rgba(0,0,0,.55); border: 1px solid var(--rj-line); border-radius: 12px; padding: 9px 11px;
    display: flex; flex-direction: column; justify-content: center; min-width: 0;
}
.rjx-wbox.main { border-color: rgba(0,255,136,.28); background: linear-gradient(160deg, rgba(0,255,136,.07), rgba(0,0,0,.6)); }
.rjx-wlbl { font-size: 9px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--rj-dim); }
.rjx-wval { font-size: 17px; font-weight: 900; color: #fff; line-height: 1.2; white-space: nowrap; }
.rjx-wbox.main .rjx-wval { color: var(--rj-green); }
.rjx-wval small { font-size: 9px; color: var(--rj-dim); font-weight: 700; }
.rjx-claim {
    margin-top: 6px; height: 26px; border: none; border-radius: 7px; cursor: pointer;
    background: linear-gradient(135deg,#FFE680,#F0A500); color: #1A1203; font-size: 10.5px; font-weight: 900;
    letter-spacing: .4px; text-transform: uppercase; transition: .15s;
}
.rjx-claim:hover:not(:disabled) { filter: brightness(1.1); transform: translateY(-1px); }
.rjx-claim:disabled { background: rgba(255,255,255,.07); color: var(--rj-mute); cursor: not-allowed; }

/* ---------- Section title ---------- */
.rjx-sec { display: flex; align-items: center; justify-content: space-between; margin: 4px 2px -2px; }
.rjx-sec h2 { margin: 0; font-size: 12px; font-weight: 800; letter-spacing: .8px; text-transform: uppercase; color: #fff; display: flex; align-items: center; gap: 8px; }
.rjx-sec h2 i { color: var(--rj-gold); }
.rjx-sec .live { font-size: 10px; color: var(--rj-dim); font-weight: 600; display: flex; align-items: center; gap: 5px; }
.rjx-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--rj-green); box-shadow: 0 0 8px var(--rj-green); animation: rjxPulse 1.6s infinite; display: inline-block; }
@keyframes rjxPulse { 0%,100% { opacity: 1; } 50% { opacity: .35; } }

/* ---------- Pool cards (lobby) 2x2 ---------- */
.rjx-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
.rjx-card {
    position: relative; overflow: hidden; display: flex; flex-direction: column; gap: 9px;
    background: linear-gradient(170deg, #0D0F15 0%, #07080C 100%);
    border: 1px solid var(--rj-line); border-radius: 15px; padding: 12px 12px 12px;
    text-decoration: none !important; color: inherit; transition: .2s ease;
}
.rjx-card::before { content: ''; position: absolute; left: 0; right: 0; top: 0; height: 3px; background: var(--accent-grad); }
.rjx-card::after {
    content: ''; position: absolute; top: -40px; right: -40px; width: 120px; height: 120px; border-radius: 50%;
    background: radial-gradient(circle, var(--accent-soft) 0%, transparent 70%); pointer-events: none;
}
.rjx-card:hover { transform: translateY(-3px); border-color: var(--accent-line); box-shadow: 0 12px 30px rgba(0,0,0,.85); }
.rjx-card-top { display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1; }
.rjx-tier {
    display: inline-flex; align-items: center; gap: 5px; font-size: 9.5px; font-weight: 800; letter-spacing: .5px;
    text-transform: uppercase; color: var(--accent); background: var(--accent-soft);
    border: 1px solid var(--accent-line); padding: 3px 7px; border-radius: 6px;
}
.rjx-live { font-size: 9.5px; font-weight: 700; color: var(--rj-dim); display: flex; align-items: center; gap: 4px; }
.rjx-cname { font-size: 10.5px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--rj-dim); position: relative; z-index: 1; margin-bottom: -4px; }
.rjx-price { font-size: 30px; font-weight: 900; line-height: 1; color: #fff; position: relative; z-index: 1; display: flex; align-items: baseline; gap: 4px; }
.rjx-price small { font-size: 10px; font-weight: 700; color: var(--rj-mute); }
.rjx-win {
    position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: center; gap: 6px;
    background: rgba(0,0,0,.55); border: 1px solid var(--rj-line); border-radius: 9px; padding: 6px 9px;
}
.rjx-win span { font-size: 9.5px; font-weight: 600; color: var(--rj-dim); line-height: 1.15; }
.rjx-win b { font-size: 14px; font-weight: 900; color: var(--accent); white-space: nowrap; }
.rjx-prog { position: relative; z-index: 1; }
.rjx-track { height: 5px; background: rgba(255,255,255,.08); border-radius: 5px; overflow: hidden; }
.rjx-fill { height: 100%; border-radius: 5px; background: var(--accent-grad); transition: width .5s ease; }
.rjx-pmeta { display: flex; justify-content: space-between; margin-top: 5px; font-size: 10px; font-weight: 600; color: var(--rj-dim); }
.rjx-pmeta b { color: #fff; font-weight: 800; }
.rjx-cta {
    position: relative; z-index: 1; margin-top: auto; height: 34px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    background: var(--accent-grad); color: #150E02; font-size: 11.5px; font-weight: 900; letter-spacing: .3px;
    transition: .15s;
}
.rjx-card:hover .rjx-cta { filter: brightness(1.08); }
.rjx-cta i { transition: transform .2s; }
.rjx-card:hover .rjx-cta i { transform: translateX(3px); }
.rjx-mine {
    position: absolute; z-index: 2; top: 38px; right: 10px; font-size: 9px; font-weight: 800; color: #0A0A0A;
    background: var(--rj-green); border-radius: 20px; padding: 2px 7px;
}

/* ---------- Steps ---------- */
.rjx-steps { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
.rjx-step { background: var(--rj-card); border: 1px solid var(--rj-line); border-radius: 12px; padding: 10px 8px; text-align: center; }
.rjx-step i { font-size: 16px; color: var(--rj-gold); margin-bottom: 6px; }
.rjx-step b { display: block; font-size: 10.5px; font-weight: 800; color: #fff; margin-bottom: 2px; }
.rjx-step span { font-size: 9.5px; color: var(--rj-dim); line-height: 1.3; display: block; }

/* ---------- Tabs ---------- */
.rjx-panel { background: var(--rj-card); border: 1px solid var(--rj-line); border-radius: 14px; overflow: hidden; }
.rjx-tabs { display: flex; background: #000; border-bottom: 1px solid var(--rj-line); overflow-x: auto; scrollbar-width: none; }
.rjx-tabs::-webkit-scrollbar { display: none; }
.rjx-tab {
    flex: 1; background: transparent; border: none; color: var(--rj-dim); padding: 11px 12px; font-size: 11px; font-weight: 700;
    cursor: pointer; border-bottom: 2px solid transparent; white-space: nowrap; display: flex; justify-content: center; align-items: center; gap: 6px; transition: .15s;
}
.rjx-tab.active { color: var(--rj-gold); border-bottom-color: var(--rj-gold); background: rgba(245,166,35,.06); }
.rjx-scroll { overflow-x: auto; max-height: 300px; }
.rjx-table { width: 100%; border-collapse: collapse; font-size: 11px; }
.rjx-table th {
    padding: 9px 12px; font-size: 9.5px; font-weight: 700; color: var(--rj-mute); text-transform: uppercase; letter-spacing: .5px;
    text-align: left; background: #000; border-bottom: 1px solid var(--rj-line); position: sticky; top: 0; white-space: nowrap;
}
.rjx-table td { padding: 9px 12px; color: #CBD5E1; border-bottom: 1px solid rgba(255,255,255,.04); white-space: nowrap; }
.rjx-table tr:hover td { background: rgba(255,255,255,.02); }
.rjx-empty { text-align: center; padding: 26px 14px; color: var(--rj-mute); font-size: 12px; }
.rjx-empty i { display: block; font-size: 26px; opacity: .35; margin-bottom: 8px; }
.rjx-pill { display: inline-block; font-size: 9.5px; font-weight: 800; padding: 2px 8px; border-radius: 20px; }
.rjx-pill.ok { background: rgba(0,255,136,.12); color: var(--rj-green); border: 1px solid rgba(0,255,136,.3); }
.rjx-pill.gold { background: rgba(245,166,35,.12); color: var(--rj-gold); border: 1px solid rgba(245,166,35,.32); }
.rjx-pill.dim { background: rgba(255,255,255,.05); color: var(--rj-dim); }

/* ---------- Modals (claim only) ---------- */
.rjx-modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.94); backdrop-filter: blur(6px); z-index: 99999; align-items: center; justify-content: center; padding: 14px; }
.rjx-modal-bg.active { display: flex; }
.rjx-modal { background: #0A0C11; border: 1px solid rgba(245,166,35,.35); border-radius: 16px; width: 100%; max-width: 420px; padding: 18px; box-shadow: 0 24px 70px rgba(0,0,0,.95); }
.rjx-mhead { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--rj-line); }
.rjx-mhead h3 { margin: 0; font-size: 14px; font-weight: 800; color: #fff; }
.rjx-x { background: transparent; border: none; color: var(--rj-dim); font-size: 16px; cursor: pointer; }
.rjx-btn {
    height: 38px; padding: 0 16px; border: none; border-radius: 10px; cursor: pointer;
    background: linear-gradient(135deg,#FFE680,#F0A500); color: #1A1203; font-size: 12px; font-weight: 900;
    display: inline-flex; align-items: center; justify-content: center; gap: 7px; text-decoration: none; transition: .15s;
}
.rjx-btn:hover:not(:disabled) { filter: brightness(1.1); }
.rjx-btn:disabled { opacity: .6; cursor: not-allowed; }
.rjx-btn.ghost { background: #000; border: 1px solid var(--rj-line); color: #CBD5E1; }
.rjx-btn.red { background: linear-gradient(135deg,#FF7A7A,#E03131); color: #fff; }

/* Keep 2x2 grid for mobile HUD shell */
.rjx-grid { grid-template-columns: repeat(2, 1fr) !important; }

/* ============================================================
   SWEETALERT2: CRUST-FREE PRISTINE TICK ICON (TICK KA NISHAN)
   ============================================================ */
.swal2-popup {
    background: #0A0C11 !important;
    border: 1px solid rgba(245, 166, 35, 0.45) !important;
    border-radius: 18px !important;
    box-shadow: 0 20px 60px rgba(0,0,0,0.95), 0 0 30px rgba(245, 166, 35, 0.15) !important;
    padding: 22px 18px 20px !important;
    max-width: 340px !important;
}
.swal2-title {
    font-size: 17px !important;
    font-weight: 900 !important;
    letter-spacing: .5px !important;
    color: #FFD700 !important;
    text-transform: uppercase !important;
    margin: 8px 0 6px !important;
    padding: 0 !important;
}
.swal2-html-container {
    font-size: 12px !important;
    color: #CBD5E1 !important;
    line-height: 1.45 !important;
    margin: 4px 0 16px !important;
    padding: 0 !important;
}
.swal2-icon {
    width: 60px !important;
    height: 60px !important;
    margin: 6px auto 10px auto !important;
    border-width: 2.5px !important;
    border-style: solid !important;
    border-radius: 50% !important;
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    box-sizing: border-box !important;
    overflow: visible !important;
}

/* SUCCESS TICK MARK */
.swal2-icon.swal2-success {
    border-color: #00FF88 !important;
    color: #00FF88 !important;
    background: radial-gradient(circle, rgba(0, 255, 136, 0.18) 0%, rgba(0, 0, 0, 0.5) 75%) !important;
    box-shadow: 0 0 24px rgba(0, 255, 136, 0.4), inset 0 0 12px rgba(0, 255, 136, 0.15) !important;
}
.swal2-icon.swal2-success .swal2-success-circular-line-left,
.swal2-icon.swal2-success .swal2-success-circular-line-right,
.swal2-icon.swal2-success .swal2-success-fix,
.swal2-icon.swal2-success .swal2-success-ring,
.swal2-icon.swal2-success [class^='swal2-success-line'] {
    display: none !important;
}
.swal2-icon.swal2-success::after {
    content: '' !important;
    display: block !important;
    width: 32px !important;
    height: 32px !important;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300FF88' stroke-width='3.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E") no-repeat center / contain !important;
    filter: drop-shadow(0 0 8px rgba(0, 255, 136, 0.85)) !important;
    animation: swalTickBounce 0.42s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards !important;
}
@keyframes swalTickBounce {
    0% { transform: scale(0.2); opacity: 0; }
    60% { transform: scale(1.18); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
}

/* ERROR ICON */
.swal2-icon.swal2-error {
    border-color: #EF4444 !important;
    color: #EF4444 !important;
    background: radial-gradient(circle, rgba(239, 68, 68, 0.18) 0%, rgba(0, 0, 0, 0.5) 75%) !important;
    box-shadow: 0 0 24px rgba(239, 68, 68, 0.4) !important;
}
.swal2-icon.swal2-error .swal2-x-mark,
.swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
    display: none !important;
}
.swal2-icon.swal2-error::after {
    content: '' !important;
    display: block !important;
    width: 28px !important;
    height: 28px !important;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23EF4444' stroke-width='3.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='18' y1='6' x2='6' y2='18'%3E%3C/line%3E%3Cline x1='6' y1='6' x2='18' y2='18'%3E%3C/line%3E%3C/svg%3E") no-repeat center / contain !important;
    filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.8)) !important;
    animation: swalTickBounce 0.42s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards !important;
}

.swal2-confirm {
    background: linear-gradient(135deg, #FFE680 0%, #F5A623 50%, #D48806 100%) !important;
    color: #0A0A0A !important;
    font-weight: 800 !important;
    border-radius: 10px !important;
    height: 40px !important;
    font-size: 13px !important;
    letter-spacing: .5px !important;
    padding: 0 24px !important;
    box-shadow: 0 6px 18px rgba(245, 166, 35, 0.35) !important;
}
.swal2-cancel[style*="display: none"],
.swal2-styled.swal2-cancel[style*="display: none"] {
    display: none !important;
}
</style>
