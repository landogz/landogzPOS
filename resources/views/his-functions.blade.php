<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>iHOMIS+ · 3-Tier System Architecture</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap');

*{box-sizing:border-box;margin:0;padding:0;}

:root{
  --bg:#07090f;
  --surface:#0f1420;
  --surface2:#141a28;
  --border:#1c2740;
  --text:#e2e8f0;
  --muted:#4a5568;
  --muted2:#64748b;
}

body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;overflow-x:hidden;}

/* ---- NAV ---- */
.top-nav{
  display:flex;align-items:center;gap:0;
  background:var(--surface);
  border-bottom:1px solid var(--border);
  padding:0 32px;
  position:sticky;top:0;z-index:200;
}
.nav-brand{
  font-family:'Space Mono',monospace;
  font-size:11px;letter-spacing:2px;
  color:#fff;padding:18px 24px 18px 0;
  border-right:1px solid var(--border);
  margin-right:24px;white-space:nowrap;
}
.nav-brand span{color:var(--tier-accent,#60a5fa);}
.tier-tabs{display:flex;gap:2px;flex:1;}
.tier-tab{
  padding:16px 28px;
  font-size:13px;font-weight:500;
  cursor:pointer;
  border:none;background:transparent;
  color:var(--muted2);
  border-bottom:2px solid transparent;
  transition:all .2s;
  white-space:nowrap;
  font-family:'DM Sans',sans-serif;
}
.tier-tab:hover{color:var(--text);}
.tier-tab.active{color:#fff;border-bottom-color:var(--tier-accent);}
.tier-tab .tab-badge{
  font-size:9px;font-family:'Space Mono',monospace;
  padding:2px 7px;border-radius:20px;
  margin-left:8px;vertical-align:middle;
}

/* ---- PAGE ---- */
.page{display:none;}
.page.active{display:block;}

/* ---- HERO ---- */
.tier-hero{
  padding:40px 40px 30px;
  background:linear-gradient(135deg,var(--bg) 0%,var(--hero-bg,#0a0e1a) 100%);
  border-bottom:1px solid var(--border);
  position:relative;overflow:hidden;
}
.tier-hero::before{
  content:'';position:absolute;top:-80px;right:-80px;
  width:400px;height:400px;
  background:radial-gradient(circle,var(--hero-glow,rgba(96,165,250,0.06)) 0%,transparent 65%);
  pointer-events:none;
}
.tier-eyebrow{
  font-family:'Space Mono',monospace;
  font-size:9px;letter-spacing:3px;
  color:var(--tier-accent);
  text-transform:uppercase;margin-bottom:10px;opacity:.8;
}
.tier-hero h1{font-size:26px;font-weight:700;color:#fff;letter-spacing:-.5px;margin-bottom:6px;}
.tier-hero .sub{font-size:14px;color:var(--muted2);max-width:600px;line-height:1.6;}
.tier-pills{display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;}
.tier-pill{
  font-size:11px;padding:5px 14px;border-radius:20px;
  font-family:'Space Mono',monospace;
  background:var(--tier-accent-bg);
  color:var(--tier-accent);
  border:1px solid var(--tier-accent-border);
}

/* ---- LEGEND BAR ---- */
.legend-bar{
  display:flex;gap:18px;padding:12px 40px;
  background:var(--surface2);border-bottom:1px solid var(--border);flex-wrap:wrap;
}
.legend-item{display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--muted2);}
.legend-dot{width:11px;height:11px;border-radius:3px;}
.legend-hint{margin-left:auto;font-size:11px;color:var(--muted);font-style:italic;}

/* ---- LAYOUT ---- */
.diagram-area{padding:28px 20px;overflow-x:auto;}
svg{display:block;margin:0 auto;max-width:100%;}

.node-group{cursor:pointer;}
.node-group:hover rect,.node-group:hover ellipse{filter:brightness(1.25);}
.node-label{font-family:'DM Sans',sans-serif;font-size:11px;font-weight:500;fill:#e2e8f0;text-anchor:middle;dominant-baseline:middle;pointer-events:none;}
.node-sublabel{font-family:'DM Sans',sans-serif;font-size:9px;fill:#4a5568;text-anchor:middle;dominant-baseline:middle;pointer-events:none;}
.flow-label{font-family:'Space Mono',monospace;font-size:8px;text-anchor:middle;}
.section-title{font-family:'Space Mono',monospace;font-size:9px;letter-spacing:2px;fill:#2d3748;text-transform:uppercase;}
.dfd-arrow{fill:none;stroke-width:1.5;}
.pulse-ring{animation:pulseRing 2.5s ease-out infinite;}
@keyframes pulseRing{0%{opacity:.5;r:9;}100%{opacity:0;r:22;}}

/* ---- MODULE GRID ---- */
.module-grid{
  display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
  gap:16px;padding:28px 40px 40px;
}
.module-card{
  background:var(--surface2);border:1px solid var(--border);
  border-radius:10px;padding:20px;
  border-top:2px solid var(--tier-accent);
  transition:transform .15s,box-shadow .15s;
}
.module-card:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,0,0,.3);}
.mc-head{display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;}
.mc-icon{
  width:36px;height:36px;border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  font-size:16px;flex-shrink:0;
  background:var(--tier-accent-bg);
}
.mc-title{font-size:13px;font-weight:600;color:#fff;line-height:1.3;}
.mc-sub{font-size:11px;color:var(--muted2);margin-top:2px;}
.func-list{list-style:none;display:flex;flex-direction:column;gap:5px;}
.func-item{
  display:flex;align-items:flex-start;gap:8px;
  font-size:11.5px;color:#cbd5e1;line-height:1.5;
  padding:5px 8px;background:rgba(0,0,0,.2);
  border-radius:5px;border-left:2px solid var(--tier-accent-border);
}
.fi-num{font-family:'Space Mono',monospace;font-size:9px;color:var(--muted);min-width:16px;margin-top:2px;}
.fi-section{
  font-family:'Space Mono',monospace;font-size:9px;letter-spacing:2px;
  color:var(--tier-accent);text-transform:uppercase;
  padding:6px 0 2px;margin-top:4px;
  border-top:1px solid var(--border);width:100%;display:block;
}
.func-item.is-section{
  background:transparent;border-left:none;padding:4px 0 0;
}

/* section divider */
.section-divider{
  padding:14px 40px 6px;
  font-family:'Space Mono',monospace;
  font-size:9px;letter-spacing:3px;
  color:var(--muted);text-transform:uppercase;
  border-top:1px solid var(--border);
  margin-top:8px;
}

/* ---- INFO PANEL ---- */
.info-panel{
  position:fixed;bottom:24px;right:24px;
  background:var(--surface);border:1px solid var(--border);
  border-radius:12px;padding:20px 22px;
  max-width:340px;max-height:78vh;overflow-y:auto;
  box-shadow:0 24px 60px rgba(0,0,0,.6);
  display:none;z-index:300;
  animation:slideUp .2s ease;
}
@keyframes slideUp{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}
.info-panel.active{display:block;}
.ip-close{position:absolute;top:10px;right:14px;background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px;}
.ip-tag{display:inline-block;font-size:9px;padding:2px 8px;border-radius:20px;margin-bottom:9px;font-family:'Space Mono',monospace;}
.ip-title{font-size:14px;font-weight:600;color:#fff;margin-bottom:7px;}
.ip-desc{font-size:12px;color:var(--muted2);line-height:1.6;margin-bottom:12px;}
.ip-fn-head{font-family:'Space Mono',monospace;font-size:9px;letter-spacing:2px;color:var(--muted);text-transform:uppercase;margin-bottom:8px;}
.ip-fn-list{list-style:none;display:flex;flex-direction:column;gap:5px;}
.ip-fn-item{display:flex;align-items:flex-start;gap:8px;font-size:11px;color:#cbd5e1;line-height:1.5;padding:5px 8px;background:#0f1420;border-radius:5px;border-left:2px solid #1c2740;}
.ip-fn-num{font-family:'Space Mono',monospace;font-size:9px;color:var(--muted);min-width:16px;margin-top:1px;}

/* ---- COMPARE TABLE ---- */
.compare-wrap{padding:28px 40px 40px;}
.compare-table{width:100%;border-collapse:collapse;}
.compare-table th,.compare-table td{padding:12px 16px;text-align:left;font-size:12.5px;border-bottom:1px solid var(--border);}
.compare-table th{font-family:'Space Mono',monospace;font-size:10px;letter-spacing:1px;color:var(--muted2);background:var(--surface2);}
.compare-table td:first-child{font-weight:500;color:#fff;}
.check{color:#22c55e;font-size:14px;}
.dash{color:var(--muted);font-size:14px;}
.partial{color:#f59e0b;font-size:12px;}

/* ---- SCROLLBAR ---- */
::-webkit-scrollbar{width:6px;height:6px;}
::-webkit-scrollbar-track{background:var(--bg);}
::-webkit-scrollbar-thumb{background:#1c2740;border-radius:3px;}
</style>
</head>
<body>

<!-- NAV -->
<nav class="top-nav">
  <div class="nav-brand">iHOMIS<span>+</span></div>
  <div class="tier-tabs">
    <button class="tier-tab active" onclick="switchTier('basic')" id="tab-basic">
      🏥 Basic
      <span class="tab-badge" style="background:#1e3a5f;color:#60a5fa;">Essential</span>
    </button>
    <button class="tier-tab" onclick="switchTier('pro')" id="tab-pro">
      ⚡ Professional
      <span class="tab-badge" style="background:#1a3020;color:#34d399;">Growth</span>
    </button>
    <button class="tier-tab" onclick="switchTier('ent')" id="tab-ent">
      🚀 Enterprise
      <span class="tab-badge" style="background:#2d1a40;color:#c084fc;">Scale</span>
    </button>
    <button class="tier-tab" onclick="switchTier('forms')" id="tab-forms">
      📋 Client Forms
      <span class="tab-badge" style="background:#1a2810;color:#84cc16;">Requirements</span>
    </button>
  </div>
</nav>

<!-- ============================================================ PAGE 1: BASIC ============================================================ -->
<div class="page active" id="page-basic" style="--tier-accent:#60a5fa;--tier-accent-bg:rgba(96,165,250,0.08);--tier-accent-border:rgba(96,165,250,0.25);--hero-bg:#070d1a;--hero-glow:rgba(96,165,250,0.07);">
  <div class="tier-hero">
    <div class="tier-eyebrow">Tier 1 · Essential</div>
    <h1>Basic — The "Essential" Tier</h1>
    <div class="sub">Core hospital operations for small to medium government hospitals, rural health units, and district facilities. Covers the fundamental workflows needed to run a compliant, functional hospital with minimal infrastructure.</div>
    <div class="tier-pills">
      <span class="tier-pill">Up to 50 Beds</span>
      <span class="tier-pill">5 Modules</span>
      <span class="tier-pill">3 Data Stores</span>
      <span class="tier-pill">RHU / District Hospital</span>
      <span class="tier-pill">Offline-Capable</span>
    </div>
  </div>

  <div class="legend-bar">
    <div class="legend-item"><div class="legend-dot" style="background:#1a2744;border:1.5px solid #60a5fa;"></div>External Entity</div>
    <div class="legend-item"><div class="legend-dot" style="background:#1a2a1a;border:1.5px solid #60a5fa;"></div>Process Module</div>
    <div class="legend-item"><div class="legend-dot" style="background:#2a1a1a;border:1.5px solid #f97316;"></div>Data Store</div>
    <div class="legend-item"><div class="legend-dot" style="background:#1e1040;border:2px solid #60a5fa;border-radius:50%;"></div>Core Engine</div>
    <span class="legend-hint">Click any node for functions →</span>
  </div>

  <div class="diagram-area">
  <svg id="svg-basic" width="1060" height="760" viewBox="0 0 1060 760" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <marker id="ab" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#334155"/></marker>
      <marker id="ab-b" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#60a5fa"/></marker>
      <marker id="ab-o" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#f97316"/></marker>
      <filter id="glow-b"><feGaussianBlur stdDeviation="4" result="cb"/><feMerge><feMergeNode in="cb"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
      <pattern id="grid-b" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M40 0L0 0 0 40" fill="none" stroke="#1c2740" stroke-width=".3"/></pattern>
    </defs>
    <rect width="1060" height="760" fill="url(#grid-b)" opacity=".3"/>

    <!-- Section panels -->
    <rect x="8" y="8" width="175" height="744" rx="7" fill="#0a0e18" stroke="#1c2740" stroke-width=".8" opacity=".7"/>
    <text x="96" y="28" class="section-title" fill="#1c2740">External</text>
    <rect x="870" y="8" width="182" height="744" rx="7" fill="#0a0e18" stroke="#1c2740" stroke-width=".8" opacity=".7"/>
    <text x="961" y="28" class="section-title" fill="#1c2740">Data Stores</text>

    <!-- Core -->
    <g class="node-group" onclick="showInfo('b','core')" filter="url(#glow-b)">
      <ellipse cx="530" cy="370" rx="82" ry="55" fill="#0e0d28" stroke="#60a5fa" stroke-width="2.5"/>
      <circle class="pulse-ring" cx="530" cy="370" r="9" fill="none" stroke="#60a5fa" stroke-width="1.5" opacity=".4"/>
      <text x="530" y="362" class="node-label" style="fill:#93c5fd;font-weight:600;font-size:12px;">iHOMIS+</text>
      <text x="530" y="376" class="node-label" style="fill:#93c5fd;font-size:10px;">Core (Basic)</text>
      <text x="530" y="390" class="node-sublabel">Essential Layer</text>
    </g>

    <!-- External Entities (left) -->
    <g class="node-group" onclick="showInfo('b','patient')">
      <rect x="18" y="55" width="155" height="50" rx="5" fill="#0f1c38" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="96" y="75" class="node-label" style="fill:#93c5fd;">Patient / Family</text>
      <text x="96" y="90" class="node-sublabel">Walk-in · Emergency</text>
    </g>
    <g class="node-group" onclick="showInfo('b','doctor')">
      <rect x="18" y="160" width="155" height="50" rx="5" fill="#0f1c38" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="96" y="180" class="node-label" style="fill:#93c5fd;">Medical Staff</text>
      <text x="96" y="195" class="node-sublabel">Doctors · Nurses</text>
    </g>
    <g class="node-group" onclick="showInfo('b','admin')">
      <rect x="18" y="265" width="155" height="50" rx="5" fill="#0f1c38" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="96" y="285" class="node-label" style="fill:#93c5fd;">Admin Staff</text>
      <text x="96" y="300" class="node-sublabel">Billing · Records</text>
    </g>
    <g class="node-group" onclick="showInfo('b','philhealth')">
      <rect x="18" y="370" width="155" height="50" rx="5" fill="#0f1c38" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="96" y="390" class="node-label" style="fill:#93c5fd;">PhilHealth</text>
      <text x="96" y="405" class="node-sublabel">Claims · Eligibility</text>
    </g>
    <g class="node-group" onclick="showInfo('b','doh')">
      <rect x="18" y="475" width="155" height="50" rx="5" fill="#0f1c38" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="96" y="495" class="node-label" style="fill:#93c5fd;">DOH / Regulators</text>
      <text x="96" y="510" class="node-sublabel">Reports · Compliance</text>
    </g>
    <!-- Other Facilities — new for referral -->
    <g class="node-group" onclick="showInfo('b','facilities_basic')">
      <rect x="18" y="580" width="155" height="50" rx="5" fill="#0f1c38" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="96" y="600" class="node-label" style="fill:#93c5fd;">Other Facilities</text>
      <text x="96" y="615" class="node-sublabel">RHU · District · Provincial</text>
    </g>

    <!-- Process Modules (left column) -->
    <g class="node-group" onclick="showInfo('b','reg')">
      <rect x="240" y="55" width="150" height="50" rx="4" fill="#0f1e0f" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="315" y="75" class="node-label" style="fill:#93c5fd;font-size:10px;">1.0 Registration</text>
      <text x="315" y="90" class="node-label" style="fill:#93c5fd;font-size:10px;">& Admission</text>
    </g>
    <g class="node-group" onclick="showInfo('b','clinical')">
      <rect x="240" y="168" width="150" height="50" rx="4" fill="#0f1e0f" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="315" y="188" class="node-label" style="fill:#93c5fd;font-size:10px;">2.0 Clinical Records</text>
      <text x="315" y="203" class="node-label" style="fill:#93c5fd;font-size:10px;">& Basic EMR</text>
    </g>
    <g class="node-group" onclick="showInfo('b','billing')">
      <rect x="240" y="281" width="150" height="50" rx="4" fill="#0f1e0f" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="315" y="301" class="node-label" style="fill:#93c5fd;font-size:10px;">3.0 Billing &</text>
      <text x="315" y="316" class="node-label" style="fill:#93c5fd;font-size:10px;">Collection</text>
    </g>
    <g class="node-group" onclick="showInfo('b','phproc')">
      <rect x="240" y="394" width="150" height="50" rx="4" fill="#0f1e0f" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="315" y="414" class="node-label" style="fill:#93c5fd;font-size:10px;">4.0 PhilHealth</text>
      <text x="315" y="429" class="node-label" style="fill:#93c5fd;font-size:10px;">eClaims</text>
    </g>
    <g class="node-group" onclick="showInfo('b','referral_basic')">
      <rect x="240" y="507" width="150" height="50" rx="4" fill="#0f1e0f" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="315" y="527" class="node-label" style="fill:#93c5fd;font-size:10px;">6.0 Patient Transfer</text>
      <text x="315" y="542" class="node-label" style="fill:#93c5fd;font-size:10px;">& Referral</text>
    </g>

    <!-- Process Modules (right column) -->
    <g class="node-group" onclick="showInfo('b','reporting_basic')">
      <rect x="668" y="168" width="155" height="50" rx="4" fill="#0f1e0f" stroke="#60a5fa" stroke-width="1.5"/>
      <text x="745" y="188" class="node-label" style="fill:#93c5fd;font-size:10px;">5.0 Basic Reporting</text>
      <text x="745" y="203" class="node-label" style="fill:#93c5fd;font-size:10px;">& DOH Compliance</text>
    </g>

    <!-- Data Stores -->
    <g class="node-group" onclick="showInfo('b','ds1')">
      <rect x="879" y="70" width="158" height="42" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/>
      <text x="958" y="86" class="node-label" style="fill:#fdba74;font-size:10px;">DS1: Patient DB</text>
      <text x="958" y="100" class="node-sublabel">Demographics · History</text>
    </g>
    <g class="node-group" onclick="showInfo('b','ds2')">
      <rect x="879" y="185" width="158" height="42" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/>
      <text x="958" y="201" class="node-label" style="fill:#fdba74;font-size:10px;">DS2: Financial DB</text>
      <text x="958" y="215" class="node-sublabel">Billing · Payments</text>
    </g>
    <g class="node-group" onclick="showInfo('b','ds3')">
      <rect x="879" y="300" width="158" height="42" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/>
      <text x="958" y="316" class="node-label" style="fill:#fdba74;font-size:10px;">DS3: Clinical DB</text>
      <text x="958" y="330" class="node-sublabel">Diagnoses · Orders</text>
    </g>
    <!-- DS: Referral Log — new -->
    <g class="node-group" onclick="showInfo('b','ds_referral')">
      <rect x="879" y="415" width="158" height="42" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/>
      <text x="958" y="431" class="node-label" style="fill:#fdba74;font-size:10px;">DS4: Referral Log DB</text>
      <text x="958" y="445" class="node-sublabel">Transfers · Status · Forms</text>
    </g>

    <!-- ── ARROWS ── -->
    <!-- Externals → Modules -->
    <line x1="173" y1="80" x2="238" y2="80" class="dfd-arrow" stroke="#60a5fa" marker-end="url(#ab-b)"/>
    <text x="206" y="74" class="flow-label" fill="#60a5fa">Patient Info</text>
    <line x1="173" y1="185" x2="238" y2="193" class="dfd-arrow" stroke="#60a5fa" marker-end="url(#ab-b)"/>
    <text x="203" y="180" class="flow-label" fill="#60a5fa">Clinical Data</text>
    <line x1="173" y1="290" x2="238" y2="306" class="dfd-arrow" stroke="#60a5fa" marker-end="url(#ab-b)"/>
    <text x="200" y="291" class="flow-label" fill="#60a5fa">Billing Input</text>
    <line x1="173" y1="395" x2="238" y2="419" class="dfd-arrow" stroke="#60a5fa" stroke-dasharray="4,2" marker-end="url(#ab-b)"/>
    <text x="196" y="400" class="flow-label" fill="#60a5fa">Eligibility</text>
    <line x1="238" y1="434" x2="173" y2="410" class="dfd-arrow" stroke="#60a5fa" stroke-dasharray="4,2" marker-end="url(#ab-b)"/>
    <text x="193" y="447" class="flow-label" fill="#60a5fa">Claims</text>
    <!-- Facilities ↔ Referral module -->
    <line x1="173" y1="605" x2="238" y2="532" class="dfd-arrow" stroke="#60a5fa" stroke-dasharray="4,2" marker-end="url(#ab-b)"/>
    <text x="185" y="565" class="flow-label" fill="#60a5fa">Referral Req.</text>
    <line x1="238" y1="545" x2="173" y2="618" class="dfd-arrow" stroke="#60a5fa" stroke-dasharray="4,2" marker-end="url(#ab-b)"/>
    <text x="162" y="590" class="flow-label" fill="#60a5fa">Accept/Transfer</text>

    <!-- Modules → Core -->
    <line x1="390" y1="80"  x2="452" y2="342" class="dfd-arrow" stroke="#60a5fa" stroke-dasharray="4,2" marker-end="url(#ab-b)"/>
    <line x1="390" y1="193" x2="452" y2="352" class="dfd-arrow" stroke="#60a5fa" stroke-dasharray="4,2" marker-end="url(#ab-b)"/>
    <line x1="390" y1="306" x2="453" y2="360" class="dfd-arrow" stroke="#60a5fa" stroke-dasharray="4,2" marker-end="url(#ab-b)"/>
    <line x1="390" y1="419" x2="453" y2="370" class="dfd-arrow" stroke="#60a5fa" stroke-dasharray="4,2" marker-end="url(#ab-b)"/>
    <line x1="390" y1="532" x2="454" y2="390" class="dfd-arrow" stroke="#60a5fa" stroke-dasharray="4,2" marker-end="url(#ab-b)"/>

    <!-- Core → Reporting -->
    <line x1="608" y1="350" x2="666" y2="193" class="dfd-arrow" stroke="#60a5fa" marker-end="url(#ab-b)"/>
    <text x="648" y="276" class="flow-label" fill="#60a5fa">Aggregated</text>

    <!-- Reporting → DOH -->
    <line x1="668" y1="193" x2="173" y2="490" class="dfd-arrow" stroke="#334155" stroke-dasharray="4,3" marker-end="url(#ab)"/>
    <text x="390" y="318" class="flow-label" fill="#475569">DOH Reports</text>

    <!-- Core ↔ Data Stores -->
    <line x1="610" y1="345" x2="877" y2="91"  class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ab-o)"/>
    <line x1="610" y1="358" x2="877" y2="206" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ab-o)"/>
    <line x1="609" y1="371" x2="877" y2="321" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ab-o)"/>
    <!-- Referral module ↔ DS4 Referral Log -->
    <line x1="390" y1="545" x2="877" y2="436" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ab-o)"/>
    <text x="620" y="510" class="flow-label" fill="#f97316">Referral Records</text>

    <!-- Billing → Patient receipt -->
    <line x1="240" y1="291" x2="173" y2="75" class="dfd-arrow" stroke="#334155" stroke-dasharray="3,4" marker-end="url(#ab)"/>
    <text x="158" y="183" class="flow-label" fill="#334155">Receipt</text>
  </svg>
  </div>

  <div class="section-divider">Module Functions — Basic Tier</div>
  <div class="module-grid" id="modules-basic"></div>
</div>

<!-- ============================================================ PAGE 2: PROFESSIONAL ============================================================ -->
<div class="page" id="page-pro" style="--tier-accent:#34d399;--tier-accent-bg:rgba(52,211,153,0.08);--tier-accent-border:rgba(52,211,153,0.25);--hero-bg:#07130e;--hero-glow:rgba(52,211,153,0.07);">
  <div class="tier-hero">
    <div class="tier-eyebrow">Tier 2 · Growth</div>
    <h1>Professional — The "Growth" Tier</h1>
    <div class="sub">Full-featured hospital management for provincial and regional hospitals. Adds advanced clinical workflows, HR/payroll, inventory management, inter-facility referrals, and automated scheduling on top of the Essential tier.</div>
    <div class="tier-pills">
      <span class="tier-pill">50–300 Beds</span>
      <span class="tier-pill">9 Modules</span>
      <span class="tier-pill">6 Data Stores</span>
      <span class="tier-pill">Provincial / Regional Hospital</span>
      <span class="tier-pill">Everything in Basic +</span>
    </div>
  </div>

  <div class="legend-bar">
    <div class="legend-item"><div class="legend-dot" style="background:#1a2744;border:1.5px solid #34d399;"></div>External Entity</div>
    <div class="legend-item"><div class="legend-dot" style="background:#1a2a1a;border:1.5px solid #34d399;"></div>Process Module</div>
    <div class="legend-item"><div class="legend-dot" style="background:#2a1a1a;border:1.5px solid #f97316;"></div>Data Store</div>
    <div class="legend-item"><div class="legend-dot" style="background:#1e1040;border:2px solid #34d399;border-radius:50%;"></div>Core Engine</div>
    <div class="legend-item"><div class="legend-dot" style="background:#0f1e0f;border:1.5px solid #fbbf24;"></div>New in Pro</div>
    <span class="legend-hint">Click any node for functions →</span>
  </div>

  <div class="diagram-area">
  <svg id="svg-pro" width="1060" height="860" viewBox="0 0 1060 860" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <marker id="ap" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#334155"/></marker>
      <marker id="ap-g" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#34d399"/></marker>
      <marker id="ap-o" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#f97316"/></marker>
      <marker id="ap-y" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#fbbf24"/></marker>
      <filter id="glow-p"><feGaussianBlur stdDeviation="4" result="cp"/><feMerge><feMergeNode in="cp"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
      <pattern id="grid-p" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M40 0L0 0 0 40" fill="none" stroke="#1c2740" stroke-width=".3"/></pattern>
    </defs>
    <rect width="1060" height="860" fill="url(#grid-p)" opacity=".3"/>
    <rect x="8" y="8" width="175" height="844" rx="7" fill="#0a0e18" stroke="#1c2740" stroke-width=".8" opacity=".7"/>
    <text x="96" y="28" class="section-title" fill="#1c2740">External</text>
    <rect x="870" y="8" width="182" height="844" rx="7" fill="#0a0e18" stroke="#1c2740" stroke-width=".8" opacity=".7"/>
    <text x="961" y="28" class="section-title" fill="#1c2740">Data Stores</text>

    <!-- Core -->
    <g class="node-group" onclick="showInfo('p','core')" filter="url(#glow-p)">
      <ellipse cx="530" cy="420" rx="85" ry="58" fill="#0e180e" stroke="#34d399" stroke-width="2.5"/>
      <circle class="pulse-ring" cx="530" cy="420" r="9" fill="none" stroke="#34d399" stroke-width="1.5" opacity=".4"/>
      <text x="530" y="411" class="node-label" style="fill:#6ee7b7;font-weight:600;font-size:12px;">iHOMIS+</text>
      <text x="530" y="425" class="node-label" style="fill:#6ee7b7;font-size:10px;">Core (Pro)</text>
      <text x="530" y="439" class="node-sublabel">Growth Layer</text>
    </g>

    <!-- Externals -->
    <g class="node-group" onclick="showInfo('p','patient')"><rect x="18" y="45" width="155" height="48" rx="5" fill="#0f1c38" stroke="#34d399" stroke-width="1.5"/><text x="96" y="65" class="node-label" style="fill:#6ee7b7;">Patient / Family</text><text x="96" y="80" class="node-sublabel">Walk-in · Referral · Emergency</text></g>
    <g class="node-group" onclick="showInfo('p','doctor')"><rect x="18" y="150" width="155" height="48" rx="5" fill="#0f1c38" stroke="#34d399" stroke-width="1.5"/><text x="96" y="170" class="node-label" style="fill:#6ee7b7;">Medical Staff</text><text x="96" y="185" class="node-sublabel">Doctors · Nurses · Specialists</text></g>
    <g class="node-group" onclick="showInfo('p','admin')"><rect x="18" y="255" width="155" height="48" rx="5" fill="#0f1c38" stroke="#34d399" stroke-width="1.5"/><text x="96" y="275" class="node-label" style="fill:#6ee7b7;">Admin Staff</text><text x="96" y="290" class="node-sublabel">Billing · HR · Records</text></g>
    <g class="node-group" onclick="showInfo('p','philhealth')"><rect x="18" y="360" width="155" height="48" rx="5" fill="#0f1c38" stroke="#34d399" stroke-width="1.5"/><text x="96" y="380" class="node-label" style="fill:#6ee7b7;">PhilHealth</text><text x="96" y="395" class="node-sublabel">Claims · Eligibility</text></g>
    <g class="node-group" onclick="showInfo('p','doh')"><rect x="18" y="465" width="155" height="48" rx="5" fill="#0f1c38" stroke="#34d399" stroke-width="1.5"/><text x="96" y="485" class="node-label" style="fill:#6ee7b7;">DOH / Regulators</text><text x="96" y="500" class="node-sublabel">Reports · Compliance</text></g>
    <!-- Other Facilities — prominently placed for referral -->
    <g class="node-group" onclick="showInfo('p','facilities')">
      <rect x="18" y="570" width="155" height="48" rx="5" fill="#0f1c38" stroke="#fbbf24" stroke-width="2"/>
      <text x="96" y="590" class="node-label" style="fill:#fde68a;">Other Facilities</text>
      <text x="96" y="605" class="node-sublabel">RHU · District · Provincial</text>
    </g>
    <g class="node-group" onclick="showInfo('p','supplier')"><rect x="18" y="675" width="155" height="48" rx="5" fill="#0f1c38" stroke="#34d399" stroke-width="1.5"/><text x="96" y="695" class="node-label" style="fill:#6ee7b7;">Pharmacy / Supplier</text><text x="96" y="710" class="node-sublabel">Drugs · Equipment</text></g>

    <!-- Process modules LEFT -->
    <g class="node-group" onclick="showInfo('p','reg')"><rect x="238" y="45" width="148" height="48" rx="4" fill="#0f1e0f" stroke="#34d399" stroke-width="1.5"/><text x="312" y="65" class="node-label" style="fill:#6ee7b7;font-size:10px;">1.0 Registration</text><text x="312" y="80" class="node-label" style="fill:#6ee7b7;font-size:10px;">& Admission</text></g>
    <g class="node-group" onclick="showInfo('p','clinical')"><rect x="238" y="158" width="148" height="48" rx="4" fill="#0f1e0f" stroke="#34d399" stroke-width="1.5"/><text x="312" y="178" class="node-label" style="fill:#6ee7b7;font-size:10px;">2.0 Clinical Records</text><text x="312" y="193" class="node-label" style="fill:#6ee7b7;font-size:10px;">& EMR</text></g>
    <g class="node-group" onclick="showInfo('p','billing')"><rect x="238" y="271" width="148" height="48" rx="4" fill="#0f1e0f" stroke="#34d399" stroke-width="1.5"/><text x="312" y="291" class="node-label" style="fill:#6ee7b7;font-size:10px;">3.0 Billing &</text><text x="312" y="306" class="node-label" style="fill:#6ee7b7;font-size:10px;">Financial Mgmt</text></g>
    <g class="node-group" onclick="showInfo('p','phproc')"><rect x="238" y="384" width="148" height="48" rx="4" fill="#0f1e0f" stroke="#34d399" stroke-width="1.5"/><text x="312" y="404" class="node-label" style="fill:#6ee7b7;font-size:10px;">4.0 PhilHealth</text><text x="312" y="419" class="node-label" style="fill:#6ee7b7;font-size:10px;">eClaims</text></g>
    <g class="node-group" onclick="showInfo('p','inventory')"><rect x="238" y="497" width="148" height="48" rx="4" fill="#0f1e0f" stroke="#fbbf24" stroke-width="1.5"/><text x="312" y="517" class="node-label" style="fill:#fde68a;font-size:10px;">5.0 Inventory &</text><text x="312" y="532" class="node-label" style="fill:#fde68a;font-size:10px;">Supply Mgmt ✦</text></g>
    <g class="node-group" onclick="showInfo('p','hr')"><rect x="238" y="610" width="148" height="48" rx="4" fill="#0f1e0f" stroke="#fbbf24" stroke-width="1.5"/><text x="312" y="630" class="node-label" style="fill:#fde68a;font-size:10px;">6.0 HR & Payroll ✦</text><text x="312" y="645" class="node-label" style="fill:#fde68a;font-size:9px;">Management</text></g>

    <!-- Process modules RIGHT -->
    <g class="node-group" onclick="showInfo('p','reporting')"><rect x="672" y="158" width="148" height="48" rx="4" fill="#0f1e0f" stroke="#fbbf24" stroke-width="1.5"/><text x="746" y="178" class="node-label" style="fill:#fde68a;font-size:10px;">7.0 Reporting &</text><text x="746" y="193" class="node-label" style="fill:#fde68a;font-size:10px;">Analytics ✦</text></g>
    <!-- Referral module — highlighted with bright border -->
    <g class="node-group" onclick="showInfo('p','referral')">
      <rect x="672" y="384" width="148" height="48" rx="4" fill="#141e0e" stroke="#fbbf24" stroke-width="2.5"/>
      <text x="746" y="404" class="node-label" style="fill:#fde68a;font-size:10px;">8.0 Referral &</text>
      <text x="746" y="419" class="node-label" style="fill:#fde68a;font-size:10px;">Inter-Facility Transfer ✦</text>
    </g>
    <g class="node-group" onclick="showInfo('p','scheduling')"><rect x="672" y="497" width="148" height="48" rx="4" fill="#0f1e0f" stroke="#fbbf24" stroke-width="1.5"/><text x="746" y="517" class="node-label" style="fill:#fde68a;font-size:10px;">9.0 Scheduling &</text><text x="746" y="532" class="node-label" style="fill:#fde68a;font-size:10px;">Appointments ✦</text></g>

    <!-- Data Stores -->
    <g class="node-group" onclick="showInfo('p','ds1')"><rect x="879" y="55" width="158" height="40" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="70" class="node-label" style="fill:#fdba74;font-size:10px;">DS1: Patient DB</text><text x="958" y="84" class="node-sublabel">Demographics · History</text></g>
    <g class="node-group" onclick="showInfo('p','ds2')"><rect x="879" y="165" width="158" height="40" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="180" class="node-label" style="fill:#fdba74;font-size:10px;">DS2: Financial DB</text><text x="958" y="194" class="node-sublabel">Billing · Payments · Ledger</text></g>
    <g class="node-group" onclick="showInfo('p','ds3')"><rect x="879" y="275" width="158" height="40" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="290" class="node-label" style="fill:#fdba74;font-size:10px;">DS3: Clinical DB</text><text x="958" y="304" class="node-sublabel">Diagnoses · Orders · Labs</text></g>
    <g class="node-group" onclick="showInfo('p','ds4')"><rect x="879" y="385" width="158" height="40" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="400" class="node-label" style="fill:#fdba74;font-size:10px;">DS4: Inventory DB</text><text x="958" y="414" class="node-sublabel">Drugs · Supplies</text></g>
    <g class="node-group" onclick="showInfo('p','ds5')"><rect x="879" y="495" width="158" height="40" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="510" class="node-label" style="fill:#fdba74;font-size:10px;">DS5: HR & Staff DB</text><text x="958" y="524" class="node-sublabel">Schedules · Payroll</text></g>
    <g class="node-group" onclick="showInfo('p','ds6')"><rect x="879" y="605" width="158" height="40" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="620" class="node-label" style="fill:#fdba74;font-size:10px;">DS6: Reports Archive</text><text x="958" y="634" class="node-sublabel">DOH · Audit · Analytics</text></g>
    <!-- DS7 Referral DB — new -->
    <g class="node-group" onclick="showInfo('p','ds_referral')">
      <rect x="879" y="715" width="158" height="40" rx="4" fill="#1a1400" stroke="#fbbf24" stroke-width="2"/>
      <text x="958" y="730" class="node-label" style="fill:#fde68a;font-size:10px;">DS7: Referral DB ✦</text>
      <text x="958" y="744" class="node-sublabel">Transfers · Status · Forms</text>
    </g>

    <!-- ── ARROWS ── -->
    <!-- Externals → Left Modules -->
    <line x1="173" y1="69" x2="236" y2="69"  class="dfd-arrow" stroke="#34d399" marker-end="url(#ap-g)"/>
    <line x1="173" y1="174" x2="236" y2="182" class="dfd-arrow" stroke="#34d399" marker-end="url(#ap-g)"/>
    <line x1="173" y1="279" x2="236" y2="295" class="dfd-arrow" stroke="#34d399" marker-end="url(#ap-g)"/>
    <line x1="173" y1="384" x2="236" y2="408" class="dfd-arrow" stroke="#34d399" stroke-dasharray="4,2" marker-end="url(#ap-g)"/>
    <line x1="236" y1="422" x2="173" y2="400" class="dfd-arrow" stroke="#34d399" stroke-dasharray="4,2" marker-end="url(#ap-g)"/>
    <line x1="173" y1="699" x2="236" y2="521" class="dfd-arrow" stroke="#34d399" marker-end="url(#ap-g)"/>

    <!-- Facilities ↔ Referral module (bidirectional) -->
    <line x1="173" y1="589" x2="670" y2="408" class="dfd-arrow" stroke="#fbbf24" stroke-width="2" stroke-dasharray="5,3" marker-end="url(#ap-y)"/>
    <text x="400" y="488" class="flow-label" fill="#fbbf24">Referral Request / Transfer</text>
    <line x1="670" y1="422" x2="173" y2="604" class="dfd-arrow" stroke="#fbbf24" stroke-width="2" stroke-dasharray="5,3" marker-end="url(#ap-y)"/>
    <text x="390" y="530" class="flow-label" fill="#fbbf24">Accept / Clinical Rec.</text>

    <!-- Left Modules → Core -->
    <line x1="386" y1="69"  x2="448" y2="392" class="dfd-arrow" stroke="#34d399" stroke-dasharray="4,2" marker-end="url(#ap-g)"/>
    <line x1="386" y1="182" x2="449" y2="398" class="dfd-arrow" stroke="#34d399" stroke-dasharray="4,2" marker-end="url(#ap-g)"/>
    <line x1="386" y1="295" x2="450" y2="408" class="dfd-arrow" stroke="#34d399" stroke-dasharray="4,2" marker-end="url(#ap-g)"/>
    <line x1="386" y1="408" x2="451" y2="416" class="dfd-arrow" stroke="#34d399" stroke-dasharray="4,2" marker-end="url(#ap-g)"/>
    <line x1="386" y1="521" x2="452" y2="424" class="dfd-arrow" stroke="#34d399" stroke-dasharray="4,2" marker-end="url(#ap-g)"/>
    <line x1="386" y1="634" x2="455" y2="436" class="dfd-arrow" stroke="#34d399" stroke-dasharray="4,2" marker-end="url(#ap-g)"/>

    <!-- Core → Right Modules -->
    <line x1="612" y1="398" x2="670" y2="182" class="dfd-arrow" stroke="#fbbf24" marker-end="url(#ap-y)"/>
    <line x1="614" y1="420" x2="670" y2="408" class="dfd-arrow" stroke="#fbbf24" marker-end="url(#ap-y)"/>
    <line x1="613" y1="432" x2="670" y2="521" class="dfd-arrow" stroke="#fbbf24" marker-end="url(#ap-y)"/>

    <!-- Reporting → DOH -->
    <line x1="672" y1="182" x2="173" y2="479" class="dfd-arrow" stroke="#334155" stroke-dasharray="4,3" marker-end="url(#ap)"/>

    <!-- Core ↔ Data Stores -->
    <line x1="612" y1="388" x2="877" y2="75"  class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ap-o)"/>
    <line x1="612" y1="402" x2="877" y2="185" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ap-o)"/>
    <line x1="612" y1="414" x2="877" y2="295" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ap-o)"/>
    <line x1="612" y1="426" x2="877" y2="405" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ap-o)"/>
    <line x1="612" y1="438" x2="877" y2="515" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ap-o)"/>
    <line x1="820" y1="182" x2="877" y2="625" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ap-o)"/>
    <!-- Referral module → DS7 -->
    <line x1="820" y1="418" x2="877" y2="735" class="dfd-arrow" stroke="#fbbf24" stroke-dasharray="3,2" marker-end="url(#ap-y)"/>
    <text x="858" y="590" class="flow-label" fill="#fbbf24">Referral</text>
    <text x="858" y="600" class="flow-label" fill="#fbbf24">Records</text>

    <text x="373" y="220" class="flow-label" fill="#34d399">Module Data</text>
    <text x="640" y="318" class="flow-label" fill="#fbbf24">Outputs</text>
    <text x="358" y="452" class="flow-label" fill="#334155">DOH Reports</text>
    <text x="756" y="310" class="flow-label" fill="#f97316">DB R/W</text>
  </svg>
  </div>

  <div class="section-divider">Module Functions — Professional Tier <span style="color:#fbbf24;font-size:9px;margin-left:8px;">✦ = New in Pro</span></div>
  <div class="module-grid" id="modules-pro"></div>
</div>

<!-- ============================================================ PAGE 3: ENTERPRISE ============================================================ -->
<div class="page" id="page-ent" style="--tier-accent:#c084fc;--tier-accent-bg:rgba(192,132,252,0.08);--tier-accent-border:rgba(192,132,252,0.25);--hero-bg:#0d0714;--hero-glow:rgba(192,132,252,0.07);">
  <div class="tier-hero">
    <div class="tier-eyebrow">Tier 3 · Scale</div>
    <h1>Enterprise — The "Scale" Tier</h1>
    <div class="sub">Full-scale hospital network management for medical centers, teaching hospitals, and DOH-retained facilities. Adds AI analytics, multi-facility network management, telemedicine, advanced BI dashboards, and deep ERP integration on top of the Professional tier.</div>
    <div class="tier-pills">
      <span class="tier-pill">300+ Beds</span>
      <span class="tier-pill">13 Modules</span>
      <span class="tier-pill">9 Data Stores</span>
      <span class="tier-pill">Medical Centers · Teaching Hospitals</span>
      <span class="tier-pill">Everything in Pro +</span>
      <span class="tier-pill">AI-Powered</span>
    </div>
  </div>

  <div class="legend-bar">
    <div class="legend-item"><div class="legend-dot" style="background:#1a2744;border:1.5px solid #c084fc;"></div>External Entity</div>
    <div class="legend-item"><div class="legend-dot" style="background:#1a2a1a;border:1.5px solid #c084fc;"></div>Process Module</div>
    <div class="legend-item"><div class="legend-dot" style="background:#2a1a1a;border:1.5px solid #f97316;"></div>Data Store</div>
    <div class="legend-item"><div class="legend-dot" style="background:#1e1040;border:2px solid #c084fc;border-radius:50%;"></div>Core Engine</div>
    <div class="legend-item"><div class="legend-dot" style="background:#1e0e2a;border:1.5px solid #f472b6;"></div>New in Enterprise</div>
    <span class="legend-hint">Click any node for functions →</span>
  </div>

  <div class="diagram-area">
  <svg id="svg-ent" width="1060" height="900" viewBox="0 0 1060 900" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <marker id="ae" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#334155"/></marker>
      <marker id="ae-p" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#c084fc"/></marker>
      <marker id="ae-o" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#f97316"/></marker>
      <marker id="ae-pk" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto"><path d="M0,0 L0,6 L7,3 z" fill="#f472b6"/></marker>
      <filter id="glow-e"><feGaussianBlur stdDeviation="5" result="ce"/><feMerge><feMergeNode in="ce"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
      <pattern id="grid-e" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M40 0L0 0 0 40" fill="none" stroke="#1c2740" stroke-width=".3"/></pattern>
    </defs>
    <rect width="1060" height="900" fill="url(#grid-e)" opacity=".3"/>
    <rect x="8" y="8" width="175" height="884" rx="7" fill="#0a0e18" stroke="#1c2740" stroke-width=".8" opacity=".7"/>
    <text x="96" y="28" class="section-title" fill="#1c2740">External</text>
    <rect x="870" y="8" width="182" height="884" rx="7" fill="#0a0e18" stroke="#1c2740" stroke-width=".8" opacity=".7"/>
    <text x="961" y="28" class="section-title" fill="#1c2740">Data Stores</text>

    <!-- Core -->
    <g class="node-group" onclick="showInfo('e','core')" filter="url(#glow-e)">
      <ellipse cx="530" cy="450" rx="90" ry="62" fill="#160a28" stroke="#c084fc" stroke-width="3"/>
      <circle class="pulse-ring" cx="530" cy="450" r="10" fill="none" stroke="#c084fc" stroke-width="2" opacity=".5"/>
      <circle class="pulse-ring" cx="530" cy="450" r="10" fill="none" stroke="#c084fc" stroke-width="1" opacity=".3" style="animation-delay:.8s"/>
      <text x="530" y="440" class="node-label" style="fill:#d8b4fe;font-weight:700;font-size:13px;">iHOMIS+</text>
      <text x="530" y="455" class="node-label" style="fill:#d8b4fe;font-size:10px;">Core (Enterprise)</text>
      <text x="530" y="469" class="node-sublabel">AI-Powered · Scale Layer</text>
    </g>

    <!-- Externals -->
    <g class="node-group" onclick="showInfo('e','patient')"><rect x="18" y="45" width="155" height="46" rx="5" fill="#0f1c38" stroke="#c084fc" stroke-width="1.5"/><text x="96" y="65" class="node-label" style="fill:#d8b4fe;">Patient / Family</text><text x="96" y="79" class="node-sublabel">Walk-in · Portal · Telemedicine</text></g>
    <g class="node-group" onclick="showInfo('e','doctor')"><rect x="18" y="140" width="155" height="46" rx="5" fill="#0f1c38" stroke="#c084fc" stroke-width="1.5"/><text x="96" y="160" class="node-label" style="fill:#d8b4fe;">Medical Staff</text><text x="96" y="175" class="node-sublabel">Doctors · Nurses · Specialists</text></g>
    <g class="node-group" onclick="showInfo('e','admin')"><rect x="18" y="235" width="155" height="46" rx="5" fill="#0f1c38" stroke="#c084fc" stroke-width="1.5"/><text x="96" y="255" class="node-label" style="fill:#d8b4fe;">Admin / Management</text><text x="96" y="270" class="node-sublabel">Execs · Finance · IT · HR</text></g>
    <g class="node-group" onclick="showInfo('e','philhealth')"><rect x="18" y="330" width="155" height="46" rx="5" fill="#0f1c38" stroke="#c084fc" stroke-width="1.5"/><text x="96" y="350" class="node-label" style="fill:#d8b4fe;">PhilHealth / HMO</text><text x="96" y="365" class="node-sublabel">Claims · Eligibility · Accreditation</text></g>
    <g class="node-group" onclick="showInfo('e','doh')"><rect x="18" y="425" width="155" height="46" rx="5" fill="#0f1c38" stroke="#c084fc" stroke-width="1.5"/><text x="96" y="445" class="node-label" style="fill:#d8b4fe;">DOH / Regulators</text><text x="96" y="460" class="node-sublabel">Reports · Licensing · Policy</text></g>
    <g class="node-group" onclick="showInfo('e','facilities')">
      <rect x="18" y="520" width="155" height="46" rx="5" fill="#0f1c38" stroke="#f472b6" stroke-width="2"/>
      <text x="96" y="540" class="node-label" style="fill:#f9a8d4;">Network Facilities</text>
      <text x="96" y="555" class="node-sublabel">Branches · Satellites · Partners</text>
    </g>
    <g class="node-group" onclick="showInfo('e','supplier')"><rect x="18" y="615" width="155" height="46" rx="5" fill="#0f1c38" stroke="#c084fc" stroke-width="1.5"/><text x="96" y="635" class="node-label" style="fill:#d8b4fe;">Pharmacy / Suppliers</text><text x="96" y="650" class="node-sublabel">Drugs · Equipment · ERP</text></g>
    <g class="node-group" onclick="showInfo('e','external_sys')"><rect x="18" y="710" width="155" height="46" rx="5" fill="#0f1c38" stroke="#f472b6" stroke-width="1.5"/><text x="96" y="730" class="node-label" style="fill:#f9a8d4;">External Systems ✦</text><text x="96" y="745" class="node-sublabel">LIS · PACS · ERP · HIS</text></g>

    <!-- Left process modules -->
    <g class="node-group" onclick="showInfo('e','reg')"><rect x="236" y="45" width="148" height="46" rx="4" fill="#1a0e28" stroke="#c084fc" stroke-width="1.5"/><text x="310" y="65" class="node-label" style="fill:#d8b4fe;font-size:10px;">1.0 Registration</text><text x="310" y="79" class="node-label" style="fill:#d8b4fe;font-size:10px;">& Admission</text></g>
    <g class="node-group" onclick="showInfo('e','clinical')"><rect x="236" y="148" width="148" height="46" rx="4" fill="#1a0e28" stroke="#c084fc" stroke-width="1.5"/><text x="310" y="168" class="node-label" style="fill:#d8b4fe;font-size:10px;">2.0 Clinical Records</text><text x="310" y="182" class="node-label" style="fill:#d8b4fe;font-size:10px;">& Advanced EMR</text></g>
    <g class="node-group" onclick="showInfo('e','billing')"><rect x="236" y="251" width="148" height="46" rx="4" fill="#1a0e28" stroke="#c084fc" stroke-width="1.5"/><text x="310" y="271" class="node-label" style="fill:#d8b4fe;font-size:10px;">3.0 Billing & Revenue</text><text x="310" y="285" class="node-label" style="fill:#d8b4fe;font-size:10px;">Cycle Mgmt</text></g>
    <g class="node-group" onclick="showInfo('e','phproc')"><rect x="236" y="354" width="148" height="46" rx="4" fill="#1a0e28" stroke="#c084fc" stroke-width="1.5"/><text x="310" y="374" class="node-label" style="fill:#d8b4fe;font-size:10px;">4.0 PhilHealth / HMO</text><text x="310" y="388" class="node-label" style="fill:#d8b4fe;font-size:10px;">Claims</text></g>
    <g class="node-group" onclick="showInfo('e','inventory')"><rect x="236" y="457" width="148" height="46" rx="4" fill="#1a0e28" stroke="#c084fc" stroke-width="1.5"/><text x="310" y="477" class="node-label" style="fill:#d8b4fe;font-size:10px;">5.0 Inventory &</text><text x="310" y="491" class="node-label" style="fill:#d8b4fe;font-size:10px;">Supply Chain</text></g>
    <g class="node-group" onclick="showInfo('e','hr')"><rect x="236" y="560" width="148" height="46" rx="4" fill="#1a0e28" stroke="#c084fc" stroke-width="1.5"/><text x="310" y="580" class="node-label" style="fill:#d8b4fe;font-size:10px;">6.0 HR, Payroll &</text><text x="310" y="594" class="node-label" style="fill:#d8b4fe;font-size:10px;">Workforce Mgmt</text></g>
    <g class="node-group" onclick="showInfo('e','telemedicine')"><rect x="236" y="663" width="148" height="46" rx="4" fill="#1e0e2a" stroke="#f472b6" stroke-width="1.5"/><text x="310" y="683" class="node-label" style="fill:#f9a8d4;font-size:10px;">10.0 Telemedicine</text><text x="310" y="697" class="node-label" style="fill:#f9a8d4;font-size:10px;">& Patient Portal ✦</text></g>

    <!-- Right process modules -->
    <g class="node-group" onclick="showInfo('e','reporting')"><rect x="672" y="148" width="148" height="46" rx="4" fill="#1a0e28" stroke="#c084fc" stroke-width="1.5"/><text x="746" y="168" class="node-label" style="fill:#d8b4fe;font-size:10px;">7.0 Reporting &</text><text x="746" y="182" class="node-label" style="fill:#d8b4fe;font-size:10px;">BI Analytics</text></g>
    <g class="node-group" onclick="showInfo('e','referral')">
      <rect x="672" y="354" width="148" height="46" rx="4" fill="#160e00" stroke="#f472b6" stroke-width="2.5"/>
      <text x="746" y="374" class="node-label" style="fill:#f9a8d4;font-size:10px;">8.0 Referral &</text>
      <text x="746" y="388" class="node-label" style="fill:#f9a8d4;font-size:10px;">Network Transfer ✦</text>
    </g>
    <g class="node-group" onclick="showInfo('e','scheduling')"><rect x="672" y="457" width="148" height="46" rx="4" fill="#1a0e28" stroke="#c084fc" stroke-width="1.5"/><text x="746" y="477" class="node-label" style="fill:#d8b4fe;font-size:10px;">9.0 Scheduling &</text><text x="746" y="491" class="node-label" style="fill:#d8b4fe;font-size:10px;">Resource Planning</text></g>
    <g class="node-group" onclick="showInfo('e','ai_analytics')"><rect x="672" y="251" width="148" height="46" rx="4" fill="#1e0e2a" stroke="#f472b6" stroke-width="1.5"/><text x="746" y="271" class="node-label" style="fill:#f9a8d4;font-size:10px;">11.0 AI Analytics &</text><text x="746" y="285" class="node-label" style="fill:#f9a8d4;font-size:10px;">Decision Support ✦</text></g>
    <g class="node-group" onclick="showInfo('e','network')"><rect x="672" y="560" width="148" height="46" rx="4" fill="#1e0e2a" stroke="#f472b6" stroke-width="1.5"/><text x="746" y="580" class="node-label" style="fill:#f9a8d4;font-size:10px;">12.0 Multi-Facility</text><text x="746" y="594" class="node-label" style="fill:#f9a8d4;font-size:10px;">Network Mgmt ✦</text></g>
    <g class="node-group" onclick="showInfo('e','integration')"><rect x="672" y="663" width="148" height="46" rx="4" fill="#1e0e2a" stroke="#f472b6" stroke-width="1.5"/><text x="746" y="683" class="node-label" style="fill:#f9a8d4;font-size:10px;">13.0 System</text><text x="746" y="697" class="node-label" style="fill:#f9a8d4;font-size:10px;">Integration Hub ✦</text></g>

    <!-- Data stores -->
    <g class="node-group" onclick="showInfo('e','ds1')"><rect x="879" y="55" width="158" height="38" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="69" class="node-label" style="fill:#fdba74;font-size:10px;">DS1: Patient DB</text><text x="958" y="83" class="node-sublabel">Master · History</text></g>
    <g class="node-group" onclick="showInfo('e','ds2')"><rect x="879" y="160" width="158" height="38" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="174" class="node-label" style="fill:#fdba74;font-size:10px;">DS2: Financial DB</text><text x="958" y="188" class="node-sublabel">Billing · Revenue Cycle</text></g>
    <g class="node-group" onclick="showInfo('e','ds3')"><rect x="879" y="265" width="158" height="38" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="279" class="node-label" style="fill:#fdba74;font-size:10px;">DS3: Clinical DB</text><text x="958" y="293" class="node-sublabel">EMR · Orders · Labs</text></g>
    <g class="node-group" onclick="showInfo('e','ds4')"><rect x="879" y="370" width="158" height="38" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="384" class="node-label" style="fill:#fdba74;font-size:10px;">DS4: Inventory DB</text><text x="958" y="398" class="node-sublabel">Drugs · Assets · Supply</text></g>
    <g class="node-group" onclick="showInfo('e','ds5')"><rect x="879" y="475" width="158" height="38" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="489" class="node-label" style="fill:#fdba74;font-size:10px;">DS5: HR & Staff DB</text><text x="958" y="503" class="node-sublabel">Workforce · Payroll</text></g>
    <g class="node-group" onclick="showInfo('e','ds6')"><rect x="879" y="580" width="158" height="38" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="594" class="node-label" style="fill:#fdba74;font-size:10px;">DS6: Reports Archive</text><text x="958" y="608" class="node-sublabel">BI · DOH · Audit</text></g>
    <g class="node-group" onclick="showInfo('e','ds7')"><rect x="879" y="685" width="158" height="38" rx="4" fill="#1a0e0e" stroke="#f97316" stroke-width="1.5"/><text x="958" y="699" class="node-label" style="fill:#fdba74;font-size:10px;">DS7: Claims DB</text><text x="958" y="713" class="node-sublabel">PhilHealth · HMO</text></g>
    <g class="node-group" onclick="showInfo('e','ds8')"><rect x="879" y="790" width="158" height="38" rx="4" fill="#280e28" stroke="#f472b6" stroke-width="1.5"/><text x="958" y="804" class="node-label" style="fill:#f9a8d4;font-size:10px;">DS8: AI/ML DataLake ✦</text><text x="958" y="818" class="node-sublabel">Predictive · Models</text></g>

    <!-- Arrows -->
    <line x1="173" y1="68" x2="234" y2="68" class="dfd-arrow" stroke="#c084fc" marker-end="url(#ae-p)"/>
    <line x1="173" y1="163" x2="234" y2="171" class="dfd-arrow" stroke="#c084fc" marker-end="url(#ae-p)"/>
    <line x1="173" y1="258" x2="234" y2="274" class="dfd-arrow" stroke="#c084fc" marker-end="url(#ae-p)"/>
    <line x1="173" y1="353" x2="234" y2="377" class="dfd-arrow" stroke="#c084fc" stroke-dasharray="4,2" marker-end="url(#ae-p)"/>
    <line x1="234" y1="392" x2="173" y2="370" class="dfd-arrow" stroke="#c084fc" stroke-dasharray="4,2" marker-end="url(#ae-p)"/>
    <line x1="173" y1="543" x2="234" y2="580" class="dfd-arrow" stroke="#c084fc" marker-end="url(#ae-p)"/>
    <line x1="173" y1="638" x2="234" y2="480" class="dfd-arrow" stroke="#c084fc" marker-end="url(#ae-p)"/>
    <line x1="173" y1="733" x2="234" y2="690" class="dfd-arrow" stroke="#f472b6" stroke-dasharray="3,3" marker-end="url(#ae-pk)"/>

    <line x1="384" y1="68" x2="447" y2="420" class="dfd-arrow" stroke="#c084fc" stroke-dasharray="4,2" marker-end="url(#ae-p)"/>
    <line x1="384" y1="171" x2="449" y2="428" class="dfd-arrow" stroke="#c084fc" stroke-dasharray="4,2" marker-end="url(#ae-p)"/>
    <line x1="384" y1="274" x2="449" y2="438" class="dfd-arrow" stroke="#c084fc" stroke-dasharray="4,2" marker-end="url(#ae-p)"/>
    <line x1="384" y1="377" x2="450" y2="445" class="dfd-arrow" stroke="#c084fc" stroke-dasharray="4,2" marker-end="url(#ae-p)"/>
    <line x1="384" y1="480" x2="451" y2="452" class="dfd-arrow" stroke="#c084fc" stroke-dasharray="4,2" marker-end="url(#ae-p)"/>
    <line x1="384" y1="583" x2="454" y2="462" class="dfd-arrow" stroke="#c084fc" stroke-dasharray="4,2" marker-end="url(#ae-p)"/>
    <line x1="384" y1="686" x2="452" y2="470" class="dfd-arrow" stroke="#f472b6" stroke-dasharray="3,3" marker-end="url(#ae-pk)"/>

    <line x1="617" y1="425" x2="670" y2="171" class="dfd-arrow" stroke="#c084fc" marker-end="url(#ae-p)"/>
    <line x1="617" y1="438" x2="670" y2="274" class="dfd-arrow" stroke="#f472b6" marker-end="url(#ae-pk)"/>
    <line x1="617" y1="450" x2="670" y2="377" class="dfd-arrow" stroke="#c084fc" marker-end="url(#ae-p)"/>
    <line x1="617" y1="462" x2="670" y2="480" class="dfd-arrow" stroke="#c084fc" marker-end="url(#ae-p)"/>
    <line x1="617" y1="474" x2="670" y2="583" class="dfd-arrow" stroke="#f472b6" marker-end="url(#ae-pk)"/>
    <line x1="617" y1="485" x2="670" y2="686" class="dfd-arrow" stroke="#f472b6" marker-end="url(#ae-pk)"/>

    <line x1="670" y1="171" x2="173" y2="438" class="dfd-arrow" stroke="#334155" stroke-dasharray="4,3" marker-end="url(#ae)"/>

    <line x1="610" y1="412" x2="877" y2="74" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ae-o)"/>
    <line x1="612" y1="428" x2="877" y2="179" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ae-o)"/>
    <line x1="612" y1="440" x2="877" y2="284" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ae-o)"/>
    <line x1="612" y1="452" x2="877" y2="389" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ae-o)"/>
    <line x1="612" y1="462" x2="877" y2="494" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ae-o)"/>
    <line x1="820" y1="171" x2="877" y2="599" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ae-o)"/>
    <line x1="820" y1="377" x2="877" y2="704" class="dfd-arrow" stroke="#f97316" stroke-dasharray="3,2" marker-end="url(#ae-o)"/>
    <line x1="820" y1="274" x2="877" y2="809" class="dfd-arrow" stroke="#f472b6" stroke-dasharray="3,2" marker-end="url(#ae-pk)"/>

    <text x="375" y="240" class="flow-label" fill="#c084fc">Module Data</text>
    <text x="640" y="330" class="flow-label" fill="#c084fc">Output</text>
    <text x="366" y="470" class="flow-label" fill="#334155">Reports</text>
    <text x="765" y="380" class="flow-label" fill="#f97316">DB R/W</text>
  </svg>
  </div>

  <div class="section-divider">Module Functions — Enterprise Tier <span style="color:#f472b6;font-size:9px;margin-left:8px;">✦ = New in Enterprise</span></div>
  <div class="module-grid" id="modules-ent"></div>
