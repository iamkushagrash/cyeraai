<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyera AI — CAI Token | The Future of Decentralized Intelligence</title>
    <meta name="description"
        content="CAI Token is the native BEP-20 digital asset powering the Cyera AI decentralized protocol on BNB Smart Chain. Total Supply: 300000 CAI.">
    <meta property="og:title" content="Cyera AI — CAI Token">
    <meta property="og:description" content="Power the Future. Own the Protocol. CAI Token on BNB Smart Chain. Total Supply: 300000 CAI.">
    <meta property="og:image" content="{{ asset('images/cai-token-coin.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cai-spartan-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Space+Mono:wght@400;700&family=Rajdhani:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --gold: #F5A623;
            --gold-bright: #FFD700;
            --green: #00FF88;
            --cyan: #00D2FF;
            --purple: #8B5CF6;
            --bg: #02030A;
            --card-bg: rgba(10, 13, 25, 0.88);
            --border: rgba(245, 166, 35, 0.18);
        }
        html { scroll-behavior: smooth; }
        body { background: var(--bg); color: #fff; font-family: 'Outfit', sans-serif; overflow-x: hidden; line-height: 1.5; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(#F5A623, #FFD700); border-radius: 10px; }

        /* === UNIVERSAL BG === */
        .universe-bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .universe-bg canvas { position: absolute; inset: 0; }
        .orb { position: absolute; border-radius: 50%; filter: blur(75px); animation: orbF 14s ease-in-out infinite alternate; pointer-events: none; }
        .orb1 { width:480px;height:480px;background:radial-gradient(circle,rgba(245,166,35,.16),transparent 70%);top:-120px;left:-100px; }
        .orb2 { width:400px;height:400px;background:radial-gradient(circle,rgba(0,210,255,.12),transparent 70%);top:40%;right:-80px;animation-duration:10s;animation-delay:-4s; }
        .orb3 { width:340px;height:340px;background:radial-gradient(circle,rgba(139,92,246,.12),transparent 70%);bottom:10%;left:15%;animation-duration:17s;animation-delay:-8s; }
        @keyframes orbF { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(40px,50px) scale(1.1)} }

        /* === NAVBAR === */
        .nav { position:fixed;top:0;left:0;right:0;z-index:1000;padding:10px 28px;display:flex;align-items:center;justify-content:space-between;background:rgba(2,3,10,.85);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid rgba(245,166,35,.15);transition:.3s; }
        .nav.scrolled { background:rgba(2,3,10,.96);border-bottom-color:rgba(245,166,35,.28);box-shadow:0 6px 24px rgba(0,0,0,.6); }
        .nav-logo { display:flex;align-items:center;gap:8px;text-decoration:none; }
        .nav-logo img { width:28px;height:28px;border-radius:50%;object-fit:cover;box-shadow:0 0 10px rgba(245,166,35,.4); }
        .nav-logo-txt { font-family:'Rajdhani',sans-serif;font-size:1.15rem;font-weight:800;background:linear-gradient(135deg,#FFD700,#F5A623);-webkit-background-clip:text;-webkit-text-fill-color:transparent;letter-spacing:1px; }
        .nav-links { display:flex;align-items:center;gap:22px;list-style:none; }
        .nav-links a { color:rgba(255,255,255,.7);text-decoration:none;font-size:.8rem;font-weight:600;letter-spacing:.5px;transition:color .2s;text-transform:uppercase; }
        .nav-links a:hover { color:#FFD700; }
        .nav-actions { display:flex;align-items:center; }
        .btn-launch { display:inline-flex;align-items:center;gap:7px;padding:7px 16px;background:linear-gradient(135deg,#F5A623,#FFD700);border-radius:8px;color:#000;text-decoration:none;font-size:.78rem;font-weight:800;letter-spacing:.4px;box-shadow:0 0 14px rgba(245,166,35,.35);transition:.25s; }
        .btn-launch:hover { transform:translateY(-1px);box-shadow:0 4px 18px rgba(245,166,35,.6);color:#000; }

        /* === HERO === */
        .hero { min-height:85vh;display:flex;align-items:center;justify-content:center;position:relative;z-index:1;padding:84px 16px 36px;text-align:center;overflow:hidden; }
        .hero-grid { position:absolute;inset:0;background-image:linear-gradient(rgba(245,166,35,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(245,166,35,.03) 1px,transparent 1px);background-size:40px 40px;animation:gridDrift 24s linear infinite; }
        @keyframes gridDrift { 0%{background-position:0 0} 100%{background-position:40px 40px} }
        .scanline { position:absolute;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(245,166,35,.5),transparent);animation:scanD 4s ease-in-out infinite; }
        @keyframes scanD { 0%{top:0%;opacity:0} 20%{opacity:1} 80%{opacity:1} 100%{top:100%;opacity:0} }
        .hero-inner { position:relative;z-index:2;max-width:780px;margin:0 auto;width:100%; }

        /* Compact Token Stage */
        .token-stage { position:relative;width:145px;height:145px;margin:0 auto 18px; }
        .ring-outer { position:absolute;inset:-14px;border-radius:50%;border:1px solid rgba(245,166,35,.3);animation:spinR 20s linear infinite; }
        .ring-outer::before { content:'';position:absolute;width:8px;height:8px;background:#FFD700;border-radius:50%;top:-4px;left:50%;transform:translateX(-50%);box-shadow:0 0 12px #FFD700; }
        .ring-mid { position:absolute;inset:-28px;border-radius:50%;border:1px solid rgba(0,210,255,.22);animation:spinR 30s linear infinite reverse; }
        .ring-mid::before { content:'';position:absolute;width:6px;height:6px;background:#00D2FF;border-radius:50%;bottom:-3px;left:50%;transform:translateX(-50%);box-shadow:0 0 10px #00D2FF; }
        .token-halo { position:absolute;inset:-10px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.3) 0%,rgba(245,166,35,.06) 60%,transparent 75%);animation:haloP 3s ease-in-out infinite alternate; }
        @keyframes haloP { 0%{opacity:.6;transform:scale(.96)} 100%{opacity:1;transform:scale(1.06)} }
        @keyframes spinR { 0%{transform:rotate(0deg)} 100%{transform:rotate(360deg)} }
        .token-coin { position:absolute;inset:0;border-radius:50%;overflow:hidden;animation:coinF 4.5s ease-in-out infinite alternate;box-shadow:0 0 35px rgba(245,166,35,.45),0 12px 30px rgba(0,0,0,.75); }
        @keyframes coinF { 0%{transform:translateY(0) rotate(-1.5deg)} 100%{transform:translateY(-10px) rotate(1.5deg)} }
        .token-coin img { width:100%;height:100%;object-fit:cover; }
        .pt { position:absolute;border-radius:50%;animation:ptD linear infinite;opacity:.7; }
        @keyframes ptD { 0%{transform:translateY(0) scale(1);opacity:.7} 50%{opacity:1} 100%{transform:translateY(-80px) scale(0);opacity:0} }

        .eyebrow { display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(245,166,35,.09);border:1px solid rgba(245,166,35,.28);border-radius:14px;font-size:.67rem;font-weight:800;color:#FFD700;letter-spacing:1.2px;text-transform:uppercase;margin-bottom:12px;box-shadow:0 0 16px rgba(245,166,35,.15); }
        .pdot { width:5px;height:5px;border-radius:50%;background:#00FF88;box-shadow:0 0 6px #00FF88;animation:pdotA 1.5s infinite alternate; }
        @keyframes pdotA { 0%{transform:scale(.8);opacity:.6} 100%{transform:scale(1.3);opacity:1;box-shadow:0 0 10px #00FF88} }

        .hero-title { font-family:'Rajdhani',sans-serif;font-size:clamp(1.9rem,4vw,3.2rem);font-weight:900;line-height:1.1;margin-bottom:10px;letter-spacing:-0.5px; }
        .grad-txt { background:linear-gradient(135deg,#fff 10%,#FFD700 45%,#F5A623 75%,#fff 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text; }
        .hero-sub { font-size:clamp(0.82rem,1.4vw,0.98rem);color:rgba(255,255,255,.65);max-width:540px;margin:0 auto 18px;line-height:1.55; }
        .hero-btns { display:flex;align-items:center;justify-content:center;gap:10px;flex-wrap:wrap;margin-bottom:18px; }
        .btn-primary { display:inline-flex;align-items:center;gap:7px;padding:10px 24px;background:linear-gradient(135deg,#F5A623,#FFD700);border-radius:8px;color:#000;text-decoration:none;font-size:.84rem;font-weight:800;box-shadow:0 0 20px rgba(245,166,35,.45);transition:.25s;position:relative;overflow:hidden; }
        .btn-primary::before { content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.3),transparent);transition:left .5s; }
        .btn-primary:hover::before { left:100%; }
        .btn-primary:hover { transform:translateY(-2px);box-shadow:0 0 30px rgba(245,166,35,.65); }
        .btn-ghost { display:inline-flex;align-items:center;gap:7px;padding:10px 22px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.18);border-radius:8px;color:#fff;text-decoration:none;font-size:.84rem;font-weight:700;transition:.25s;backdrop-filter:blur(8px); }
        .btn-ghost:hover { border-color:rgba(245,166,35,.5);background:rgba(245,166,35,.08);color:#FFD700;transform:translateY(-2px); }

        /* === SMART CONTRACT BADGES (HERO & TECH) === */
        .contracts-strip { display:grid;grid-template-columns:1fr 1fr;gap:10px;max-width:680px;margin:0 auto 20px; }
        .contract-pill { background:rgba(10,13,25,.82);border:1px solid rgba(245,166,35,.22);border-radius:10px;padding:8px 12px;display:flex;align-items:center;justify-content:space-between;gap:8px;backdrop-filter:blur(10px);transition:.25s; }
        .contract-pill:hover { border-color:rgba(245,166,35,.5);background:rgba(245,166,35,.06);box-shadow:0 0 16px rgba(245,166,35,.15); }
        .cp-left { display:flex;align-items:center;gap:8px;text-align:left;overflow:hidden;min-width:0; }
        .cp-icon { width:26px;height:26px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0; }
        .cp-icon.token { background:rgba(245,166,35,.15);color:#FFD700;border:1px solid rgba(245,166,35,.3); }
        .cp-icon.invest { background:rgba(0,255,136,.12);color:#00FF88;border:1px solid rgba(0,255,136,.28); }
        .cp-meta { overflow:hidden;min-width:0; }
        .cp-tag { font-size:.58rem;font-weight:800;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.6px;line-height:1; }
        .cp-addr { font-family:'Space Mono',monospace;font-size:.74rem;color:#FFD700;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px; }
        .cp-actions { display:flex;align-items:center;gap:5px;flex-shrink:0; }
        .btn-cp-action { width:26px;height:26px;border-radius:6px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05);color:rgba(255,255,255,.7);display:flex;align-items:center;justify-content:center;font-size:11px;cursor:pointer;text-decoration:none;transition:.2s;position:relative; }
        .btn-cp-action:hover { border-color:#FFD700;background:rgba(245,166,35,.18);color:#FFD700; }
        
        .copy-toast { position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(50px);background:linear-gradient(135deg,#0a0d19,#12182e);border:1px solid #00FF88;color:#00FF88;padding:8px 18px;border-radius:20px;font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:8px;box-shadow:0 8px 30px rgba(0,255,136,.3);z-index:9999;opacity:0;pointer-events:none;transition:all .3s cubic-bezier(.175,.885,.32,1.275); }
        .copy-toast.show { opacity:1;transform:translateX(-50%) translateY(0); }

        .stats-bar { margin-top:20px;display:grid;grid-template-columns:repeat(5,1fr);background:rgba(10,13,25,.82);border:1px solid rgba(245,166,35,.15);border-radius:12px;backdrop-filter:blur(14px);overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.4); }
        .stat-item { padding:10px 8px;text-align:center;position:relative; }
        .stat-item:not(:last-child)::after { content:'';position:absolute;right:0;top:20%;height:60%;width:1px;background:rgba(245,166,35,.12); }
        .stat-num { font-family:'Space Mono',monospace;font-size:1.05rem;font-weight:700;color:#FFD700;line-height:1.2; }
        .stat-lbl { font-size:.62rem;color:rgba(255,255,255,.5);font-weight:600;letter-spacing:.6px;text-transform:uppercase;margin-top:2px; }

        .scroll-down { position:absolute;bottom:10px;left:50%;transform:translateX(-50%);color:rgba(255,255,255,.3);cursor:pointer;animation:sdB 2s ease-in-out infinite; }
        @keyframes sdB { 0%,100%{transform:translateX(-50%) translateY(0);opacity:.3} 50%{transform:translateX(-50%) translateY(5px);opacity:.65} }

        /* === SHARED SECTION STYLES === */
        section { position:relative;z-index:1; }
        .sec-pad { padding:52px 18px; }
        .container { max-width:980px;margin:0 auto;width:100%; }
        .sec-eyebrow { display:inline-flex;align-items:center;gap:6px;padding:3px 9px;background:rgba(245,166,35,.08);border:1px solid rgba(245,166,35,.22);border-radius:12px;font-size:.65rem;font-weight:800;color:#F5A623;letter-spacing:1.2px;text-transform:uppercase;margin-bottom:8px; }
        .sec-title { font-family:'Rajdhani',sans-serif;font-size:clamp(1.55rem,3vw,2.4rem);font-weight:800;color:#fff;line-height:1.15;margin-bottom:10px;letter-spacing:-0.3px; }
        .sec-title span { background:linear-gradient(135deg,#FFD700,#F5A623);-webkit-background-clip:text;-webkit-text-fill-color:transparent; }
        .sec-desc { font-size:.86rem;color:rgba(255,255,255,.6);line-height:1.6;max-width:540px; }
        .divider { height:1px;background:linear-gradient(90deg,transparent,rgba(245,166,35,.12),transparent);margin:0 16px; }

        /* === ABOUT === */
        .about-bg { background:linear-gradient(180deg,transparent,rgba(245,166,35,.02) 50%,transparent); }
        .about-grid { display:grid;grid-template-columns:260px 1fr;gap:36px;align-items:center; }
        .about-vis { position:relative;display:flex;justify-content:center; }
        .about-coin-wrap { width:190px;height:190px;margin:0 auto;position:relative; }
        .about-coin-wrap img { width:100%;height:100%;object-fit:cover;border-radius:50%;animation:coinF 5s ease-in-out infinite alternate;box-shadow:0 0 36px rgba(245,166,35,.3); }
        .a-ring { position:absolute;border-radius:50%;border:1px dashed rgba(245,166,35,.25);animation:spinR linear infinite; }
        .a-ring1 { inset:-18px;animation-duration:25s; }
        .a-ring2 { inset:-36px;animation-duration:40s;animation-direction:reverse;border-color:rgba(0,210,255,.18); }
        .fl-badge { position:absolute;background:rgba(10,13,25,.94);border:1px solid rgba(245,166,35,.28);border-radius:8px;padding:5px 10px;backdrop-filter:blur(8px);animation:bF 4s ease-in-out infinite alternate;box-shadow:0 4px 14px rgba(0,0,0,.6); }
        @keyframes bF { 0%{transform:translateY(0)} 100%{transform:translateY(-5px)} }
        .fl-badge.b1 { top:10px;right:-15px; }
        .fl-badge.b2 { bottom:20px;left:-15px;animation-delay:-2s; }
        .bv { font-family:'Space Mono',monospace;font-size:.74rem;font-weight:700;color:#FFD700; }
        .bl { font-size:.58rem;color:rgba(255,255,255,.5);font-weight:600;text-transform:uppercase;letter-spacing:.4px; }
        .about-feats { display:flex;flex-direction:column;gap:10px;margin-top:20px; }
        .feat-row { display:flex;align-items:flex-start;gap:10px;padding:10px 12px;background:rgba(10,13,25,.55);border:1px solid rgba(255,255,255,.05);border-radius:10px;transition:.2s; }
        .feat-row:hover { border-color:rgba(245,166,35,.22);background:rgba(245,166,35,.03);transform:translateX(3px); }
        .feat-ico { width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0; }
        .ico-gold { background:rgba(245,166,35,.15);color:#FFD700; }
        .ico-green { background:rgba(0,255,136,.1);color:#00FF88; }
        .ico-cyan { background:rgba(0,210,255,.1);color:#00D2FF; }
        .ft { font-size:.82rem;font-weight:700;color:#fff; }
        .fd { font-size:.73rem;color:rgba(255,255,255,.5);line-height:1.45;margin-top:1px; }

        /* === ECOSYSTEM === */
        .eco-bg { background:radial-gradient(ellipse at 50% 50%,rgba(139,92,246,.04),transparent 70%); }
        .eco-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:32px; }
        .eco-card { background:rgba(10,13,25,.75);border:1px solid rgba(255,255,255,.06);border-radius:14px;padding:18px 16px;position:relative;overflow:hidden;transition:.25s; }
        .eco-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--cc,#FFD700),transparent);opacity:0;transition:.25s; }
        .eco-card:hover { transform:translateY(-3px);border-color:rgba(255,255,255,.12); }
        .eco-card:hover::before { opacity:1; }
        .eco-glow { position:absolute;bottom:-30px;right:-30px;width:90px;height:90px;border-radius:50%;filter:blur(40px);opacity:.12; }
        .eco-icon { width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;margin-bottom:12px; }
        .eco-t { font-family:'Rajdhani',sans-serif;font-size:1.02rem;font-weight:700;color:#fff;margin-bottom:4px; }
        .eco-d { font-size:.76rem;color:rgba(255,255,255,.5);line-height:1.5; }
        .c-gold { --cc:#FFD700; } .c-gold .eco-glow { background:#FFD700; } .c-gold .eco-icon { background:rgba(255,215,0,.12);color:#FFD700; }
        .c-cyan { --cc:#00D2FF; } .c-cyan .eco-glow { background:#00D2FF; } .c-cyan .eco-icon { background:rgba(0,210,255,.1);color:#00D2FF; }
        .c-green { --cc:#00FF88; } .c-green .eco-glow { background:#00FF88; } .c-green .eco-icon { background:rgba(0,255,136,.1);color:#00FF88; }
        .c-purple { --cc:#8B5CF6; } .c-purple .eco-glow { background:#8B5CF6; } .c-purple .eco-icon { background:rgba(139,92,246,.12);color:#8B5CF6; }
        .c-amber { --cc:#F59E0B; } .c-amber .eco-glow { background:#F59E0B; } .c-amber .eco-icon { background:rgba(245,158,11,.12);color:#F59E0B; }
        .c-pink { --cc:#EC4899; } .c-pink .eco-glow { background:#EC4899; } .c-pink .eco-icon { background:rgba(236,72,153,.12);color:#EC4899; }


        /* === ROADMAP === */
        .rm-bg { background:radial-gradient(ellipse at 30% 50%,rgba(0,210,255,.04),transparent 60%); }
        .rm-timeline { position:relative;max-width:700px;margin:36px auto 0; }
        .rm-line { position:absolute;left:50%;top:0;bottom:0;width:2px;background:linear-gradient(180deg,rgba(245,166,35,.8),rgba(0,210,255,.3),rgba(139,92,246,.2));transform:translateX(-50%); }
        .rm-item { display:grid;grid-template-columns:1fr 40px 1fr;gap:0;margin-bottom:24px;align-items:center; }
        .rm-card { background:rgba(10,13,25,.75);border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:14px 16px;transition:.25s; }
        .rm-card:hover { border-color:rgba(245,166,35,.22);transform:scale(1.01); }
        .rm-card.r { text-align:right; }
        .rm-phase { font-size:.58rem;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#F5A623;margin-bottom:4px; }
        .rm-ttl { font-family:'Rajdhani',sans-serif;font-size:.95rem;font-weight:700;color:#fff;margin-bottom:4px; }
        .rm-pts { font-size:.73rem;color:rgba(255,255,255,.5);line-height:1.45; }
        .rm-node { display:flex;align-items:center;justify-content:center;position:relative;z-index:2; }
        .rm-dot { width:14px;height:14px;border-radius:50%;background:linear-gradient(135deg,#F5A623,#FFD700);box-shadow:0 0 12px rgba(245,166,35,.6); }
        .rm-dot.done { background:linear-gradient(135deg,#00FF88,#00D2FF);box-shadow:0 0 12px rgba(0,255,136,.5); }
        .rm-dot.coming { background:rgba(255,255,255,.1);border:2px solid rgba(255,255,255,.2);box-shadow:none; }

        /* === TECHNOLOGY & VERIFIED CONTRACTS === */
        .tech-bg { background:linear-gradient(135deg,rgba(245,166,35,.02),rgba(0,210,255,.02)); }
        .tech-layout { display:grid;grid-template-columns:1fr 1fr;gap:36px;align-items:center;width:100%;max-width:100%;min-width:0;box-sizing:border-box; }
        .spec-list { display:flex;flex-direction:column;gap:0;margin-top:18px;width:100%;max-width:100%;box-sizing:border-box; }
        .spec-row { display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.05);gap:10px;width:100%;box-sizing:border-box;min-width:0; }
        .spec-row:last-child { border-bottom:none; }
        .sk { font-size:.74rem;color:rgba(255,255,255,.5);font-weight:600;text-transform:uppercase;letter-spacing:.4px;flex-shrink:0; }
        .sv { font-family:'Space Mono',monospace;font-size:.75rem;font-weight:700;color:#fff;text-align:right;flex:1 1 auto;word-break:break-word; }
        .sv.gold { color:#FFD700; }
        .sv.green { color:#00FF88; }
        
        .chain-card { background:rgba(10,13,25,.85);border:1px solid rgba(245,166,35,.2);border-radius:16px;padding:20px 16px;position:relative;overflow:hidden;text-align:center;width:100%;max-width:100%;box-sizing:border-box;min-width:0; }
        .chain-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,#F5A623,#FFD700,#F5A623); }
        .cc-row { display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:14px; }
        .cc-icon { width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;background:rgba(245,166,35,.12);border:1px solid rgba(245,166,35,.22);flex-shrink:0; }
        .cc-name { font-family:'Rajdhani',sans-serif;font-size:1.15rem;font-weight:800;color:#fff; }
        .cc-sub { font-size:.68rem;color:rgba(255,255,255,.4);margin-top:1px; }
        
        .verified-contracts-box { margin-top:14px;display:flex;flex-direction:column;gap:8px;text-align:left;width:100%;box-sizing:border-box; }
        .vc-item { background:rgba(255,255,255,.03);border:1px solid rgba(245,166,35,.18);border-radius:8px;padding:8px 10px;display:flex;align-items:center;justify-content:space-between;gap:8px;width:100%;max-width:100%;box-sizing:border-box;min-width:0;transition:.2s; }
        .vc-item:hover { border-color:rgba(245,166,35,.4);background:rgba(245,166,35,.04); }
        .vc-left { min-width:0;overflow:hidden;flex:1 1 auto; }
        .vc-title { font-size:.62rem;color:rgba(255,255,255,.5);font-weight:700;text-transform:uppercase;letter-spacing:.6px; }
        .vc-hash { font-family:'Space Mono',monospace;font-size:.72rem;color:#FFD700;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:1px; }
        .vc-actions { display:flex;align-items:center;gap:4px;flex-shrink:0; }
        
        .cc-feats { display:flex;flex-direction:column;gap:6px;text-align:left;margin-top:12px;width:100%; }
        .cc-feat-row { display:flex;align-items:center;gap:8px;padding:7px 9px;background:rgba(255,255,255,.02);border-radius:6px;font-size:.78rem;color:rgba(255,255,255,.7); }
        .chain-badges { display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-top:12px; }
        .cbadge { padding:3px 8px;border-radius:5px;font-size:.64rem;font-weight:700;letter-spacing:.4px; }
        .cbadge.bep { background:rgba(245,166,35,.15);border:1px solid rgba(245,166,35,.3);color:#FFD700; }
        .cbadge.ver { background:rgba(0,255,136,.1);border:1px solid rgba(0,255,136,.3);color:#00FF88; }
        .cbadge.sec { background:rgba(0,210,255,.1);border:1px solid rgba(0,210,255,.3);color:#00D2FF; }

        /* === CTA === */
        .cta-sec { padding:56px 18px;text-align:center;position:relative;overflow:hidden; }
        .cta-glow { position:absolute;width:400px;height:260px;border-radius:50%;background:radial-gradient(ellipse,rgba(245,166,35,.12),transparent 70%);top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none; }
        .cta-box { position:relative;max-width:620px;margin:0 auto;background:rgba(10,13,25,.88);border:1px solid rgba(245,166,35,.24);border-radius:18px;padding:36px 24px;backdrop-filter:blur(16px);overflow:hidden; }
        .cta-box::before { content:'';position:absolute;top:0;left:15%;right:15%;height:2px;background:linear-gradient(90deg,transparent,rgba(255,215,0,.85),rgba(245,166,35,.9),transparent); }
        .cta-title { font-family:'Rajdhani',sans-serif;font-size:clamp(1.5rem,3vw,2.2rem);font-weight:900;color:#fff;margin-bottom:10px;line-height:1.15; }
        .cta-title span { background:linear-gradient(135deg,#FFD700,#F5A623);-webkit-background-clip:text;-webkit-text-fill-color:transparent; }
        .cta-desc { font-size:.84rem;color:rgba(255,255,255,.55);margin:0 auto 24px;max-width:440px;line-height:1.55; }
        .cta-btns { display:flex;justify-content:center;gap:10px;flex-wrap:wrap; }

        /* === FOOTER === */
        .footer { background:rgba(2,3,10,.94);border-top:1px solid rgba(245,166,35,.1);padding:28px 18px;text-align:center;position:relative;z-index:1; }
        .ft-logo { display:inline-flex;align-items:center;gap:8px;margin-bottom:12px; }
        .ft-logo img { width:24px;height:24px;border-radius:50%; }
        .ft-logo-txt { font-family:'Rajdhani',sans-serif;font-size:1.05rem;font-weight:800;background:linear-gradient(135deg,#FFD700,#F5A623);-webkit-background-clip:text;-webkit-text-fill-color:transparent; }
        .ft-links { display:flex;justify-content:center;gap:18px;list-style:none;margin-bottom:14px;flex-wrap:wrap; }
        .ft-links a { color:rgba(255,255,255,.45);text-decoration:none;font-size:.74rem;font-weight:600;transition:color .2s; }
        .ft-links a:hover { color:#FFD700; }
        .ft-copy { font-size:.7rem;color:rgba(255,255,255,.25); }

        /* === REVEAL === */
        .reveal { opacity:0;transform:translateY(20px);transition:opacity .6s ease,transform .6s ease; }
        .revealed { opacity:1!important;transform:translateY(0)!important; }

        /* === RESPONSIVE BREAKPOINTS === */
        @media (max-width: 900px) {
            .nav-links { display:none; }
            .nav { padding:10px 18px; }
            .about-grid { grid-template-columns: 1fr; gap: 24px; text-align: center; }
            .about-feats { text-align: left; }
            .tech-layout { grid-template-columns: 1fr; gap: 28px; }
            .eco-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-bar { grid-template-columns: repeat(3, 1fr); }
            .stat-item:nth-child(3)::after { display: none; }
        }

        @media (max-width: 600px) {
            .nav { padding: 8px 12px; }
            .nav-logo-txt { font-size: 1.02rem; }
            .nav-logo img { width: 24px; height: 24px; }
            .btn-launch { padding: 6px 12px; font-size: 0.74rem; }

            .hero { padding: 68px 12px 24px; min-height: auto; }
            .token-stage { width: 110px; height: 110px; margin-bottom: 14px; }
            .ring-outer { inset: -8px; }
            .ring-mid { inset: -16px; }
            .hero-title { font-size: 1.7rem; line-height: 1.15; margin-bottom: 8px; }
            .hero-sub { font-size: 0.8rem; margin-bottom: 14px; line-height: 1.5; }
            .hero-btns { gap: 8px; margin-bottom: 14px; }
            .hero-btns a { flex: 1 1 130px; justify-content: center; padding: 8px 12px; font-size: 0.78rem; }
            
            .contracts-strip { grid-template-columns: 1fr; gap: 6px; margin-bottom: 14px; }
            .contract-pill { padding: 6px 10px; }
            .cp-addr { font-size: 0.7rem; }

            .stats-bar { grid-template-columns: repeat(2, 1fr); gap: 1px; background: rgba(245,166,35,.15); padding: 1px; margin-top: 16px; }
            .stat-item { background: rgba(10,13,25,.95); padding: 7px 4px; }
            .stat-item:last-child { grid-column: span 2; }
            .stat-item::after { display: none !important; }
            .stat-num { font-size: 0.9rem; }
            .stat-lbl { font-size: 0.56rem; }

            .sec-pad { padding: 36px 12px; }
            .sec-title { font-size: 1.35rem; }
            .sec-desc { font-size: 0.78rem; }

            .about-coin-wrap { width: 130px; height: 130px; }
            .fl-badge { padding: 3px 6px; }
            .bv { font-size: 0.62rem; }
            .bl { font-size: 0.5rem; }

            .eco-grid { grid-template-columns: 1fr; gap: 9px; margin-top: 20px; }
            .eco-card { padding: 12px 10px; }
            .eco-icon { width: 32px; height: 32px; font-size: 14px; margin-bottom: 8px; border-radius: 8px; }
            .eco-t { font-size: 0.95rem; }
            .eco-d { font-size: 0.74rem; }

            .donut-wrap { width: 140px; height: 140px; }
            .dc-val { font-size: 0.95rem; }
            .alloc-label { width: 110px; font-size: 0.68rem; }
            .alloc-pct { font-size: 0.65rem; }
            .tok-info-grid { grid-template-columns: repeat(2, 1fr); gap: 5px; }
            .tok-card { padding: 6px 7px; }
            .tc-val { font-size: 0.75rem; }

            /* Roadmap Mobile Compact */
            .rm-line { left: 14px; transform: none; }
            .rm-item { grid-template-columns: 28px 1fr; gap: 8px; margin-bottom: 12px; }
            .rm-item > div:first-child:empty { display: none; }
            .rm-item > div:last-child:empty { display: none; }
            .rm-node { grid-column: 1; align-items: flex-start; padding-top: 10px; justify-content: flex-start; }
            .rm-dot { width: 11px; height: 11px; }
            .rm-card, .rm-card.r { grid-column: 2; text-align: left; padding: 9px 11px; }
            .rm-ttl { font-size: 0.85rem; }
            .rm-pts { font-size: 0.68rem; }

            .spec-row { padding: 6px 0; gap: 8px; }
            .sk { font-size: 0.68rem; }
            .sv { font-size: 0.68rem; }
            .chain-card { padding: 14px 10px; }
            .cc-name { font-size: 0.95rem; }
            .cc-feat-row { padding: 5px 7px; font-size: 0.72rem; }
            .vc-item { padding: 7px 8px; }
            .vc-hash { font-size: 0.68rem; }

            .cta-sec { padding: 36px 12px; }
            .cta-box { padding: 20px 12px; }
            .cta-btns a { flex: 1 1 120px; justify-content: center; padding: 8px 10px; font-size: 0.78rem; }
            
            .footer { padding: 20px 12px; }
            .ft-links { gap: 12px; font-size: 0.7rem; }
        }
    </style>
</head>
<body>

<!-- UNIVERSE BG -->
<div class="universe-bg">
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>
    <canvas id="starfield"></canvas>
</div>

<!-- COPY NOTIFICATION TOAST -->
<div class="copy-toast" id="copyToast">
    <i class="fas fa-circle-check"></i> <span id="copyToastMsg">Contract Address Copied!</span>
</div>

<!-- NAVBAR -->
<nav class="nav" id="mainNav">
    <a href="#" class="nav-logo">
        <img src="{{ asset('images/cai-token-coin.png') }}" alt="CAI">
        <span class="nav-logo-txt">CYERA AI</span>
    </a>
    <ul class="nav-links">
        <li><a href="#chart" style="color:#00FF88;"><i class="fas fa-chart-line"></i> Live Rate</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#ecosystem">Ecosystem</a></li>
        <li><a href="#roadmap">Roadmap</a></li>
        <li><a href="#technology">Technology</a></li>
    </ul>
    <div class="nav-actions" style="display:flex;align-items:center;gap:10px;">
        <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;background:rgba(0,255,136,0.1);border:1px solid rgba(0,255,136,0.3);color:#00FF88;font-size:0.75rem;font-family:'Space Mono',monospace;font-weight:700;">
            <span class="pdot" style="margin:0;"></span>
            <span id="navLivePrice">$0.9991</span>
        </span>
        <a href="{{ url('/login') }}" class="btn-launch"><i class="fas fa-wallet"></i> Connect Wallet</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">
    <div class="hero-grid"></div>
    <div class="scanline"></div>
    <div class="hero-inner">
        <div class="eyebrow"><span class="pdot"></span> BNB SMART CHAIN · BEP-20 TOKEN · VERIFIED</div>

        <div class="token-stage">
            <div class="ring-mid"></div>
            <div class="ring-outer"></div>
            <div class="token-halo"></div>
            <div class="token-coin">
                <img src="{{ asset('images/cai-token-coin.png') }}" alt="CAI Token">
            </div>
            <div class="pt" style="width:4px;height:4px;background:#FFD700;left:8%;bottom:18%;animation-duration:3.5s;"></div>
            <div class="pt" style="width:3px;height:3px;background:#00FF88;left:82%;bottom:28%;animation-duration:4.2s;animation-delay:-1s;"></div>
            <div class="pt" style="width:5px;height:5px;background:#00D2FF;left:50%;bottom:5%;animation-duration:5s;animation-delay:-2s;"></div>
            <div class="pt" style="width:3px;height:3px;background:#F5A623;left:22%;bottom:8%;animation-duration:3s;animation-delay:-0.5s;"></div>
            <div class="pt" style="width:4px;height:4px;background:#8B5CF6;left:72%;bottom:14%;animation-duration:4.8s;animation-delay:-1.5s;"></div>
        </div>

        <h1 class="hero-title">
            <span class="grad-txt">Power The Future</span><br>
            Own The Protocol
        </h1>
        <p class="hero-sub">CAI Token is the native BEP-20 digital asset powering the Cyera AI decentralized protocol on BNB Smart Chain with a capped supply of 300000 CAI.</p>
        
        <div class="hero-btns">
            <a href="{{ url('/register') }}" class="btn-primary"><i class="fas fa-rocket"></i> Get Started</a>
            <a href="#about" class="btn-ghost"><i class="fas fa-play-circle"></i> Explore CAI</a>
        </div>

        <!-- DUAL SMART CONTRACT BADGES -->
        <div class="contracts-strip">
            <!-- CAI TOKEN CONTRACT -->
            <div class="contract-pill">
                <div class="cp-left">
                    <div class="cp-icon token"><i class="fas fa-coins"></i></div>
                    <div class="cp-meta">
                        <div class="cp-tag">CAI Token Contract</div>
                        <div class="cp-addr">0x4756...4eDB</div>
                    </div>
                </div>
                <div class="cp-actions">
                    <button class="btn-cp-action" onclick="copyAddress('0x4756618F389A46819008Aff01ad0f91A38154eDB', 'CAI Token Address')" title="Copy Token Address">
                        <i class="fas fa-copy"></i>
                    </button>
                    <a href="https://bscscan.com/token/0x4756618F389A46819008Aff01ad0f91A38154eDB" target="_blank" rel="noopener noreferrer" class="btn-cp-action" title="View CAI Token on BscScan">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                    <a href="https://dexscreener.com/bsc/0x4b33d9a80aeae68ad6d9ee84fd816db9a29d6a2c" target="_blank" rel="noopener noreferrer" class="btn-cp-action" title="Live Chart on DexScreener" style="color: #00FF88;">
                        <i class="fas fa-chart-line"></i>
                    </a>
                </div>
            </div>

            <!-- INVESTMENT SPLITTER CONTRACT -->
            <div class="contract-pill">
                <div class="cp-left">
                    <div class="cp-icon invest"><i class="fas fa-shield-halved"></i></div>
                    <div class="cp-meta">
                        <div class="cp-tag">Cyera Mining Engine</div>
                        <div class="cp-addr">0xdd90...0217</div>
                    </div>
                </div>
                <div class="cp-actions">
                    <button class="btn-cp-action" onclick="copyAddress('0xdd905468F6F91f8c37eFB9e27E1f282734E60217', 'Mining Engine Address')" title="Copy Mining Engine Address">
                        <i class="fas fa-copy"></i>
                    </button>
                    <a href="https://bscscan.com/address/0xdd905468F6F91f8c37eFB9e27E1f282734E60217" target="_blank" rel="noopener noreferrer" class="btn-cp-action" title="View Mining Engine on BscScan">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="stats-bar">
            <div class="stat-item"><div class="stat-num">BEP-20</div><div class="stat-lbl">Token Standard</div></div>
            <div class="stat-item"><div class="stat-num">BNB Chain</div><div class="stat-lbl">Network</div></div>
            <div class="stat-item"><div class="stat-num">300000</div><div class="stat-lbl">Total Supply</div></div>
            <div class="stat-item"><div class="stat-num">18</div><div class="stat-lbl">Decimals</div></div>
            <div class="stat-item"><div class="stat-num">LIVE</div><div class="stat-lbl">Protocol Status</div></div>
        </div>
    </div>
    <div class="scroll-down" onclick="document.getElementById('chart').scrollIntoView({behavior:'smooth'})">
        <i class="fas fa-chevron-down" style="font-size:16px;"></i>
    </div>
</section>

<div class="divider"></div>

<!-- ============================================================
     LIVE DEX MARKET & TRADINGVIEW CANDLESTICK CHART (COMPACT)
     ============================================================ -->
<section class="sec-pad" id="chart" style="padding: 24px 12px; background: radial-gradient(ellipse at 50% 0%, rgba(245, 166, 35, 0.05) 0%, transparent 70%);">
    <div class="container" style="max-width: 860px;">
        <div class="reveal" style="text-align: center; margin-bottom: 12px;">
            <div class="sec-eyebrow" style="margin: 0 auto 6px; font-size: 0.58rem; padding: 2px 7px;"><i class="fas fa-chart-line"></i> DEX Real-Time Market</div>
            <h2 class="sec-title" style="font-size: clamp(1.2rem, 2.5vw, 1.8rem); margin-bottom: 4px;">Live <span>CAI / USDT</span> Market Chart</h2>
            <p class="sec-desc" style="margin: 0 auto; font-size: 0.76rem; max-width: 480px;">Real-time streaming price, on-chain liquidity &amp; candlestick chart from PancakeSwap V2.</p>
        </div>

        <div class="reveal" style="background: rgba(10, 13, 25, 0.95); border: 1px solid rgba(245, 166, 35, 0.3); border-radius: 12px; padding: 10px 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.6); position: relative; overflow: hidden;">
            <!-- Header Telemetry Row -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid rgba(245, 166, 35, 0.15);">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(255, 215, 0, 0.12); border: 1px solid rgba(255, 215, 0, 0.4); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <img src="{{ asset('images/cai-token-coin.png') }}" alt="CAI" style="width: 18px; height: 18px; object-fit: contain;">
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <span style="font-family: 'Rajdhani', sans-serif; font-size: 0.92rem; font-weight: 800; color: #FFF; letter-spacing: 0.4px;">CAI / USDT</span>
                            <span style="font-size: 0.52rem; padding: 1px 4px; border-radius: 3px; background: rgba(0, 255, 136, 0.12); color: #00FF88; border: 1px solid rgba(0, 255, 136, 0.3); font-weight: 800; font-family: 'Space Mono', monospace;">PANCAKESWAP</span>
                        </div>
                        <div style="font-size: 0.58rem; color: rgba(255, 255, 255, 0.5); font-family: 'Space Mono', monospace;">
                            Pool: <a href="https://bscscan.com/address/0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c" target="_blank" style="color: #FFD700; text-decoration: none;">0x4B33...6A2c <i class="fas fa-external-link-alt" style="font-size: 7px;"></i></a>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <div>
                        <div style="font-size: 0.50rem; color: rgba(255, 255, 255, 0.5); text-transform: uppercase; font-weight: 600; text-align: right;">Live DEX Rate</div>
                        <div id="landingLivePrice" style="font-size: 0.95rem; font-weight: 800; font-family: 'Space Mono', monospace; color: #00FF88; line-height: 1.1; text-align: right;">
                            $0.9991
                        </div>
                    </div>
                    <div style="background: rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 6px; padding: 3px 7px; text-align: center;">
                        <div style="font-size: 0.48rem; color: rgba(255, 255, 255, 0.5); text-transform: uppercase; font-weight: 600;">24H Change</div>
                        <div id="landing24hChange" style="font-size: 0.72rem; font-weight: 800; font-family: 'Space Mono', monospace; color: #00FF88;">
                            <i class="fas fa-arrow-trend-up"></i> +0.00%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compact 2x2 on Mobile, 4x1 on Desktop Stats Bar -->
            <style>
                .dex-stats-grid-compact {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 6px;
                    margin-bottom: 8px;
                }
                @media (max-width: 600px) {
                    .dex-stats-grid-compact {
                        grid-template-columns: repeat(2, 1fr);
                        gap: 4px;
                        margin-bottom: 6px;
                    }
                }
            </style>
            <div class="dex-stats-grid-compact">
                <div style="background: rgba(15, 23, 42, 0.65); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 6px; padding: 4px 6px;">
                    <div style="font-size: 0.50rem; color: rgba(255, 255, 255, 0.5); text-transform: uppercase; font-weight: 600;">24H Volume</div>
                    <div id="landing24hVol" style="font-size: 0.68rem; font-weight: 700; color: #FFFFFF; font-family: 'Space Mono', monospace;">$--</div>
                </div>
                <div style="background: rgba(15, 23, 42, 0.65); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 6px; padding: 4px 6px;">
                    <div style="font-size: 0.50rem; color: rgba(255, 255, 255, 0.5); text-transform: uppercase; font-weight: 600;">Pool Liquidity</div>
                    <div id="landingLiquidity" style="font-size: 0.68rem; font-weight: 700; color: #FFD700; font-family: 'Space Mono', monospace;">$--</div>
                </div>
                <div style="background: rgba(15, 23, 42, 0.65); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 6px; padding: 4px 6px;">
                    <div style="font-size: 0.50rem; color: rgba(255, 255, 255, 0.5); text-transform: uppercase; font-weight: 600;">Token Standard</div>
                    <div style="font-size: 0.68rem; font-weight: 700; color: #00FF88; font-family: 'Space Mono', monospace;">BEP-20 (BSC)</div>
                </div>
                <div style="background: rgba(15, 23, 42, 0.65); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 6px; padding: 4px 6px;">
                    <div style="font-size: 0.50rem; color: rgba(255, 255, 255, 0.5); text-transform: uppercase; font-weight: 600;">Capped Supply</div>
                    <div style="font-size: 0.68rem; font-weight: 700; color: #00E5FF; font-family: 'Space Mono', monospace;">300,000 CAI</div>
                </div>
            </div>

            <!-- TradingView Candlestick Chart Container (Compact Height) -->
            <div style="position: relative; width: 100%; border-radius: 8px; overflow: hidden; border: 1px solid rgba(245, 166, 35, 0.25); background: #0c0f17;">
                <style>
                    #landing-chart-frame {
                        position: relative;
                        width: 100%;
                        height: 380px;
                    }
                    @media (max-width: 600px) {
                        #landing-chart-frame {
                            height: 300px;
                        }
                    }
                    #landing-chart-frame iframe {
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        top: 0;
                        left: 0;
                        border: 0;
                    }
                </style>
                <div id="landing-chart-frame">
                    <iframe src="https://dexscreener.com/bsc/0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c?embed=1&theme=dark&trades=0&info=0" allowfullscreen></iframe>
                </div>
            </div>

            <!-- Footer Buttons (Ultra Compact) -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-top: 8px; padding-top: 6px; border-top: 1px solid rgba(245, 166, 35, 0.12);">
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    <a href="https://pancakeswap.finance/swap?outputCurrency=0x4756618F389A46819008Aff01ad0f91A38154eDB" target="_blank" class="btn-primary" style="padding: 5px 10px; font-size: 0.68rem; border-radius: 6px;">
                        <i class="fas fa-arrow-right-arrow-left"></i> Trade on PancakeSwap
                    </a>
                    <a href="https://dexscreener.com/bsc/0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c" target="_blank" class="btn-ghost" style="padding: 5px 10px; font-size: 0.68rem; border-radius: 6px;">
                        <i class="fas fa-chart-line"></i> Full DexScreener
                    </a>
                </div>
                <div style="font-size: 0.58rem; color: rgba(255, 255, 255, 0.5); font-family: 'Space Mono', monospace;">
                    PancakeSwap Pool
                </div>
            </div>
        </div>
    </div>
</section>

<div class="divider"></div>

<!-- ABOUT -->
<section class="about-bg sec-pad" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-vis reveal">
                <div class="about-coin-wrap">
                    <div class="a-ring a-ring1"></div>
                    <div class="a-ring a-ring2"></div>
                    <img src="{{ asset('images/cai-token-coin.png') }}" alt="Cyera AI CAI Token">
                    <div class="fl-badge b1"><div class="bv">BNB Chain</div><div class="bl">Powered By</div></div>
                    <div class="fl-badge b2"><div class="bv">300000 CAI</div><div class="bl">Total Supply</div></div>
                </div>
            </div>
            <div class="reveal" style="transition-delay:.2s">
                <div class="sec-eyebrow"><i class="fas fa-cube"></i> What Is CAI</div>
                <h2 class="sec-title">Meet the Token<br>Powering <span>Decentralized AI</span></h2>
                <p class="sec-desc">CAI (Cyera AI Token) is an exclusive BEP-20 digital asset built on BNB Smart Chain with a strictly capped total supply of 300000 tokens. It serves as the native utility and governance asset of the Cyera AI protocol — engineered for ultra-fast, audited on-chain participation.</p>
                <div class="about-feats">
                    <div class="feat-row">
                        <div class="feat-ico ico-gold"><i class="fas fa-shield-halved"></i></div>
                        <div><div class="ft">Immutable Smart Contract</div><div class="fd">CAI is deployed with an audited, immutable contract on BSC — no hidden minting privileges.</div></div>
                    </div>
                    <div class="feat-row">
                        <div class="feat-ico ico-green"><i class="fas fa-bolt-lightning"></i></div>
                        <div><div class="ft">Lightning-Fast Transactions</div><div class="fd">BNB Smart Chain delivers ~3 second block times and sub-cent fees for seamless protocol interactions.</div></div>
                    </div>
                    <div class="feat-row">
                        <div class="feat-ico ico-cyan"><i class="fas fa-network-wired"></i></div>
                        <div><div class="ft">Decentralized Protocol</div><div class="fd">Governed by token holders. No single point of control. Fully decentralized architecture from day one.</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="divider"></div>

<!-- ECOSYSTEM -->
<section class="eco-bg sec-pad" id="ecosystem">
    <div class="container">
        <div class="reveal" style="text-align:center">
            <div class="sec-eyebrow" style="margin:0 auto 14px"><i class="fas fa-atom"></i> The Ecosystem</div>
            <h2 class="sec-title" style="text-align:center">Built For The <span>Next Generation</span></h2>
            <p class="sec-desc" style="margin:0 auto;text-align:center">A comprehensive suite of decentralized products and services — all powered by the CAI token.</p>
        </div>
        <div class="eco-grid">
            <div class="eco-card c-gold reveal" style="transition-delay:.05s">
                <div class="eco-glow"></div>
                <div class="eco-icon"><i class="fas fa-coins"></i></div>
                <div class="eco-t">Token Staking</div>
                <div class="eco-d">Lock your CAI tokens in the protocol and participate in the network's security and growth cycle through smart-contract driven staking mechanics.</div>
            </div>
            <div class="eco-card c-cyan reveal" style="transition-delay:.1s">
                <div class="eco-glow"></div>
                <div class="eco-icon"><i class="fas fa-users"></i></div>
                <div class="eco-t">Referral Network</div>
                <div class="eco-d">Build your decentralized network of participants. CAI rewards flow through an on-chain multi-level commission architecture.</div>
            </div>
            <div class="eco-card c-green reveal" style="transition-delay:.15s">
                <div class="eco-glow"></div>
                <div class="eco-icon"><i class="fas fa-layer-group"></i></div>
                <div class="eco-t">Turnover Pools</div>
                <div class="eco-d">Daily, Weekly, and Monthly global reward pools distribute a percentage of protocol volume to qualifying network participants.</div>
            </div>
            <div class="eco-card c-purple reveal" style="transition-delay:.2s">
                <div class="eco-glow"></div>
                <div class="eco-icon"><i class="fas fa-crown"></i></div>
                <div class="eco-t">Rank Milestones</div>
                <div class="eco-d">Achieve network milestones to unlock rank titles — from V1 to V8 — with corresponding protocol recognition and privileges.</div>
            </div>
            <div class="eco-card c-amber reveal" style="transition-delay:.25s">
                <div class="eco-glow"></div>
                <div class="eco-icon"><i class="fas fa-chart-network"></i></div>
                <div class="eco-t">Team Analytics</div>
                <div class="eco-d">Real-time genealogy tree visualization, team business tracking, and downline analytics all in one decentralized dashboard.</div>
            </div>
            <div class="eco-card c-pink reveal" style="transition-delay:.3s">
                <div class="eco-glow"></div>
                <div class="eco-icon"><i class="fas fa-wallet"></i></div>
                <div class="eco-t">Web3 Settlement</div>
                <div class="eco-d">All protocol transactions settle on-chain via BEP-20 USDT and CAI. Withdraw directly to your BNB Chain wallet anytime.</div>
            </div>
        </div>
    </div>
</section>

<div class="divider"></div>

<!-- ROADMAP -->
<section class="rm-bg sec-pad" id="roadmap">
    <div class="container">
        <div class="reveal" style="text-align:center">
            <div class="sec-eyebrow" style="margin:0 auto 14px"><i class="fas fa-map-marked-alt"></i> Roadmap</div>
            <h2 class="sec-title" style="text-align:center">Building Towards <span>Protocol Infinity</span></h2>
            <p class="sec-desc" style="text-align:center;margin:0 auto">Our development roadmap reflects our commitment to building a robust, long-lasting decentralized ecosystem.</p>
        </div>
        <div class="rm-timeline">
            <div class="rm-line"></div>
            <div class="rm-item reveal" style="transition-delay:.1s">
                <div class="rm-card">
                    <div class="rm-phase">Phase 1 — Complete</div>
                    <div class="rm-ttl">Protocol Genesis</div>
                    <div class="rm-pts">Smart contract deployment · BEP-20 token launch (30K Supply) · Core staking engine · Dashboard v1.0 · Referral architecture</div>
                </div>
                <div class="rm-node"><div class="rm-dot done"></div></div>
                <div></div>
            </div>
            <div class="rm-item reveal" style="transition-delay:.2s">
                <div></div>
                <div class="rm-node"><div class="rm-dot done"></div></div>
                <div class="rm-card r">
                    <div class="rm-phase">Phase 2 — Complete</div>
                    <div class="rm-ttl">Network Expansion</div>
                    <div class="rm-pts">Global turnover pools · Multi-level architecture · Rank system V1–V8 · Tree visualization · Mobile-optimized HUD</div>
                </div>
            </div>
            <div class="rm-item reveal" style="transition-delay:.3s">
                <div class="rm-card">
                    <div class="rm-phase">Phase 3 — In Progress</div>
                    <div class="rm-ttl">DeFi Integration</div>
                    <div class="rm-pts">DEX listing · Liquidity pool launch · CMC & CoinGecko listing · Community DAO governance · Cross-chain bridge</div>
                </div>
                <div class="rm-node"><div class="rm-dot"></div></div>
                <div></div>
            </div>
            <div class="rm-item reveal" style="transition-delay:.4s">
                <div></div>
                <div class="rm-node"><div class="rm-dot coming"></div></div>
                <div class="rm-card r">
                    <div class="rm-phase">Phase 4 — Upcoming</div>
                    <div class="rm-ttl">AI Protocol Layer</div>
                    <div class="rm-pts">AI-powered portfolio intelligence · Predictive analytics engine · On-chain AI governance · Metaverse integration · Exchange partnerships</div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="divider"></div>

<!-- TECHNOLOGY & VERIFIED CONTRACTS -->
<section class="tech-bg sec-pad" id="technology">
    <div class="container">
        <div class="tech-layout">
            <div class="reveal">
                <div class="sec-eyebrow"><i class="fas fa-microchip"></i> Technology</div>
                <h2 class="sec-title">Built On Rock-Solid<br><span>Infrastructure</span></h2>
                <p class="sec-desc">Cyera AI leverages the battle-tested BNB Smart Chain — one of the most secure, scalable, and widely adopted blockchain networks on earth.</p>
                <div class="spec-list">
                    <div class="spec-row"><span class="sk">Blockchain</span><span class="sv gold">BNB Smart Chain</span></div>
                    <div class="spec-row"><span class="sk">Token Standard</span><span class="sv gold">BEP-20</span></div>
                    <div class="spec-row"><span class="sk">Total Supply</span><span class="sv gold">300000 CAI</span></div>
                    <div class="spec-row"><span class="sk">Consensus</span><span class="sv">PoSA (21 Validators)</span></div>
                    <div class="spec-row"><span class="sk">Block Time</span><span class="sv green">~3 Seconds</span></div>
                    <div class="spec-row"><span class="sk">Transaction Fees</span><span class="sv green">&lt; $0.01 USD</span></div>
                    <div class="spec-row"><span class="sk">Settlement</span><span class="sv">USDT BEP-20</span></div>
                    <div class="spec-row"><span class="sk">Decimals</span><span class="sv">18</span></div>
                    <div class="spec-row"><span class="sk">Smart Contract</span><span class="sv green">Verified on BscScan</span></div>
                </div>
            </div>
            <div class="reveal" style="transition-delay:.2s">
                <div class="chain-card">
                    <div class="cc-row">
                        <div class="cc-icon" style="color:#F5A623"><i class="fas fa-link"></i></div>
                        <div><div class="cc-name">BNB Smart Chain</div><div class="cc-sub">Native Blockchain Infrastructure</div></div>
                    </div>
                    
                    <!-- VERIFIED CONTRACTS LIST -->
                    <div class="verified-contracts-box">
                        <div class="vc-item">
                            <div class="vc-left">
                                <div class="vc-title"><i class="fas fa-coins" style="color:#FFD700;margin-right:4px;"></i> CAI Token Contract</div>
                                <div class="vc-hash" title="0x4756618F389A46819008Aff01ad0f91A38154eDB">0x4756...4eDB</div>
                            </div>
                            <div class="vc-actions">
                                <button class="btn-cp-action" onclick="copyAddress('0x4756618F389A46819008Aff01ad0f91A38154eDB', 'CAI Token Address')" title="Copy Token Address">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <a href="https://bscscan.com/token/0x4756618F389A46819008Aff01ad0f91A38154eDB" target="_blank" rel="noopener noreferrer" class="btn-cp-action" title="View CAI Token on BscScan">
                                    <i class="fas fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>

                        <div class="vc-item">
                            <div class="vc-left">
                                <div class="vc-title"><i class="fas fa-shield-halved" style="color:#00FF88;margin-right:4px;"></i> Cyera Mining Engine</div>
                                <div class="vc-hash" title="0xdd905468F6F91f8c37eFB9e27E1f282734E60217">0xdd90...0217</div>
                            </div>
                            <div class="vc-actions">
                                <button class="btn-cp-action" onclick="copyAddress('0xdd905468F6F91f8c37eFB9e27E1f282734E60217', 'Mining Engine Address')" title="Copy Mining Engine Address">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <a href="https://bscscan.com/address/0xdd905468F6F91f8c37eFB9e27E1f282734E60217" target="_blank" rel="noopener noreferrer" class="btn-cp-action" title="View Mining Engine on BscScan">
                                    <i class="fas fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="cc-feats">
                        <div class="cc-feat-row"><i class="fas fa-shield-halved" style="color:#00FF88"></i><span>21 Validators · PoSA Consensus</span></div>
                        <div class="cc-feat-row"><i class="fas fa-bolt" style="color:#FFD700"></i><span>High TPS · Sub-second finality</span></div>
                        <div class="cc-feat-row"><i class="fas fa-code-branch" style="color:#00D2FF"></i><span>EVM Compatible · Solidity Smart Contracts</span></div>
                    </div>
                    <div class="chain-badges">
                        <span class="cbadge bep">BEP-20</span>
                        <span class="cbadge ver">✓ BscScan Verified</span>
                        <span class="cbadge sec">🔒 Audited Architecture</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="divider"></div>

<!-- CTA -->
<section class="cta-sec" id="join">
    <div class="cta-glow"></div>
    <div class="cta-box reveal">
        <h2 class="cta-title">Ready to Join the<br><span>CAI Revolution?</span></h2>
        <p class="cta-desc">Become part of the Cyera AI protocol. Connect your wallet, stake CAI, and participate in the decentralized future of intelligent finance.</p>
        <div class="cta-btns">
            <a href="{{ url('/register') }}" class="btn-primary" style="font-size:.95rem"><i class="fas fa-rocket"></i> Create Account</a>
            <a href="{{ url('/login') }}" class="btn-ghost" style="font-size:.95rem"><i class="fas fa-sign-in-alt"></i> Already a Member</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="ft-logo">
        <img src="{{ asset('images/cai-token-coin.png') }}" alt="CAI">
        <span class="ft-logo-txt">CYERA AI</span>
    </div>
    <ul class="ft-links">
        <li><a href="#about">About</a></li>
        <li><a href="#ecosystem">Ecosystem</a></li>
        <li><a href="#roadmap">Roadmap</a></li>
        <li><a href="#technology">Technology</a></li>
        <li><a href="{{ url('/login') }}">Login</a></li>
    </ul>
    <p class="ft-copy">&copy; {{ date('Y') }} Cyera AI Protocol. All rights reserved. CAI Token operates on BNB Smart Chain. Total Supply: 300000 CAI.</p>
</footer>

<script>
// === STARFIELD ===
const canvas = document.getElementById('starfield');
const ctx = canvas.getContext('2d');
let stars = [];
function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
function initStars() {
    stars = [];
    const n = Math.floor((canvas.width * canvas.height) / 6000);
    for (let i = 0; i < n; i++) stars.push({
        x: Math.random() * canvas.width, y: Math.random() * canvas.height,
        r: Math.random() * 1.2 + 0.2, a: Math.random(),
        ts: (Math.random() * 0.018 + 0.004) * (Math.random() > .5 ? 1 : -1)
    });
}
function drawStars() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    stars.forEach(s => {
        s.a += s.ts;
        if (s.a > 1 || s.a < 0.08) s.ts *= -1;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(255,255,255,${s.a * 0.6})`;
        ctx.fill();
    });
    requestAnimationFrame(drawStars);
}
window.addEventListener('resize', () => { resize(); initStars(); });
resize(); initStars(); drawStars();

// === COPY ADDRESS FUNCTION WITH TOAST ===
function copyAddress(address, label) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(address).then(() => showToast(label + ' Copied!'));
    } else {
        const input = document.createElement('input');
        input.value = address;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        showToast(label + ' Copied!');
    }
}

function showToast(msg) {
    const toast = document.getElementById('copyToast');
    const toastMsg = document.getElementById('copyToastMsg');
    if (!toast || !toastMsg) return;
    toastMsg.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2200);
}

// === NAVBAR SCROLL ===
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 60));

// === REVEAL ===
const io = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); io.unobserve(e.target); } });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
document.querySelectorAll('.reveal').forEach(r => io.observe(r));

// === SMOOTH SCROLL ===
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const t = document.querySelector(a.getAttribute('href'));
        if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
});

// === REAL-TIME DEXSCREENER TELEMETRY POLLING ===
async function fetchLandingDexTelemetry() {
    try {
        const res = await fetch('https://api.dexscreener.com/latest/dex/pairs/bsc/0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c');
        if (!res.ok) return;
        const data = await res.json();
        if (!data || !data.pairs || data.pairs.length === 0) return;

        const pair = data.pairs[0];
        const priceUsd = parseFloat(pair.priceUsd) || 0;
        const change24h = parseFloat(pair.priceChange?.h24) || 0;
        const vol24h = parseFloat(pair.volume?.h24) || 0;
        const liquidityUsd = parseFloat(pair.liquidity?.usd) || 0;

        // Nav price
        const navEl = document.getElementById('navLivePrice');
        if (navEl && priceUsd > 0) navEl.innerText = '$' + priceUsd.toFixed(4);

        // Section live rate
        const priceEl = document.getElementById('landingLivePrice');
        if (priceEl && priceUsd > 0) priceEl.innerText = '$' + priceUsd.toFixed(4);

        // 24H change
        const changeEl = document.getElementById('landing24hChange');
        if (changeEl) {
            if (change24h >= 0) {
                changeEl.style.color = '#00FF88';
                changeEl.innerHTML = `<i class="fas fa-arrow-trend-up"></i> +${change24h.toFixed(2)}%`;
            } else {
                changeEl.style.color = '#FF4D7D';
                changeEl.innerHTML = `<i class="fas fa-arrow-trend-down"></i> ${change24h.toFixed(2)}%`;
            }
        }

        // 24h Vol
        const volEl = document.getElementById('landing24hVol');
        if (volEl) volEl.innerText = '$' + vol24h.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Liquidity
        const liqEl = document.getElementById('landingLiquidity');
        if (liqEl) liqEl.innerText = '$' + liquidityUsd.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    } catch (e) {
        console.debug('Landing Dex Polling Error:', e);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    fetchLandingDexTelemetry();
    setInterval(fetchLandingDexTelemetry, 20000);
});
</script>
</body>
</html>