</div>

<!-- INFO PANEL -->
<div class="info-panel" id="infoPanel">
  <button class="ip-close" onclick="closeInfo()">✕</button>
  <span class="ip-tag" id="ipTag"></span>
  <div class="ip-title" id="ipTitle"></div>
  <div class="ip-desc" id="ipDesc"></div>
  <div id="ipFnWrap" style="display:none;">
    <div class="ip-fn-head">Functions</div>
    <ul class="ip-fn-list" id="ipFnList"></ul>
  </div>
</div>

<script>
// ====================== DATA ======================
const TIERS = {
  b: { // BASIC
    core: { tag:'Core Engine', color:'#60a5fa', title:'iHOMIS+ Core (Basic)', desc:'Essential integration layer for small facilities. Handles user auth, patient flow coordination, and basic data sync between 5 core modules.', fns:['User login & role-based access (admin, doctor, nurse)','Patient encounter session management','Basic data synchronization between modules','Offline-capable local database sync','System audit log (basic)','Backup and data export to CSV/Excel'] },
    patient: { tag:'External Entity', color:'#60a5fa', title:'Patient / Family', desc:'Initiates care journey at walk-in or emergency.', fns:['Submit personal & demographic information','Provide consent for treatment','Receive billing statement and official receipt','Get discharge summary and home instructions'] },
    doctor: { tag:'External Entity', color:'#60a5fa', title:'Medical Staff', desc:'Doctors and nurses inputting clinical data.', fns:['Enter diagnoses and prescriptions','Request lab tests and procedures','Update patient clinical notes','Issue discharge orders'] },
    admin: { tag:'External Entity', color:'#60a5fa', title:'Admin Staff', desc:'Administrative personnel managing billing and records.', fns:['Process patient billing and collections','Manage patient registration records','Handle cash and PhilHealth payments','Generate basic financial reports'] },
    philhealth: { tag:'External Entity', color:'#60a5fa', title:'PhilHealth', desc:'Insurance body for claims processing.', fns:['Verify member eligibility','Receive claim form submissions','Return claim approval/rejection status','Provide reimbursement amounts'] },
    doh: { tag:'External Entity', color:'#60a5fa', title:'DOH / Regulators', desc:'Receives mandated health reports.', fns:['Receive basic FHSIS reports','Provide compliance guidelines and ICD updates','Issue accreditation requirements'] },
    reg: { tag:'Module 1.0', color:'#60a5fa', title:'1.0 Registration & Admission', desc:'Core patient intake module for all encounter types.', fns:['Register new patients with unique Hospital Number (HN)','Capture demographics: name, age, address, contact, PhilHealth number','Process walk-in and emergency admissions','Verify PhilHealth membership at admission','Assign ward, room, and bed','Generate admission slip','Process patient discharge','Track basic bed census (occupied/vacant)'] },
    clinical: { tag:'Module 2.0', color:'#60a5fa', title:'2.0 Clinical Records & Basic EMR', desc:'Basic electronic medical records for patient clinical documentation.', fns:['Create and store patient Electronic Medical Records','Record physician orders and clinical notes','Encode diagnoses with ICD-10 codes','Log lab test requests and manual results entry','Record medications prescribed','Generate basic discharge summary','View patient visit history'] },
    billing: { tag:'Module 3.0', color:'#60a5fa', title:'3.0 Billing & Collection', desc:'Core billing and payment processing module.', fns:['Generate itemized patient billing statement','Process cash payments and issue official receipts','Apply PhilHealth deductions on bills','Apply senior citizen and PWD discounts','Record accounts receivable and outstanding balances','Generate daily collection summary report','Process billing adjustments and refunds'] },
    phproc: { tag:'Module 4.0', color:'#60a5fa', title:'4.0 PhilHealth eClaims Processing', desc:'PhilHealth eClaims processing and electronic submission module covering the full claims lifecycle from eligibility to reimbursement.', fns:[
      '— ELIGIBILITY VERIFICATION —',
      'Verify PhilHealth membership status at point of admission',
      'Check member contribution history and active status',
      'Identify applicable benefit package (case rate, Z-benefit, SARS, etc.)',
      'Confirm dependent relationship for non-member patients',
      '— CLAIM FORM GENERATION —',
      'Auto-generate Claim Form 1 (CF1) — Member Data Record',
      'Auto-generate Claim Form 2 (CF2) — Hospital Claim Form',
      'Encode principal and secondary diagnoses (ICD-10)',
      'Attach itemized list of services rendered',
      'Encode attending physician PRC and PhilHealth accreditation number',
      '— eCLAIMS SUBMISSION —',
      'Submit claims electronically via PhilHealth eClaims portal',
      'Generate electronic Transmittal Report (eTR) per batch',
      'Attach scanned supporting documents (LOA, OR, clinical notes)',
      'Receive submission acknowledgment and tracking number',
      '— CLAIM STATUS MONITORING —',
      'Track claim status: submitted → received → processed → approved/rejected',
      'View rejection reason codes (RRC) and denial notices',
      'Flag claims pending additional document requirements',
      '— REIMBURSEMENT & RECONCILIATION —',
      'Record reimbursement amount and check/bank credit details',
      'Reconcile PhilHealth payments against billed amount',
      'Post reimbursement to patient ledger and financial records',
      'Flag rejected claims for correction and resubmission',
    ] },
    reporting_basic: { tag:'Module 5.0', color:'#60a5fa', title:'5.0 Basic Reporting & DOH Compliance', desc:'Generates essential reports for operations and regulatory compliance.', fns:['Generate daily/monthly census report','Produce basic FHSIS reports for DOH submission','Generate PhilHealth utilization summary','Produce daily collection and billing report','Generate patient admission and discharge log','Export reports to PDF and Excel','Generate basic bed occupancy report'] },
    referral_basic: { tag:'Module 6.0', color:'#60a5fa', title:'6.0 Patient Transfer & Referral', desc:'Core module for managing outgoing and incoming patient transfers between facilities — essential for continuity of care at the district and RHU level.', fns:[
      '— OUTGOING REFERRAL (SENDING FACILITY) —',
      'Create outgoing referral request for patients needing higher-level care',
      'Select receiving facility from referral network directory',
      'Generate standard DOH Referral Form / Transfer Note',
      'Attach patient demographic data and Hospital Number to referral',
      'Include reason for referral and working/confirmed diagnosis (ICD-10)',
      'Attach brief clinical summary: chief complaint, history, medications given',
      'Include latest vital signs and clinical status at time of transfer',
      'Indicate urgency level: routine, urgent, or emergency transfer',
      'Attach copies of lab results, X-ray readings, or other diagnostics',
      'Record attending physician name, PRC number, and signature',
      'Log date and time referral was initiated',
      '— INCOMING REFERRAL (RECEIVING FACILITY) —',
      'Receive and log incoming referral requests from other facilities',
      'Accept or decline referral based on bed availability and capacity',
      'Notify sending facility of acceptance or suggested alternative facility',
      'Register transferred patient using referral data (pre-populated fields)',
      'Link transferred patient\'s referral record to new admission encounter',
      'Document clinical handover notes from accompanying referral letter',
      '— PATIENT TRANSPORT & HANDOVER —',
      'Record mode of transport: ambulance, private vehicle, helicopter',
      'Log departure time from sending facility and arrival time',
      'Record name and contact of accompanying health personnel',
      'Document patient condition during transport (stable/unstable)',
      'Capture handover acknowledgment at receiving facility',
      '— REFERRAL TRACKING & FOLLOW-UP —',
      'Track referral status: created → sent → accepted → patient arrived',
      'Record outcome of referred patient: admitted, treated and sent back, expired',
      'Generate back-referral or feedback report to sending facility',
      'Maintain log of all outgoing and incoming referrals per period',
      'Generate monthly referral volume report for DOH submission',
    ] },
    ds1: { tag:'Data Store', color:'#f97316', title:'DS1: Patient Records DB', desc:'Core patient identity and encounter database.', fns:['Store patient demographics and Hospital Number','Maintain admission and discharge history','Record PhilHealth membership details','Store allergy and blood type info','Index records for quick lookup'] },
    ds2: { tag:'Data Store', color:'#f97316', title:'DS2: Financial DB', desc:'Financial transactions and billing ledger.', fns:['Store all billing transactions and payments','Maintain charge master and fee schedules','Record accounts receivable data','Archive official receipts','Store PhilHealth coverage per encounter'] },
    ds3: { tag:'Data Store', color:'#f97316', title:'DS3: Clinical DB', desc:'Core clinical records data store.', fns:['Store ICD-10 diagnoses per encounter','Archive lab requests and results','Maintain medication records','Store clinical notes and orders','Archive discharge summaries'] },
    ds_referral: { tag:'Data Store', color:'#f97316', title:'DS4: Referral Log DB', desc:'Stores all outgoing and incoming patient referral and transfer records.', fns:['Store outgoing referral forms and transfer notes','Record incoming referral acceptance/rejection decisions','Archive attached clinical summaries and supporting documents','Log patient transport details and handover records','Track referral status history and timestamps','Store back-referral feedback and patient outcomes','Maintain referral network directory of facilities','Archive monthly referral volume data for DOH reporting'] },
    facilities_basic: { tag:'External Entity', color:'#60a5fa', title:'Other Facilities', desc:'Referring and receiving facilities in the basic referral network.', fns:['Send outgoing referral requests with Transfer Note','Receive and respond to incoming referral requests','Confirm patient acceptance and bed availability','Exchange patient clinical summary and supporting documents','Acknowledge patient arrival and handover completion','Send back-referral feedback to originating facility'] },
  },

  p: { // PROFESSIONAL — inherits Basic + adds new
    core: { tag:'Core Engine', color:'#34d399', title:'iHOMIS+ Core (Professional)', desc:'Full integration layer for medium-to-large hospitals. Adds advanced data routing, multi-department workflows, and automated triggers for inventory and scheduling.', fns:['Everything in Basic tier','Multi-department data routing and isolation','Automated reorder and alert triggers','Real-time inter-module event messaging','Advanced role-based access (department-level)','Scheduled automated report delivery','System health monitoring and alerts','API integration with PhilHealth eClaims portal'] },
    patient: { tag:'External Entity', color:'#34d399', title:'Patient / Family', desc:'Extended patient interaction including referrals and appointment booking.', fns:['Submit referral request and supporting documents','Book appointments online or via front desk','Receive SMS appointment reminders','Access itemized bill breakdown','Receive referral transfer status updates','Get electronic discharge summary'] },
    doctor: { tag:'External Entity', color:'#34d399', title:'Medical Staff', desc:'Full clinical workflow access including labs, imaging, and scheduling.', fns:['Access full EMR with lab and imaging results','Place electronic orders (labs, radiology, pharmacy)','Manage personal patient queue and OR schedule','Review and sign off discharge summaries digitally','Receive clinical alerts (drug interactions, critical labs)','Participate in inter-facility referral process'] },
    admin: { tag:'External Entity', color:'#34d399', title:'Admin Staff', desc:'Full admin access including HR, payroll, and financial management.', fns:['Manage full HR records and payroll','Process complex billing with HMO and private payers','Generate department-level financial reports','Handle procurement requests and supplier management','Manage user accounts and department permissions','Process staff leave and attendance records'] },
    philhealth: { tag:'External Entity', color:'#34d399', title:'PhilHealth', desc:'Full electronic claims integration.', fns:['Real-time eligibility verification via API','Receive all claim form types electronically','Process automated claim submission batches','Return itemized rejection codes','Provide case rate and benefit updates electronically'] },
    doh: { tag:'External Entity', color:'#34d399', title:'DOH / Regulators', desc:'Full compliance and surveillance reporting.', fns:['Receive complete FHSIS reports electronically','Access disease surveillance and morbidity data','Provide updated ICD and clinical standards','Issue licensing and accreditation requirements'] },
    facilities: { tag:'External Entity', color:'#34d399', title:'Other Health Facilities', desc:'Referral network partners for patient transfer.', fns:['Send and receive electronic referral requests','Exchange patient clinical summaries','Confirm acceptance of transferred patients','Share diagnostic results for care continuity','Track patient transfer status in real-time'] },
    supplier: { tag:'External Entity', color:'#34d399', title:'Pharmacy / Suppliers', desc:'Integrated supply chain partners.', fns:['Submit delivery receipts electronically','Receive automated purchase orders','Update drug catalog with pricing and availability','Provide expiry date and lot number data','Respond to emergency procurement requests'] },
    reg: { tag:'Module 1.0', color:'#34d399', title:'1.0 Registration & Admission', desc:'Full patient intake with multi-ward management.', fns:['All Basic tier functions','Process multi-ward concurrent admissions','Manage ICU, isolation, and special unit admissions','Auto-assign bed based on ward availability rules','Track patient location and transfer between wards','Generate wristband and patient ID barcode','Manage newborn registration and mother linkage','Process day surgery and day care admissions'] },
    clinical: { tag:'Module 2.0', color:'#34d399', title:'2.0 Clinical Records & EMR', desc:'Full EMR with electronic ordering and clinical decision support.', fns:['All Basic tier functions','Process electronic lab orders and retrieve results automatically','Electronic radiology orders linked to PACS viewer','Medication administration record (MAR) with dosing alerts','Clinical decision support: drug-drug and drug-allergy alerts','Nursing documentation and care plan management','Vital signs charting with trend visualization','Consent management with digital signature','Surgical and anesthesia records','Referral letter generation from EMR'] },
    billing: { tag:'Module 3.0', color:'#34d399', title:'3.0 Billing & Financial Management', desc:'Full revenue cycle management with multi-payer support.', fns:['All Basic tier functions','Process HMO and private insurance claims','Manage multiple payment modes (GCash, credit card)','Department-level revenue tracking and cost center reports','Budget allocation and expense monitoring','Accounts payable management','Financial audit trail with user-level tracking','Produce P&L statements and financial summaries','Manage charity and indigent patient accounts'] },
    phproc: { tag:'Module 4.0', color:'#34d399', title:'4.0 PhilHealth eClaims Processing', desc:'Automated full-cycle PhilHealth eClaims management with batch processing, real-time portal integration, and reconciliation — builds on Basic tier.', fns:[
      '— ELIGIBILITY VERIFICATION —',
      'Real-time eligibility verification via PhilHealth API at admission',
      'Check member contribution history and active coverage status',
      'Identify applicable benefit package: All Case Rates, Z-Benefit, SARS, MCP, GUNSHOT, etc.',
      'Auto-flag patients with lapsed or insufficient contributions',
      'Verify dependent relationship and qualified beneficiaries',
      'Capture and store PhilHealth Identification Number (PIN)',
      '— CLAIM FORM GENERATION —',
      'Auto-generate CF1 (Member Data Record) from patient master file',
      'Auto-generate CF2 (Hospital Claim Form) from billing and clinical data',
      'Auto-generate CF3 (Professional Fee Claim Form) for physician fees',
      'Auto-generate CF4 (All Case Rate Claim Form)',
      'Encode principal and all secondary diagnoses with ICD-10 codes',
      'Attach complete itemized charges per service category',
      'Encode all attending, operating, and anesthesiologist PhilHealth accreditation numbers',
      'Auto-apply correct case rate deductions per diagnosis',
      '— eCLAIMS PORTAL SUBMISSION —',
      'Batch electronic submission via PhilHealth eClaims portal (EPRS)',
      'Generate and print electronic Transmittal Report (eTR) per batch submission',
      'Attach required supporting documents: LOA, OR, consent forms, clinical abstract',
      'Receive digital acknowledgment receipt and claim tracking number',
      'Handle resubmission of returned or rejected claims',
      '— CLAIM STATUS MONITORING —',
      'Real-time claim status dashboard: submitted → received → processed → approved/rejected',
      'View and interpret Rejection Reason Codes (RRC) per claim line',
      'Receive and manage Request for Additional Documents (RAD) notices',
      'Track aging of pending claims by days outstanding',
      'Alert notification for claims approaching the 60-day filing deadline',
      '— REIMBURSEMENT & RECONCILIATION —',
      'Record reimbursement amount, check number, and bank credit details',
      'Reconcile PhilHealth payments vs. billed amounts per encounter',
      'Post reimbursement to patient financial ledger automatically',
      'Generate monthly PhilHealth accounts receivable aging report',
      'Produce PhilHealth utilization report by case rate and benefit package',
      '— COMPLIANCE & AUDIT —',
      'Maintain complete audit trail of all claim actions per user',
      'Generate PhilHealth compliance checklist report',
      'Track accreditation expiry dates for facility and physicians',
      'Store all eClaims portal transaction logs for COA and PHIC audit',
    ] },
    inventory: { tag:'Module 5.0 ✦ NEW', color:'#fbbf24', title:'5.0 Inventory & Supply Management', desc:'Full pharmaceutical and medical supply chain management — new in Professional.', fns:['Maintain real-time stock levels per item and location','Process stock receiving with delivery receipt matching','Issue supplies to departments with consumption tracking','Auto-trigger reorder at minimum stock level','Monitor drug expiry dates with advance alerts','Track lot numbers and batch recall management','Process purchase requests and approved purchase orders','Conduct periodic physical inventory count and reconciliation','Generate inventory valuation and consumption reports','Manage multiple storage locations (pharmacy, wards, CSSD)'] },
    hr: { tag:'Module 6.0 ✦ NEW', color:'#fbbf24', title:'6.0 HR & Payroll Management', desc:'Complete human resource and compensation management — new in Professional.', fns:['Maintain employee 201 masterfile records','Manage shift scheduling and duty roster building','Record daily time and attendance (DTR) with biometrics integration','Process leave applications and maintain leave balance ledger','Compute semi-monthly payroll with all deductions','Handle government remittances: SSS, PhilHealth, Pag-IBIG, BIR','Manage plantilla, contractual, and job order positions','Process performance evaluation documentation','Compute end-of-service, retirement, and separation pay','Generate payslips and payroll summary reports','Track post-training certifications and skills matrix'] },
    reporting: { tag:'Module 7.0 ✦ NEW', color:'#fbbf24', title:'7.0 Reporting & Analytics', desc:'Full analytics and DOH-mandated reporting engine — enhanced in Professional.', fns:['Generate all DOH-mandated FHSIS reports','Real-time hospital dashboard with KPIs','Report bed occupancy rate (BOR), ALOS, and census','Disease surveillance and morbidity/mortality reporting','Financial performance reports (revenue, expense, variance)','PhilHealth utilization and claims summary reports','Department-level workload and productivity reports','Audit trail and system activity logs','Export to PDF, Excel, and CSV','Schedule automated report delivery by email','Customizable report templates per department'] },
    referral: { tag:'Module 8.0 ✦ NEW', color:'#fbbf24', title:'8.0 Referral & Inter-Facility Transfer', desc:'Full electronic inter-facility patient referral and transfer management — builds on Basic tier with electronic transmission, status tracking, and outcome reporting.', fns:[
      '— OUTGOING REFERRAL (SENDING FACILITY) —',
      'Create electronic outgoing referral with pre-filled patient data from EMR',
      'Select receiving facility and department from network directory',
      'Auto-generate DOH-compliant Referral Form and Transfer Note',
      'Attach clinical summary auto-pulled from patient EMR (diagnoses, medications, vitals)',
      'Include ICD-10 coded diagnosis and reason for referral',
      'Attach lab results, imaging reports, and diagnostic files electronically',
      'Set referral urgency: routine, urgent, or emergency',
      'Record attending and referring physician details (PRC/PhilHealth accreditation)',
      'Notify receiving facility via system message or SMS alert',
      'Generate printable referral slip and patient transfer packet',
      '— INCOMING REFERRAL (RECEIVING FACILITY) —',
      'Receive and display incoming referral requests in real-time queue',
      'View full referral packet including clinical summary and attachments',
      'Accept or decline referral with reason documented',
      'Auto-notify sending facility of acceptance decision',
      'Pre-register transferred patient from referral data (reduces encoding time)',
      'Link referral record to new admission encounter in EMR',
      'Assign ward and bed for incoming transfer patient',
      'Document clinical handover and receiving physician acknowledgment',
      '— PATIENT TRANSPORT MANAGEMENT —',
      'Record mode of transport: ambulance, private vehicle, air transport',
      'Log estimated and actual departure and arrival times',
      'Record name, contact, and license of accompanying personnel',
      'Document patient condition at departure and arrival (GCS, vitals)',
      'Coordinate with PhilHealth for ambulance coverage (if applicable)',
      'Generate ambulance request and dispatch form',
      '— CLINICAL CONTINUITY —',
      'Transmit complete patient clinical record to receiving facility electronically',
      'Share lab and imaging results via secure electronic channel',
      'Include current medication list and active orders',
      'Send discharge summary or transfer summary to receiving physician',
      'Maintain continuity of PhilHealth claim across sending and receiving facility',
      '— REFERRAL TRACKING & STATUS —',
      'Real-time referral status board: initiated → sent → accepted → in-transit → received',
      'Automatic timestamp at each status transition',
      'Alert sending physician when patient arrives at receiving facility',
      'Track referral response time (benchmark: 30 mins for emergency)',
      '— BACK-REFERRAL & FOLLOW-UP —',
      'Generate back-referral (feedback) to sending facility after treatment',
      'Record patient outcome: stabilized and returned, admitted, expired',
      'Track follow-up appointments scheduled for referred patients',
      'Manage back-referral queue for returning patients',
      '— REPORTING & ANALYTICS —',
      'Monthly referral volume report by facility, diagnosis, and urgency',
      'Track referral acceptance and rejection rates per receiving facility',
      'Generate DOH-mandated referral outcome reports',
      'Identify top diagnoses requiring referral (for capacity planning)',
    ] },
    scheduling: { tag:'Module 9.0 ✦ NEW', color:'#fbbf24', title:'9.0 Scheduling & Appointments', desc:'Full appointment and resource scheduling system — new in Professional.', fns:['Book OPD appointments per doctor and specialty','Manage operating room (OR) schedule and blocking','View and manage doctor availability calendars','Send automated SMS/email appointment reminders','Process cancellations, rescheduling, and walk-in insertion','Manage diagnostic and lab procedure appointment slots','Link confirmed appointments to registration upon patient arrival','Track no-show rates and cancellation analytics','Generate daily patient queue rosters and schedule summaries','Manage waiting list and priority queuing'] },
    ds1: { tag:'Data Store', color:'#f97316', title:'DS1: Patient Records DB', desc:'Expanded patient master record.', fns:['All Basic tier functions','Store referral history and transfer records','Maintain HMO and private insurance details','Link newborn to mother record','Store patient photograph and biometrics'] },
    ds2: { tag:'Data Store', color:'#f97316', title:'DS2: Financial DB', desc:'Full financial ledger with cost center tracking.', fns:['All Basic tier functions','Cost center and department-level entries','Multi-payer accounts receivable','Budget vs. actual tracking','Accounts payable records'] },
    ds3: { tag:'Data Store', color:'#f97316', title:'DS3: Clinical DB', desc:'Full clinical data store with electronic ordering.', fns:['All Basic tier functions','Electronic order sets and templates','MAR and nursing documentation','Surgical and anesthesia records','Digital consent forms'] },
    ds4: { tag:'Data Store', color:'#f97316', title:'DS4: Inventory DB', desc:'Supply chain data store — new in Pro.', fns:['Current stock levels by location','Purchase orders and delivery records','Item master with lot numbers and expiry','Supplier master and pricing history','Stock movement audit trail'] },
    ds5: { tag:'Data Store', color:'#f97316', title:'DS5: HR & Staff DB', desc:'HR and workforce data store — new in Pro.', fns:['Employee 201 records and contracts','Shift schedules and duty rosters','Attendance and DTR records','Payroll computation history','Leave balances and usage records'] },
    ds6: { tag:'Data Store', color:'#f97316', title:'DS6: Reports Archive', desc:'Reports repository — new in Pro.', fns:['FHSIS and DOH submission archives','Financial statement archives','Audit trail logs','Analytics snapshots','Exported report file storage'] },
    ds_referral: { tag:'Data Store ✦ NEW', color:'#fbbf24', title:'DS7: Referral DB', desc:'Dedicated data store for all inter-facility referral and patient transfer records — new in Professional.', fns:['Store all outgoing and incoming referral forms','Record referral status history with full timestamps','Archive attached clinical summaries and diagnostic documents','Log patient transport details, personnel, and handover records','Store acceptance/rejection decisions and reasons','Archive back-referral feedback and patient outcome reports','Maintain referral network directory of facilities and contacts','Store monthly referral volume data for DOH compliance'] },
  },

  e: { // ENTERPRISE
    core: { tag:'Core Engine', color:'#c084fc', title:'iHOMIS+ Core (Enterprise)', desc:'AI-powered enterprise integration layer for hospital networks. Full event-driven architecture, multi-facility data federation, and predictive analytics engine.', fns:['Everything in Professional tier','Multi-facility data federation and network management','Event-driven microservices architecture','AI/ML model orchestration and inference engine','Real-time predictive alerts and decision support','Single Sign-On (SSO) across all facilities','Disaster recovery with geo-redundant failover','Open API marketplace for third-party integrations','HL7 FHIR compliance for health data interoperability','Compliance engine for DOH, JCI, and PhilHealth standards'] },
    patient: { tag:'External Entity', color:'#c084fc', title:'Patient / Family', desc:'Full digital patient engagement including portal and telemedicine.', fns:['Access personal health records via patient portal','Book and attend telemedicine video consultations','Receive AI-generated health summaries and alerts','Track lab/imaging results in real-time via portal','Manage family member health profiles','Receive smart appointment reminders with preparation instructions','Provide real-time patient satisfaction feedback'] },
    doctor: { tag:'External Entity', color:'#c084fc', title:'Medical Staff', desc:'AI-assisted clinical workflows with cross-facility access.', fns:['Access patient records across all network facilities','Receive AI clinical decision support and diagnosis suggestions','Place orders via voice or natural language input','Participate in virtual ward rounds and telemedicine','Access predictive deterioration and sepsis alerts','Review AI-generated clinical summaries on discharge','Mobile app access for ward and on-call rounds'] },
    admin: { tag:'External Entity', color:'#c084fc', title:'Admin / Management', desc:'Executive and operational management across the network.', fns:['Access enterprise-wide financial and operational dashboards','Manage multi-facility budgets and performance targets','Handle strategic procurement and vendor management','Review AI-generated cost-saving recommendations','Oversee network-wide HR and workforce planning','Access accreditation management and compliance dashboard'] },
    philhealth: { tag:'External Entity', color:'#c084fc', title:'PhilHealth / HMO', desc:'Full insurance network integration with multiple payers.', fns:['Real-time eligibility for PhilHealth and all HMO partners','Auto-batch multi-facility claim submissions','Receive AI-assisted claim pre-validation (reduce rejections)','Monitor cross-facility PhilHealth utilization analytics','Access accreditation compliance dashboard'] },
    doh: { tag:'External Entity', color:'#c084fc', title:'DOH / Regulators', desc:'Real-time compliance and disease surveillance reporting.', fns:['Real-time notifiable disease reporting to DOH surveillance','Submit all FHSIS reports automatically on schedule','Access hospital performance benchmarking data','Receive AI-generated compliance gap alerts','Participate in national health data exchange (NHDR)'] },
    facilities: { tag:'External Entity', color:'#c084fc', title:'Network Facilities', desc:'Branch hospitals and satellite clinics in the enterprise network.', fns:['Participate in unified patient record sharing network','Send and receive referrals within the hospital network','Share real-time bed availability across facilities','Access centralized lab and imaging results','Participate in network-wide procurement and inventory pooling'] },
    supplier: { tag:'External Entity', color:'#c084fc', title:'Pharmacy / Suppliers', desc:'Enterprise supply chain with ERP integration.', fns:['Integration with supplier ERP for automated PO processing','Real-time price and availability catalog sync','Batch delivery receipt processing','Participate in network-wide consolidated purchasing','Comply with national drug formulary and BFAD tracking'] },
    external_sys: { tag:'External Entity ✦ NEW', color:'#f472b6', title:'External Systems (Enterprise)', desc:'Third-party clinical and enterprise systems integrated via the hub.', fns:['LIS (Laboratory Information System) bidirectional integration','PACS/RIS (Radiology/Imaging) result retrieval','External ERP system (SAP/Oracle) financial sync','National Health Data Repository (NHDR) connectivity','eHealth PH interoperability compliance (HL7 FHIR)','Biometrics system integration (time and attendance)','SMS gateway and email notification services'] },
    reg: { tag:'Module 1.0', color:'#c084fc', title:'1.0 Registration & Admission', desc:'Enterprise-grade patient intake with smart queuing and cross-facility lookup.', fns:['All Professional tier functions','Cross-facility patient identity deduplication','AI-powered triage scoring at emergency intake','Smart bed assignment based on predictive occupancy','Biometric patient verification at admission','Patient portal self-check-in kiosk integration','Newborn registration with automated PhilHealth linkage','Multi-facility census and bed management dashboard'] },
    clinical: { tag:'Module 2.0', color:'#c084fc', title:'2.0 Clinical Records & Advanced EMR', desc:'AI-enhanced EMR with full diagnostic integration and clinical intelligence.', fns:['All Professional tier functions','AI-assisted diagnosis suggestion from clinical notes','Natural language processing (NLP) for clinical documentation','Full PACS viewer integration for radiology images','LIS bidirectional integration for auto-result posting','Predictive sepsis and deterioration early warning alerts','Advanced medication management: reconciliation and CPOE','Clinical quality measures tracking (JCI, DOH standards)','Genomics and precision medicine data support','Population health analytics from patient data'] },
    billing: { tag:'Module 3.0', color:'#c084fc', title:'3.0 Billing & Revenue Cycle Management', desc:'Full enterprise revenue cycle with AI-powered optimization.', fns:['All Professional tier functions','AI-powered charge capture optimization (reduce revenue leakage)','Automated insurance eligibility check at every touch point','Multi-facility consolidated billing and reporting','Revenue cycle analytics: denial management, A/R aging','Value-based payment and bundled payment support','Real-time revenue dashboard across all facilities','Patient financial counseling tool and payment plans','Automated government reporting: BIR, COA compliance'] },
    phproc: { tag:'Module 4.0', color:'#c084fc', title:'4.0 PhilHealth / HMO eClaims Processing', desc:'AI-assisted multi-payer eClaims management for enterprise hospital networks — includes full PhilHealth eClaims, HMO LOA workflows, and cross-facility claims consolidation.', fns:[
      '— ELIGIBILITY VERIFICATION —',
      'Real-time PhilHealth eligibility API check at every patient touchpoint',
      'HMO and private insurer eligibility verification (multi-payer)',
      'Auto-identify benefit packages: All Case Rates, Z-Benefit, SARS, MCP, TB-DOTS, Animal Bite, Malaria, etc.',
      'AI-powered contribution gap detection and member alert',
      'Verify dependent status and qualified beneficiaries automatically',
      'Cross-facility patient PhilHealth status lookup via Master Patient Index',
      '— CLAIM FORM GENERATION —',
      'Auto-generate all CF types (CF1, CF2, CF3, CF4) from clinical and billing data',
      'AI-assisted ICD-10 coding validation before claim generation',
      'Auto-apply correct case rate and co-pay computation per diagnosis',
      'Encode all physician PhilHealth accreditation numbers from staff registry',
      'Generate HMO claim forms and Letter of Authority (LOA) requests',
      'Attach complete itemized charges, OR records, and clinical abstracts automatically',
      '— eCLAIMS PORTAL SUBMISSION —',
      'Automated batch submission to PhilHealth EPRS eClaims portal',
      'AI pre-validation engine: predicts and flags claims likely to be rejected before submission',
      'Generate electronic Transmittal Report (eTR) with digital signature',
      'Submit HMO claims via payer-specific portals or EDI connectors',
      'Multi-facility batch submission with centralized submission dashboard',
      'Automated filing deadline tracker with escalation alerts (60-day rule)',
      '— CLAIM STATUS MONITORING —',
      'Unified multi-payer claim status dashboard (PhilHealth + all HMOs)',
      'Real-time status updates: submitted → received → processed → approved/rejected',
      'AI-driven Rejection Reason Code (RRC) analysis with correction recommendations',
      'Auto-resubmit corrected claims with AI-filled missing data',
      'Manage Request for Additional Documents (RAD) queue with auto-routing',
      'Escalation alerts for high-value claims and long-pending items',
      '— REIMBURSEMENT & RECONCILIATION —',
      'Auto-post reimbursements to patient ledger upon PhilHealth credit confirmation',
      'Multi-payer payment reconciliation against billed amounts',
      'Real-time reimbursement forecasting dashboard',
      'A/R aging report by payer, facility, and case rate category',
      'Revenue leakage detection: identify under-claimed or missed case rates',
      '— COMPLIANCE, AUDIT & ACCREDITATION —',
      'Full audit trail of all claim lifecycle events with user accountability',
      'PhilHealth accreditation renewal tracker for facility and all physicians',
      'AI-generated compliance gap report vs. PHIC circular requirements',
      'JCI and DOH billing standards compliance monitoring',
      'Store all eClaims portal logs, eTRs, and PhilHealth notices for COA audit',
      'Cross-facility consolidated PhilHealth performance benchmarking report',
    ] },
    inventory: { tag:'Module 5.0', color:'#c084fc', title:'5.0 Inventory & Supply Chain', desc:'Enterprise supply chain with predictive restocking and network pooling.', fns:['All Professional tier functions','AI demand forecasting for drug and supply procurement','Network-wide inventory pooling across facilities','Automated procurement with ERP supplier integration','Real-time BFAD and drug safety recall alerts','Asset management for medical equipment (CMMS integration)','End-to-end cold chain monitoring for vaccines and biologics','Total landed cost analysis per item'] },
    hr: { tag:'Module 6.0', color:'#c084fc', title:'6.0 HR, Payroll & Workforce Management', desc:'Enterprise workforce intelligence with predictive staffing.', fns:['All Professional tier functions','AI-powered nurse-to-patient ratio optimization','Predictive staffing based on admission forecast','Multi-facility unified HR management','Training and competency tracking with certification alerts','Succession planning and talent management module','Employee self-service portal (leave, payslip, schedule)','Labor cost analytics and workforce productivity dashboard'] },
    reporting: { tag:'Module 7.0', color:'#c084fc', title:'7.0 Reporting & BI Analytics', desc:'Enterprise business intelligence with AI-powered insights.', fns:['All Professional tier functions','Interactive BI dashboards with drill-down capability','Predictive hospital performance modeling','Natural language query for non-technical users (ask a question, get a chart)','Benchmarking against national and regional hospital data','Real-time DOH surveillance integration and auto-reporting','Revenue cycle and financial forecasting reports','Custom KPI builder for executive scorecards','Automated board-level report generation'] },
    referral: { tag:'Module 8.0', color:'#c084fc', title:'8.0 Referral & Network Transfer', desc:'AI-powered, network-wide patient referral and transfer management across the enterprise hospital system — includes everything in Professional plus intelligent routing, ambulance coordination, and cross-network analytics.', fns:[
      '— INTELLIGENT REFERRAL ROUTING —',
      'AI-powered referral routing: recommend best-fit receiving facility based on diagnosis, bed availability, and specialty',
      'Real-time bed availability feed from all network facilities',
      'Auto-match patient needs to receiving facility capability (ICU, OR, specialty units)',
      'AI-generated referral priority scoring (clinical urgency + logistics)',
      'Suggest nearest facility with required specialist and available bed',
      'Cross-network referral load balancing to prevent facility overload',
      '— OUTGOING REFERRAL (SENDING FACILITY) —',
      'All Professional tier outgoing referral functions',
      'One-click referral initiation with full EMR data auto-attached',
      'Voice-activated referral creation for emergency scenarios',
      'Simultaneous referral broadcast to multiple receiving facilities',
      'AI-drafted clinical summary for referral letter from EMR notes',
      '— INCOMING REFERRAL (RECEIVING FACILITY) —',
      'All Professional tier incoming referral functions',
      'Predictive bed preparation: alert ward staff before patient arrives',
      'Auto-assign care team (physician, nurse) based on referral diagnosis',
      'Pre-order labs and imaging based on incoming diagnosis (AI-suggested)',
      'Auto-generate pre-admission orders from referral clinical summary',
      '— PATIENT TRANSPORT & COORDINATION —',
      'All Professional tier transport functions',
      'Integrated ambulance dispatch and GPS tracking system',
      'Real-time patient vitals monitoring during transport (IoT wearables)',
      'Automated ETA notification to receiving facility every 10 minutes',
      'Multi-agency coordination: ambulance, police escort, helicopter',
      'Critical care transport checklist and protocol enforcement',
      '— CLINICAL CONTINUITY & RECORD SHARING —',
      'Instant cross-facility patient record access via Master Patient Index (MPI)',
      'HL7 FHIR-compliant patient summary transmission to any connected facility',
      'Share PACS images and LIS results in real-time across facilities',
      'Maintain single longitudinal patient record across the network',
      'Automated continuity of PhilHealth claim between facilities',
      'Cross-facility medication reconciliation on transfer',
      '— REFERRAL TRACKING & STATUS —',
      'All Professional tier tracking functions',
      'Live referral status map view across the entire network',
      'Mobile app push notifications for referring and receiving physicians',
      'SLA monitoring: flag referrals exceeding response time benchmarks',
      'Critical alert escalation if patient deteriorates during transfer',
      '— BACK-REFERRAL & FOLLOW-UP —',
      'All Professional tier back-referral functions',
      'Automated follow-up reminder scheduling for referred patients',
      'Telemedicine follow-up option for stable back-referred patients',
      'Outcome data feedback loop to improve future referral routing (AI learning)',
      '— NETWORK-WIDE REPORTING & ANALYTICS —',
      'Real-time network referral dashboard across all facilities',
      'Referral heatmap: identify geographic gaps in service coverage',
      'Top diagnoses driving referrals for DOH capacity planning reports',
      'AI-predicted referral surge alerts (e.g., during disease outbreaks)',
      'Benchmark referral acceptance rate and average response time per facility',
      'Cross-facility transfer cost and transport analytics',
      'Generate integrated DOH referral network performance report',
    ] },
    scheduling: { tag:'Module 9.0', color:'#c084fc', title:'9.0 Scheduling & Resource Planning', desc:'AI-enhanced enterprise resource and capacity planning.', fns:['All Professional tier functions','AI-optimized OR and procedure room scheduling','Predictive demand-based appointment slot generation','Multi-facility doctor schedule management','Patient waitlist analytics with estimated wait times','Integrate with patient portal for self-scheduling','Resource utilization optimization (rooms, equipment, staff)'] },
    telemedicine: { tag:'Module 10.0 ✦ NEW', color:'#f472b6', title:'10.0 Telemedicine & Patient Portal', desc:'Digital care delivery platform for remote consultations and patient self-service — new in Enterprise.', fns:['Video consultation platform (secure, HIPAA-aligned)','Online prescription and e-Rx generation','Remote monitoring integration (wearables, home devices)','Patient health record portal with self-service access','Online lab result delivery with AI explanation','Chat-based triage and symptom checker (AI-powered)','Teleconsultation scheduling and billing integration','Patient-reported outcomes (PRO) data collection','Digital consent and e-signature for remote patients'] },
    ai_analytics: { tag:'Module 11.0 ✦ NEW', color:'#f472b6', title:'11.0 AI Analytics & Decision Support', desc:'AI/ML-powered clinical and operational intelligence — new in Enterprise.', fns:['Predictive readmission risk scoring per patient','Sepsis and clinical deterioration early warning system','AI-assisted ICD coding from clinical notes','Drug-drug and drug-allergy interaction engine','Length of stay (LOS) prediction model','Disease outbreak detection and pattern recognition','Financial anomaly detection (billing fraud alerts)','AI-generated clinical summaries on patient discharge','Predictive maintenance alerts for medical equipment','Population health risk stratification and profiling'] },
    network: { tag:'Module 12.0 ✦ NEW', color:'#f472b6', title:'12.0 Multi-Facility Network Management', desc:'Centralized governance and operations across the hospital network — new in Enterprise.', fns:['Centralized dashboard for all network facilities','Unified patient identity management (Master Patient Index)','Cross-facility bed availability and patient transfer coordination','Network-wide quality metrics and benchmarking','Centralized pharmacy and supply pooling management','Consolidated financial reporting across all facilities','Network-wide policy and configuration management','Multi-facility disaster response coordination','Shared service center (lab, radiology, pharmacy) management'] },
    integration: { tag:'Module 13.0 ✦ NEW', color:'#f472b6', title:'13.0 System Integration Hub', desc:'Enterprise integration layer connecting all internal and external systems — new in Enterprise.', fns:['HL7 FHIR-compliant health data exchange API','LIS/PACS bidirectional integration middleware','ERP (SAP/Oracle) financial data sync connector','PhilHealth eClaims and NHDR API gateway','SMS, email, and push notification routing service','Biometrics and IoT device integration framework','External analytics and BI tool connectors (PowerBI, Tableau)','API versioning, rate limiting, and security management','Integration monitoring dashboard and alert system','Third-party app marketplace and plugin management'] },
    ds1: { tag:'Data Store', color:'#f97316', title:'DS1: Patient Records DB (Enterprise)', desc:'Network-wide master patient index.', fns:['All Professional tier functions','Master Patient Index (MPI) across all facilities','Biometric identity verification records','Patient portal access credentials','Longitudinal health timeline across facilities'] },
    ds2: { tag:'Data Store', color:'#f97316', title:'DS2: Financial DB (Enterprise)', desc:'Enterprise revenue cycle data store.', fns:['All Professional tier functions','Multi-facility consolidated ledger','Revenue cycle analytics data','Cost center hierarchy','Regulatory financial reporting data (COA, BIR)'] },
    ds3: { tag:'Data Store', color:'#f97316', title:'DS3: Clinical DB (Enterprise)', desc:'Full EMR with imaging and external data.', fns:['All Professional tier functions','PACS/LIS external result archive','Genomic and precision medicine data','Quality measures and clinical indicator data','Telemedicine consultation records'] },
    ds4: { tag:'Data Store', color:'#f97316', title:'DS4: Inventory DB (Enterprise)', desc:'Enterprise-wide supply chain data store.', fns:['All Professional tier functions','Network-wide pooled inventory ledger','Asset register for medical equipment','BFAD and drug safety records','Cold chain monitoring logs'] },
    ds5: { tag:'Data Store', color:'#f97316', title:'DS5: HR & Staff DB (Enterprise)', desc:'Enterprise workforce data store.', fns:['All Professional tier functions','Multi-facility staff records','Training and certification records','Succession planning data','Workforce analytics dataset'] },
    ds6: { tag:'Data Store', color:'#f97316', title:'DS6: Reports Archive (Enterprise)', desc:'Enterprise reporting and compliance archive.', fns:['All Professional tier functions','Board and executive report archives','Benchmarking and performance data','Accreditation documentation','DOH surveillance submission logs'] },
    ds7: { tag:'Data Store', color:'#f97316', title:'DS7: Claims DB (Enterprise)', desc:'Multi-payer claims data store.', fns:['All Professional tier functions','HMO and private insurer claim records','AI pre-validation results archive','Cross-facility claims consolidation data','Reimbursement forecasting dataset'] },
    ds8: { tag:'Data Store ✦ NEW', color:'#f472b6', title:'DS8: AI/ML Data Lake (Enterprise)', desc:'Centralized machine learning data store for predictive analytics — new in Enterprise.', fns:['Store de-identified clinical data for model training','Maintain predictive model versions and performance metrics','Archive AI decision logs and audit trails','Store patient risk scores and predictions','Maintain population health cohort datasets','Support FHIR-formatted health data export for national integration'] },
  }
};

const MODULE_META = {
  // icons and labels for module cards
  core:'🔮', patient:'👤', doctor:'🩺', admin:'🏢', philhealth:'🏥', doh:'📋', facilities:'🏨', supplier:'💊', external_sys:'🔗',
  reg:'📝', clinical:'🫀', billing:'💳', phproc:'📋', inventory:'📦', hr:'👥', reporting_basic:'📊', reporting:'📊',
  referral:'🚑', referral_basic:'🚑', scheduling:'🗓️', telemedicine:'📱', ai_analytics:'🤖', network:'🌐', integration:'⚡',
  ds1:'🗄️', ds2:'🗄️', ds3:'🗄️', ds4:'🗄️', ds5:'🗄️', ds6:'🗄️', ds7:'🗄️', ds8:'🗄️'
};

function buildModuleCards(tierId, containerId) {
  const container = document.getElementById('modules-' + (containerId || tierId));
  const data = TIERS[tierId];
  const accent = tierId==='b'?'#60a5fa':tierId==='p'?'#34d399':'#c084fc';
  const newColor = tierId==='p'?'#fbbf24':'#f472b6';

  const order = tierId==='b'
    ? ['reg','clinical','billing','phproc','reporting_basic','referral_basic']
    : tierId==='p'
    ? ['reg','clinical','billing','phproc','inventory','hr','reporting','referral','scheduling']
    : ['reg','clinical','billing','phproc','inventory','hr','reporting','referral','scheduling','telemedicine','ai_analytics','network','integration'];

  order.forEach(id => {
    const d = data[id]; if(!d) return;
    const isNew = d.tag && d.tag.includes('NEW');
    const cardAccent = isNew ? newColor : accent;
    const card = document.createElement('div');
    card.className = 'module-card';
    card.style.setProperty('--tier-accent', cardAccent);
    card.style.setProperty('--tier-accent-bg', cardAccent+'14');
    card.style.setProperty('--tier-accent-border', cardAccent+'44');
    card.style.borderTopColor = cardAccent;
    card.innerHTML = `
      <div class="mc-head">
        <div class="mc-icon" style="background:${cardAccent}18;">${MODULE_META[id]||'⚙️'}</div>
        <div>
          <div class="mc-title" style="color:${isNew?newColor:'#fff'};">${d.title}</div>
          <div class="mc-sub">${d.desc}</div>
        </div>
      </div>
      <ul class="func-list">
      ${d.fns.map((f,i)=>{
        if(f.startsWith('—')) return `<li class="func-item is-section" style="--tier-accent:${cardAccent};"><span class="fi-section">${f.replace(/—/g,'').trim()}</span></li>`;
        return `<li class="func-item" style="border-left-color:${cardAccent}55;"><span class="fi-num">${String(i+1).padStart(2,'0')}</span><span>${f}</span></li>`;
      }).join('')}
      </ul>`;
    container.appendChild(card);
  });
}

function showInfo(tierId, nodeId) {
  const d = (TIERS[tierId]||{})[nodeId];
  if(!d) return;
  document.getElementById('ipTag').textContent = d.tag;
  document.getElementById('ipTag').style.cssText = `background:${d.color}18;color:${d.color};border:1px solid ${d.color}44;`;
  document.getElementById('ipTitle').textContent = d.title;
  document.getElementById('ipDesc').textContent = d.desc;
  const wrap = document.getElementById('ipFnWrap');
  const list = document.getElementById('ipFnList');
  list.innerHTML = '';
  if(d.fns && d.fns.length) {
    wrap.style.display='block';
    d.fns.forEach((f,i)=>{
      const li = document.createElement('li');
      if(f.startsWith('—')) {
        li.className = 'ip-fn-item';
        li.style.cssText = 'background:transparent;border-left:none;padding:6px 0 2px;margin-top:4px;border-top:1px solid #1c2740;';
        li.innerHTML = `<span style="font-family:'Space Mono',monospace;font-size:9px;letter-spacing:2px;color:${d.color};text-transform:uppercase;">${f.replace(/—/g,'').trim()}</span>`;
      } else {
        li.className = 'ip-fn-item';
        li.style.borderLeftColor = d.color+'55';
        li.innerHTML = `<span class="ip-fn-num">${String(i+1).padStart(2,'0')}</span><span>${f}</span>`;
      }
      list.appendChild(li);
    });
  } else { wrap.style.display='none'; }
  document.getElementById('infoPanel').classList.add('active');
}
</script>
<!-- ============================================================ PAGE 4: CLIENT FORMS ============================================================ -->
<div class="page" id="page-forms" style="--tier-accent:#84cc16;--tier-accent-bg:rgba(132,204,22,0.08);--tier-accent-border:rgba(132,204,22,0.25);--hero-bg:#080f04;--hero-glow:rgba(132,204,22,0.07);">
  <div class="tier-hero">
    <div class="tier-eyebrow">Requirements Gathering · Hospital Client Onboarding</div>
    <h1>📋 Client Information Forms</h1>
    <div class="sub">All forms and information we need to collect from the hospital client before system setup, configuration, and deployment. Fill these out during the discovery and onboarding phase.</div>
    <div class="tier-pills">
      <span class="tier-pill">8 Form Categories</span>
      <span class="tier-pill">Hospital Profile</span>
      <span class="tier-pill">Operations Setup</span>
      <span class="tier-pill">PhilHealth eClaims</span>
      <span class="tier-pill">IT Infrastructure</span>
      <span class="tier-pill">Referral Network</span>
    </div>
  </div>

  <div style="padding:20px 40px 0;display:flex;gap:10px;flex-wrap:wrap;border-bottom:1px solid var(--border);background:var(--surface2);">
    <button class="form-cat-btn active" onclick="showFormCat('all')" id="fcat-all">All Forms</button>
    <button class="form-cat-btn" onclick="showFormCat('profile')" id="fcat-profile">🏥 Hospital Profile</button>
    <button class="form-cat-btn" onclick="showFormCat('operations')" id="fcat-operations">⚙️ Operations</button>
    <button class="form-cat-btn" onclick="showFormCat('clinical')" id="fcat-clinical">🩺 Clinical</button>
    <button class="form-cat-btn" onclick="showFormCat('philhealth')" id="fcat-philhealth">📋 PhilHealth</button>
    <button class="form-cat-btn" onclick="showFormCat('hr')" id="fcat-hr">👥 HR & Staff</button>
    <button class="form-cat-btn" onclick="showFormCat('it')" id="fcat-it">💻 IT & Infrastructure</button>
    <button class="form-cat-btn" onclick="showFormCat('referral')" id="fcat-referral">🚑 Referral Network</button>
    <button class="form-cat-btn" onclick="showFormCat('finance')" id="fcat-finance">💳 Finance & Billing</button>
  </div>

  <div id="forms-container" style="padding:28px 40px 60px;display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:20px;"></div>
</div>

<style>
.form-cat-btn{
  padding:10px 18px;font-size:12px;font-weight:500;
  border:1px solid var(--border);border-radius:6px 6px 0 0;
  background:transparent;color:var(--muted2);
  cursor:pointer;font-family:'DM Sans',sans-serif;
  transition:all .15s;margin-bottom:-1px;
}
.form-cat-btn:hover{color:var(--text);background:var(--surface);}
.form-cat-btn.active{background:var(--bg);color:#84cc16;border-color:#84cc16;border-bottom-color:var(--bg);}

.form-card{
  background:var(--surface2);border:1px solid var(--border);
  border-radius:10px;border-top:2px solid #84cc16;
  overflow:hidden;transition:transform .15s,box-shadow .15s;
}
.form-card:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,0,0,.3);}
.form-card-head{
  padding:18px 20px 14px;
  border-bottom:1px solid var(--border);
  display:flex;gap:12px;align-items:flex-start;
}
.form-card-icon{
  width:38px;height:38px;border-radius:8px;
  background:rgba(132,204,22,0.1);
  display:flex;align-items:center;justify-content:center;
  font-size:18px;flex-shrink:0;
}
.form-card-title{font-size:13px;font-weight:600;color:#fff;margin-bottom:3px;}
.form-card-sub{font-size:11px;color:var(--muted2);line-height:1.4;}
.form-card-body{padding:14px 20px 18px;}
.form-section-label{
  font-family:'Space Mono',monospace;font-size:9px;
  letter-spacing:2px;color:#84cc16;text-transform:uppercase;
  margin:12px 0 8px;
}
.form-section-label:first-child{margin-top:0;}
.form-field{
  display:flex;flex-direction:column;gap:4px;margin-bottom:10px;
}
.form-field label{
  font-size:11.5px;color:#94a3b8;font-weight:500;
  display:flex;align-items:center;gap:6px;
}
.form-field label .req{color:#f87171;font-size:10px;}
.form-field label .opt{color:var(--muted);font-size:10px;}
.form-input{
  background:#0a0e18;border:1px solid var(--border);
  border-radius:6px;padding:8px 12px;
  color:var(--text);font-size:12px;
  font-family:'DM Sans',sans-serif;
  outline:none;transition:border-color .15s;width:100%;
}
.form-input:focus{border-color:#84cc16;}
.form-input::placeholder{color:var(--muted);}
select.form-input{cursor:pointer;}
.form-input-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.form-checkbox-group{display:flex;flex-direction:column;gap:6px;}
.form-checkbox{
  display:flex;align-items:center;gap:8px;
  font-size:12px;color:#94a3b8;cursor:pointer;
}
.form-checkbox input{
  width:14px;height:14px;accent-color:#84cc16;cursor:pointer;
}
.form-radio-group{display:flex;flex-wrap:wrap;gap:8px;}
.form-radio{
  display:flex;align-items:center;gap:6px;
  font-size:12px;color:#94a3b8;cursor:pointer;
  background:#0a0e18;border:1px solid var(--border);
  padding:5px 12px;border-radius:20px;
  transition:all .15s;
}
.form-radio:hover{border-color:#84cc16;color:#84cc16;}
.form-radio input{accent-color:#84cc16;}
.form-note{
  font-size:11px;color:var(--muted);
  background:rgba(132,204,22,0.05);
  border:1px solid rgba(132,204,22,0.15);
  border-radius:6px;padding:8px 12px;
  margin-top:4px;line-height:1.5;
}
.form-divider{height:1px;background:var(--border);margin:14px 0;}
.form-card-footer{
  padding:12px 20px;background:rgba(0,0,0,.2);
  border-top:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;
}
.form-priority{
  font-family:'Space Mono',monospace;font-size:9px;
  padding:3px 8px;border-radius:20px;
}
.form-priority.required{background:rgba(248,113,113,0.15);color:#f87171;border:1px solid rgba(248,113,113,0.3);}
.form-priority.important{background:rgba(251,191,36,0.12);color:#fbbf24;border:1px solid rgba(251,191,36,0.25);}
.form-priority.optional{background:rgba(100,116,139,0.15);color:var(--muted2);border:1px solid var(--border);}
.form-save-btn{
  padding:6px 16px;border-radius:6px;font-size:11px;font-weight:600;
  background:rgba(132,204,22,0.15);color:#84cc16;
  border:1px solid rgba(132,204,22,0.3);cursor:pointer;
  font-family:'DM Sans',sans-serif;transition:all .15s;
}
.form-save-btn:hover{background:rgba(132,204,22,0.25);}
</style>
<script>
// FORMS + SHARED JS
const FORMS = [
  {
    id:'hospital-profile', cat:'profile', icon:'🏥',
    title:'Hospital Profile & Identity',
    sub:'Basic hospital information for system registration and DOH licensing',
    priority:'required',
    sections:[
      { label:'Legal & Registration Details', fields:[
        { label:'Complete Hospital Name', req:true, type:'text', ph:'e.g. Olongapo City General Hospital' },
        { label:'Hospital License Number (DOH)', req:true, type:'text', ph:'DOH License No.' },
        { label:'PhilHealth Accreditation Number', req:true, type:'text', ph:'PhilHealth Accreditation No.' },
        { label:'Tax Identification Number (TIN)', req:true, type:'text', ph:'TIN' },
        { label:'Year Established', req:false, type:'text', ph:'e.g. 1985' },
      ]},
      { label:'Classification & Category', fields:[
        { label:'Hospital Classification', req:true, type:'select', options:['Primary Care Hospital','Secondary Care Hospital','Tertiary Care Hospital','Special Hospital','Teaching & Training Hospital','Infirmary'] },
        { label:'Ownership Type', req:true, type:'select', options:['Government - DOH Retained','Government - LGU Owned','Private - Non-Profit','Private - For Profit','Church/Mission Hospital'] },
        { label:'Total Licensed Bed Capacity', req:true, type:'text', ph:'e.g. 150 beds' },
        { label:'Current Operational Beds', req:true, type:'text', ph:'e.g. 120 beds' },
      ]},
      { label:'Address & Location', fields:[
        { label:'Complete Physical Address', req:true, type:'textarea', ph:'Street, Barangay, City/Municipality, Province' },
        { label:'Region', req:true, type:'select', options:['NCR','Region I','Region II','Region III','Region IV-A','Region IV-B','Region V','Region VI','Region VII','Region VIII','Region IX','Region X','Region XI','Region XII','Region XIII','CAR','BARMM'] },
        { label:'Zip Code', req:false, type:'text', ph:'Zip Code' },
        { label:'GPS Coordinates (optional)', req:false, type:'text', ph:'Latitude, Longitude' },
      ]},
      { label:'Contact Information', fields:[
        { label:'Main Telephone Number', req:true, type:'text', ph:'(047) XXX-XXXX' },
        { label:'Emergency/OPD Number', req:false, type:'text', ph:'(047) XXX-XXXX' },
        { label:'Official Email Address', req:true, type:'text', ph:'info@hospital.gov.ph' },
        { label:'Hospital Website (if any)', req:false, type:'text', ph:'https://...' },
        { label:'Hospital Chief / Medical Director', req:true, type:'text', ph:'Full Name, MD' },
        { label:'Hospital Administrator', req:true, type:'text', ph:'Full Name' },
      ]},
    ]
  },
  {
    id:'departments', cat:'operations', icon:'🏢',
    title:'Departments & Service Units',
    sub:'List of all hospital departments, units, and ancillary services offered',
    priority:'required',
    sections:[
      { label:'Clinical Departments (check all that apply)', fields:[
        { label:'Active Clinical Departments', req:true, type:'checkbox', options:['Internal Medicine','Pediatrics','OB-Gynecology','Surgery','Orthopedics','ENT','Ophthalmology','Dermatology','Psychiatry/Mental Health','Neurology','Cardiology','Pulmonology','Nephrology','Oncology','Urology','Rehabilitation Medicine','Emergency Medicine','Family Medicine','Infectious Disease','Neonatology'] },
      ]},
      { label:'Ancillary & Support Services', fields:[
        { label:'Available Ancillary Services', req:true, type:'checkbox', options:['Laboratory (Clinical Lab)','Radiology / X-Ray','Ultrasound','CT Scan','MRI','Pharmacy','Blood Bank','Dialysis Unit','Physical Therapy','Occupational Therapy','Dietary / Nutrition','Medical Social Work','Dental Services','Mortuary Services','Laundry','Central Supply & Sterilization (CSSD)','Medical Records'] },
      ]},
      { label:'Special Units', fields:[
        { label:'Special Care Units Available', req:false, type:'checkbox', options:['Intensive Care Unit (ICU)','Neonatal ICU (NICU)','Pediatric ICU (PICU)','Cardiac Care Unit (CCU)','Stroke Unit','Burns Unit','Isolation Room / AIIR','Operating Room (OR)','Recovery Room (RR)','Day Surgery Unit','Dialysis Center','Birthing Center / LDR'] },
      ]},
      { label:'Operating Room Details', fields:[
        { label:'Number of Operating Rooms', req:false, type:'text', ph:'e.g. 4' },
        { label:'OR Operating Hours', req:false, type:'text', ph:'e.g. 24/7 / 6am-10pm' },
      ]},
    ]
  },
  {
    id:'bed-management', cat:'operations', icon:'🛏️',
    title:'Ward & Bed Management Setup',
    sub:'Ward layout, bed categories, and room types for census and billing configuration',
    priority:'required',
    sections:[
      { label:'Ward Categories', fields:[
        { label:'List all Wards / Nursing Units', req:true, type:'textarea', ph:'e.g. Male Ward, Female Ward, Pedia Ward, OB Ward, ICU, Pay Room 1-10, Private Rooms...' },
        { label:'Room / Accommodation Types', req:true, type:'checkbox', options:['Ward (Charity)','Semi-Private Room','Private Room','Suite / VIP Room','ICU Bed','Isolation Room','Pay Room','Day Care Bed'] },
        { label:'Total Beds per Ward (provide breakdown)', req:true, type:'textarea', ph:'Male Ward: 20 beds, Female Ward: 20 beds, Pedia: 15 beds...' },
      ]},
      { label:'Billing Classification', fields:[
        { label:'Are room rates different per accommodation type?', req:true, type:'radio', options:['Yes','No'] },
        { label:'Rate per day — Ward/Charity', req:false, type:'text', ph:'PhP amount' },
        { label:'Rate per day — Semi-Private', req:false, type:'text', ph:'PhP amount' },
        { label:'Rate per day — Private Room', req:false, type:'text', ph:'PhP amount' },
      ]},
    ]
  },
  {
    id:'philhealth-setup', cat:'philhealth', icon:'📋',
    title:'PhilHealth eClaims Setup',
    sub:'PhilHealth accreditation details and eClaims portal configuration requirements',
    priority:'required',
    sections:[
      { label:'Facility Accreditation', fields:[
        { label:'PhilHealth Facility Accreditation Number', req:true, type:'text', ph:'PEN/HEI Accreditation No.' },
        { label:'Facility Type for PhilHealth', req:true, type:'select', options:['Primary Care Facility (PCF)','Hospital — Level 1','Hospital — Level 2','Hospital — Level 3','Ambulatory Surgical Clinic','Maternity Care Clinic','TB DOTS Facility','Animal Bite Treatment Center','Renal Dialysis Facility','Birthing Home'] },
        { label:'Date of Latest Accreditation', req:true, type:'text', ph:'MM/DD/YYYY' },
        { label:'Accreditation Expiry Date', req:true, type:'text', ph:'MM/DD/YYYY' },
        { label:'PhilHealth Regional Office In-Charge', req:false, type:'text', ph:'PRO Name / Region' },
      ]},
      { label:'eClaims Portal Credentials', fields:[
        { label:'PhilHealth eClaims Portal Username', req:true, type:'text', ph:'Facility eClaims Username' },
        { label:'Name of eClaims Coordinator', req:true, type:'text', ph:'Full Name, Designation' },
        { label:'eClaims Coordinator Contact Number', req:true, type:'text', ph:'Mobile Number' },
        { label:'eClaims Coordinator Email', req:true, type:'text', ph:'Email Address' },
      ]},
      { label:'Benefit Packages Used', fields:[
        { label:'PhilHealth Benefit Packages (check all active)', req:true, type:'checkbox', options:['All Case Rate (ACR)','Z-Benefit Package','SARS / COVID-19 Package','Maternity Care Package (MCP)','TB-DOTS Package','Animal Bite Treatment Package','Malaria Package','Newborn Care Package','Outpatient HIV/AIDS Treatment Package','Hemodialysis Package','Cataract Package','Primary Care Benefit (PCB1/PCB2)','GUNSHOT / WAR INJURIES','Senior Citizen Package'] },
      ]},
      { label:'Claims History', fields:[
        { label:'Average Monthly PhilHealth Claims Volume', req:false, type:'text', ph:'e.g. 250 claims/month' },
        { label:'Current Claims Processing Method', req:true, type:'radio', options:['Manual (paper-based)','eClaims Portal (existing)','Third-party claims processor','Mixed'] },
        { label:'Average Claims Rejection Rate (if known)', req:false, type:'text', ph:'e.g. 15%' },
        { label:'Main Reasons for Claim Rejections (if known)', req:false, type:'textarea', ph:'e.g. incomplete documents, ICD coding errors...' },
      ]},
      { label:'Accredited Physicians', fields:[
        { label:'Number of PhilHealth-Accredited Physicians', req:true, type:'text', ph:'e.g. 45 physicians' },
        { label:'Do you have a current list of accredited physicians with PRC & PhilHealth numbers?', req:true, type:'radio', options:['Yes — ready to submit','Partially available','No — needs to be compiled'] },
      ]},
    ]
  },
  {
    id:'patient-flow', cat:'operations', icon:'🔄',
    title:'Patient Flow & Admission Process',
    sub:'How patients enter, move through, and exit the hospital — for workflow configuration',
    priority:'required',
    sections:[
      { label:'Admission Sources', fields:[
        { label:'How do patients arrive? (check all)', req:true, type:'checkbox', options:['Walk-in (OPD)','Emergency / ER','Referral from RHU/BHS','Referral from another hospital','Direct admission by private doctor','Scheduled OPD appointment','Maternal / Birthing admission','Post-operative from another facility'] },
        { label:'Average Daily OPD Patients', req:false, type:'text', ph:'e.g. 120 patients/day' },
        { label:'Average Daily ER Patients', req:false, type:'text', ph:'e.g. 40 patients/day' },
        { label:'Average Monthly Admissions (IP)', req:false, type:'text', ph:'e.g. 350 admissions/month' },
      ]},
      { label:'OPD Setup', fields:[
        { label:'OPD Operating Hours', req:true, type:'text', ph:'e.g. Monday–Friday 8am–5pm' },
        { label:'Is OPD appointment-based or walk-in only?', req:true, type:'radio', options:['Walk-in only','Appointment-based','Both'] },
        { label:'Number of OPD Consultation Rooms', req:false, type:'text', ph:'e.g. 8 rooms' },
      ]},
      { label:'Discharge Process', fields:[
        { label:'Who initiates the discharge order?', req:true, type:'radio', options:['Attending Physician only','Resident Doctor with Attending approval','Nursing Station with Doctor order'] },
        { label:'Is there a discharge lounge or checkout area?', req:false, type:'radio', options:['Yes','No'] },
        { label:'Average discharge processing time (from order to clearance)', req:false, type:'text', ph:'e.g. 2-3 hours' },
      ]},
    ]
  },
  {
    id:'clinical-setup', cat:'clinical', icon:'🩺',
    title:'Clinical Services & EMR Configuration',
    sub:'Clinical service details for EMR setup, order sets, and clinical documentation templates',
    priority:'required',
    sections:[
      { label:'Diagnosis & Coding', fields:[
        { label:'ICD Version Currently Used', req:true, type:'radio', options:['ICD-10 (standard)','ICD-11 (latest)','No standard coding used yet'] },
        { label:'Do physicians currently encode their own diagnoses?', req:true, type:'radio', options:['Yes','No — medical records encodes','Mixed practice'] },
        { label:'Is there an existing diagnosis/procedure master list?', req:false, type:'radio', options:['Yes — ready to submit','No — will use iHOMIS+ default'] },
      ]},
      { label:'Laboratory Services', fields:[
        { label:'Lab tests available in-house', req:true, type:'textarea', ph:'e.g. CBC, UA, Blood Chem, Blood Typing, Cultures, Serology...' },
        { label:'Is lab currently using a Laboratory Information System (LIS)?', req:true, type:'radio', options:['Yes — specify brand','No — manual/logbook'] },
        { label:'LIS Brand / System (if yes)', req:false, type:'text', ph:'e.g. LabManager, Medilink, custom...' },
      ]},
      { label:'Radiology / Imaging', fields:[
        { label:'Imaging modalities available', req:true, type:'checkbox', options:['X-Ray (plain)','Fluoroscopy','Ultrasound','CT Scan','MRI','Mammogram','DEXA Scan','Echocardiography','ECG / Holter'] },
        { label:'Is there a PACS (Picture Archiving System) in use?', req:true, type:'radio', options:['Yes — specify brand','No'] },
        { label:'PACS Brand (if yes)', req:false, type:'text', ph:'PACS brand name' },
      ]},
      { label:'Pharmacy', fields:[
        { label:'Is there an in-house pharmacy?', req:true, type:'radio', options:['Yes — 24/7','Yes — limited hours','No — external pharmacy only'] },
        { label:'Drug Formulary Type', req:true, type:'radio', options:['National Drug Formulary (DOH)','Hospital own formulary','Both'] },
        { label:'Approximate number of items in formulary', req:false, type:'text', ph:'e.g. 800 drug items' },
        { label:'Is pharmacy currently using inventory software?', req:true, type:'radio', options:['Yes — specify','No — manual'] },
      ]},
    ]
  },
  {
    id:'hr-setup', cat:'hr', icon:'👥',
    title:'HR, Staffing & Payroll Setup',
    sub:'Workforce information for HR module configuration and payroll computation setup',
    priority:'important',
    sections:[
      { label:'Staffing Overview', fields:[
        { label:'Total Number of Employees', req:true, type:'text', ph:'e.g. 280 employees' },
        { label:'Breakdown by Employment Type', req:true, type:'textarea', ph:'Plantilla: 120, Job Order: 80, Contractual: 50, Consultant/Specialist: 30' },
        { label:'Number of Licensed Medical Doctors (Active)', req:true, type:'text', ph:'e.g. 45 physicians' },
        { label:'Number of Registered Nurses', req:true, type:'text', ph:'e.g. 90 nurses' },
        { label:'Number of other Allied Health Professionals', req:false, type:'text', ph:'e.g. 30 (Med Tech, PhysTherapy, Radtech...)' },
      ]},
      { label:'Payroll Configuration', fields:[
        { label:'Payroll Frequency', req:true, type:'radio', options:['Semi-monthly (1st & 15th)','Semi-monthly (15th & 30th)','Monthly','Weekly'] },
        { label:'Payroll System Currently Used', req:true, type:'radio', options:['Manual (spreadsheet/paper)','Existing software — specify','Government HRIS (NHRIS/HRPMES)','None'] },
        { label:'Existing Payroll System Name (if any)', req:false, type:'text', ph:'System name' },
        { label:'Government Remittances to process', req:true, type:'checkbox', options:['SSS','PhilHealth (Employee)','Pag-IBIG / HDMF','BIR Withholding Tax','GSIS (for government employees)'] },
      ]},
      { label:'Attendance & Scheduling', fields:[
        { label:'Current Time & Attendance Method', req:true, type:'radio', options:['Biometrics (fingerprint/facial)','Bundy Clock','Manual logbook','Swipe card','Mobile app'] },
        { label:'Biometrics Brand / Model (if applicable)', req:false, type:'text', ph:'e.g. ZKTeco K40, Suprema...' },
        { label:'Shift Types Used', req:true, type:'checkbox', options:['Day shift (7am-3pm)','Evening shift (3pm-11pm)','Night shift (11pm-7am)','12-hour shift','8-hour floating shift','Split shift','On-call duty'] },
        { label:'Does the hospital have duty schedules per ward/department?', req:true, type:'radio', options:['Yes','No'] },
      ]},
    ]
  },
  {
    id:'finance-billing', cat:'finance', icon:'💳',
    title:'Finance, Billing & Revenue Setup',
    sub:'Fee structure, payment setup, and financial workflow for billing module configuration',
    priority:'required',
    sections:[
      { label:'Service Fees & Charge Master', fields:[
        { label:'Does the hospital have an existing Charge Master / Price List?', req:true, type:'radio', options:['Yes — electronic (Excel/CSV ready)','Yes — paper-based only','No — needs to be built'] },
        { label:'Number of items in current charge master (approx.)', req:false, type:'text', ph:'e.g. 1,500 items' },
        { label:'Do fees differ per accommodation type?', req:true, type:'radio', options:['Yes','No'] },
        { label:'Are professional fees included in hospital billing?', req:true, type:'radio', options:['Yes — combined billing','No — separate professional fee billing','Mixed by department'] },
      ]},
      { label:'Payment Modes Accepted', fields:[
        { label:'Accepted Payment Methods (check all)', req:true, type:'checkbox', options:['Cash','PhilHealth (primary payer)','HMO / Private Insurance','Senior Citizen Discount (20%)','PWD Discount (20%)','Indigent / Charity (DSWD)','Government employee benefits','GCash / GCash for Business','PayMaya / Maya','Credit Card (Visa/Mastercard)','Cheque','Hospital Payment Plan / Installment'] },
        { label:'HMO Companies with existing hospital accreditation', req:false, type:'textarea', ph:'e.g. Maxicare, Intellicare, Medicard, PhilCare, Insular Health...' },
      ]},
      { label:'Current Financial System', fields:[
        { label:'Current Billing / Accounting Software', req:true, type:'radio', options:['Manual (ledger/spreadsheet)','QuickBooks','SAP','Oracle Financials','Custom system','Government e-NGAS (NGAs only)','None'] },
        { label:'Existing System Name (if custom)', req:false, type:'text', ph:'Software name' },
        { label:'Does the hospital have a separate COA-accredited accounting system?', req:true, type:'radio', options:['Yes','No'] },
        { label:'Fiscal Year', req:true, type:'radio', options:['January – December','July – June','Other'] },
      ]},
    ]
  },
  {
    id:'referral-network', cat:'referral', icon:'🚑',
    title:'Referral Network & Transfer Setup',
    sub:'Referral system configuration — partner facilities, transport, and protocols',
    priority:'required',
    sections:[
      { label:'Referral Role', fields:[
        { label:'This hospital primarily acts as:', req:true, type:'radio', options:['Sending facility (refers patients out)','Receiving facility (accepts referrals)','Both sending and receiving','Referral hub / network coordinator'] },
        { label:'Average monthly outgoing referrals', req:false, type:'text', ph:'e.g. 30 referrals/month' },
        { label:'Average monthly incoming referrals', req:false, type:'text', ph:'e.g. 15 referrals/month' },
        { label:'Top 3 reasons patients are referred out', req:false, type:'textarea', ph:'e.g. ICU/ventilator need, specialized surgery, dialysis...' },
      ]},
      { label:'Referral Partner Facilities', fields:[
        { label:'List primary receiving / partner hospitals', req:true, type:'textarea', ph:'e.g. Jose B. Lingad Memorial Regional Hospital, San Fernando, Pampanga; Philippine General Hospital, Manila...' },
        { label:'List primary referring facilities (sending to us)', req:false, type:'textarea', ph:'e.g. Olongapo City Health Office, RHU Subic, BHS Barretto...' },
        { label:'Is there an existing Memorandum of Agreement (MOA) with partner facilities?', req:false, type:'radio', options:['Yes','No','In progress'] },
      ]},
      { label:'Transport & Logistics', fields:[
        { label:'Hospital-owned ambulance?', req:true, type:'radio', options:['Yes — 1 unit','Yes — 2 or more units','No — coordinate with LGU/BFP','No — private ambulance service'] },
        { label:'Ambulance equipped with?', req:false, type:'checkbox', options:['Basic Life Support (BLS) equipment','Advanced Life Support (ALS) equipment','Oxygen tank','Cardiac monitor/defibrillator','IV stand and supplies','Trained EMT/paramedic on board','GPS tracking device'] },
        { label:'Protocol for emergency transfers (describe briefly)', req:false, type:'textarea', ph:'e.g. ER physician initiates, nurse calls receiving facility, ambulance dispatched within X mins...' },
      ]},
      { label:'Current Referral Process', fields:[
        { label:'How are referrals currently done?', req:true, type:'radio', options:['Phone call only','Paper referral form (manual)','Email','Existing e-referral system','Combined methods'] },
        { label:'Is there a standard referral form used?', req:true, type:'radio', options:['Yes — DOH standard form','Yes — hospital own form','No standard form'] },
        { label:'Do you currently send clinical records with referrals?', req:true, type:'radio', options:['Yes — always','Sometimes — depending on urgency','No — verbal information only'] },
      ]},
    ]
  },
  {
    id:'it-infrastructure', cat:'it', icon:'💻',
    title:'IT Infrastructure & Network',
    sub:'Hardware, connectivity, and existing systems for deployment planning',
    priority:'required',
    sections:[
      { label:'Network & Connectivity', fields:[
        { label:'Internet Service Provider (ISP)', req:true, type:'text', ph:'e.g. PLDT, Globe, Converge, DICT-funded' },
        { label:'Current Internet Bandwidth / Speed', req:true, type:'text', ph:'e.g. 100 Mbps fiber' },
        { label:'Is there a Local Area Network (LAN) in the hospital?', req:true, type:'radio', options:['Yes — wired LAN throughout','Yes — partial coverage','No — WiFi only','No network currently'] },
        { label:'Network Coverage (check areas with network access)', req:true, type:'checkbox', options:['Admin / Cashier','Medical Records','ER / OPD','Nursing Stations (all wards)','Laboratory','Radiology','Pharmacy','ICU','Operating Room','Doctors Lounge / Station','IT Room / Server Room'] },
        { label:'WiFi available in the hospital?', req:true, type:'radio', options:['Yes — full coverage','Yes — partial','No'] },
      ]},
      { label:'Hardware Inventory', fields:[
        { label:'Approximate number of computers/workstations', req:true, type:'text', ph:'e.g. 35 units' },
        { label:'Are computers networked or standalone?', req:true, type:'radio', options:['All networked','Mostly networked','Mostly standalone','All standalone'] },
        { label:'Is there a dedicated server?', req:true, type:'radio', options:['Yes — on-premise server','Yes — rented/cloud server','No dedicated server'] },
        { label:'Server Specs (if existing)', req:false, type:'text', ph:'e.g. Dell PowerEdge, 16GB RAM, 2TB HDD, Windows Server 2019' },
        { label:'UPS / Power backup for server/network?', req:true, type:'radio', options:['Yes — UPS + generator','UPS only','Generator only','None'] },
      ]},
      { label:'Existing Software Systems', fields:[
        { label:'Existing Hospital Information System (if any)', req:true, type:'radio', options:['iHOMIS (old version)','Medilink','QuadraMed','HIMS (generic)','Custom-built system','None'] },
        { label:'Existing System Name (if custom or other)', req:false, type:'text', ph:'System name / version' },
        { label:'Which modules are currently digitized? (check all)', req:true, type:'checkbox', options:['Patient Registration','Billing/Cashiering','Laboratory','Radiology','Pharmacy','Medical Records/EMR','HR/Payroll','Inventory','PhilHealth Claims','Scheduling/Appointments','None yet — fully manual'] },
        { label:'Is data from existing system exportable?', req:false, type:'radio', options:['Yes — CSV/Excel ready','Partially','No — trapped in old system','No existing system'] },
      ]},
      { label:'IT Staff', fields:[
        { label:'Number of IT staff', req:true, type:'text', ph:'e.g. 2 IT staff' },
        { label:'Is there a designated IT In-Charge / System Admin?', req:true, type:'radio', options:['Yes — full-time IT staff','Yes — part-time / shared role','No — outsourced IT support','No IT staff'] },
        { label:'IT In-Charge Name & Contact', req:false, type:'text', ph:'Name, Mobile, Email' },
      ]},
    ]
  },
  {
    id:'key-contacts', cat:'profile', icon:'📞',
    title:'Key Contacts & Project Team',
    sub:'Primary contacts for implementation, training, and ongoing support coordination',
    priority:'required',
    sections:[
      { label:'Project Champion / Decision Maker', fields:[
        { label:'Name & Designation', req:true, type:'text', ph:'Full Name, Chief of Hospital / Administrator' },
        { label:'Contact Number', req:true, type:'text', ph:'Mobile Number' },
        { label:'Email Address', req:true, type:'text', ph:'Official Email' },
      ]},
      { label:'iHOMIS+ System Coordinator (Primary)', fields:[
        { label:'Name & Designation', req:true, type:'text', ph:'Full Name, Designation' },
        { label:'Department', req:true, type:'text', ph:'e.g. Medical Records, IT, Admin' },
        { label:'Contact Number', req:true, type:'text', ph:'Mobile Number' },
        { label:'Email Address', req:true, type:'text', ph:'Official Email' },
      ]},
      { label:'Module-Specific Focal Persons', fields:[
        { label:'PhilHealth / eClaims Focal Person', req:true, type:'text', ph:'Name, Designation, Contact' },
        { label:'Laboratory Focal Person', req:false, type:'text', ph:'Name, Designation, Contact' },
        { label:'Pharmacy / Inventory Focal Person', req:false, type:'text', ph:'Name, Designation, Contact' },
        { label:'HR / Payroll Focal Person', req:false, type:'text', ph:'Name, Designation, Contact' },
        { label:'Finance / Billing Focal Person', req:true, type:'text', ph:'Name, Designation, Contact' },
        { label:'IT / Technical Focal Person', req:true, type:'text', ph:'Name, Designation, Contact' },
      ]},
      { label:'Training Preferences', fields:[
        { label:'Preferred training schedule', req:false, type:'radio', options:['Weekdays (office hours)','Weekends','Evening sessions','Flexible / as scheduled'] },
        { label:'Preferred training venue', req:false, type:'radio', options:['On-site at the hospital','Off-site training center','Online / virtual','Mixed'] },
        { label:'Estimated number of staff to be trained', req:false, type:'text', ph:'e.g. 80 staff members' },
        { label:'Any training requirements or constraints?', req:false, type:'textarea', ph:'e.g. department by department training, no training during shift change...' },
      ]},
    ]
  },
];

function renderFormCard(form) {
  let html = `
    <div class="form-card" data-cat="${form.cat}">
      <div class="form-card-head">
        <div class="form-card-icon">${form.icon}</div>
        <div>
          <div class="form-card-title">${form.title}</div>
          <div class="form-card-sub">${form.sub}</div>
        </div>
      </div>
      <div class="form-card-body">`;

  form.sections.forEach(sec => {
    html += `<div class="form-section-label">${sec.label}</div>`;
    sec.fields.forEach(f => {
      html += `<div class="form-field">`;
      html += `<label>${f.label} <span class="${f.req ? 'req' : 'opt'}">${f.req ? '★ Required' : '○ Optional'}</span></label>`;

      if (f.type === 'text') {
        html += `<input type="text" class="form-input" placeholder="${f.ph||''}" />`;
      } else if (f.type === 'textarea') {
        html += `<textarea class="form-input" rows="3" placeholder="${f.ph||''}" style="resize:vertical;"></textarea>`;
      } else if (f.type === 'select') {
        html += `<select class="form-input"><option value="">— Select —</option>`;
        f.options.forEach(o => html += `<option>${o}</option>`);
        html += `</select>`;
      } else if (f.type === 'radio') {
        html += `<div class="form-radio-group">`;
        f.options.forEach(o => html += `<label class="form-radio"><input type="radio" name="${form.id}-${f.label.replace(/\s/g,'')}" />${o}</label>`);
        html += `</div>`;
      } else if (f.type === 'checkbox') {
        html += `<div class="form-checkbox-group">`;
        f.options.forEach(o => html += `<label class="form-checkbox"><input type="checkbox" /><span>${o}</span></label>`);
        html += `</div>`;
      }
      html += `</div>`;
    });
  });

  const priorityLabel = form.priority === 'required' ? 'Required' : form.priority === 'important' ? 'High Priority' : 'Optional';
  html += `
      </div>
      <div class="form-card-footer">
        <span class="form-priority ${form.priority}">${priorityLabel}</span>
        <button class="form-save-btn" onclick="markComplete(this)">Mark Complete ✓</button>
      </div>
    </div>`;
  return html;
}

function showFormCat(cat) {
  document.querySelectorAll('.form-cat-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('fcat-' + cat).classList.add('active');
  const container = document.getElementById('forms-container');
  const filtered = cat === 'all' ? FORMS : FORMS.filter(f => f.cat === cat);
  container.innerHTML = filtered.map(renderFormCard).join('');
}

function markComplete(btn) {
  const card = btn.closest('.form-card');
  if (card.classList.contains('completed')) {
    card.classList.remove('completed');
    card.style.borderTopColor = '#84cc16';
    btn.textContent = 'Mark Complete ✓';
    btn.style.color = '#84cc16';
  } else {
    card.classList.add('completed');
    card.style.borderTopColor = '#22c55e';
    btn.textContent = '✓ Completed';
    btn.style.color = '#22c55e';
  }
}

function closeInfo() { document.getElementById('infoPanel').classList.remove('active'); }

function switchTier(t) {
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.tier-tab').forEach(b=>b.classList.remove('active'));
  document.getElementById('page-'+t).classList.add('active');
  document.getElementById('tab-'+t).classList.add('active');
  const accent = t==='basic'?'#60a5fa':t==='pro'?'#34d399':t==='ent'?'#c084fc':'#84cc16';
  document.getElementById('tab-'+t).style.setProperty('--tier-accent', accent);
  if(t !== 'forms') closeInfo();
}

// Init — defer until DOM is fully parsed
document.addEventListener('DOMContentLoaded', function() {
  buildModuleCards('b', 'basic');
  buildModuleCards('p', 'pro');
  buildModuleCards('e', 'ent');
  showFormCat('all');
  document.getElementById('tab-basic').style.setProperty('--tier-accent','#60a5fa');
  document.getElementById('tab-forms').style.setProperty('--tier-accent','#84cc16');
});
</script>
</body>
</html>