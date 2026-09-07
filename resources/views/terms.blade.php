<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CYERA AI | Terms & Conditions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-blue: #0066FF;
            --primary-cyan: #00D4FF;
            --hack-green: #00FF9D;
            --dark-bg: #0A0A0F;
            --darker-bg: #050508;
            --card-bg: rgba(10, 15, 25, 0.95);
            --glass-border: rgba(0, 212, 255, 0.15);
            --text-light: #FFFFFF;
            --text-muted: #A0A0C0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 20px;
        }

        .scan-lines {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                0deg,
                rgba(0, 0, 0, 0.15) 0px,
                rgba(0, 0, 0, 0.15) 1px,
                transparent 1px,
                transparent 2px
            );
            z-index: -1;
            pointer-events: none;
            animation: scanMove 20s linear infinite;
        }

        @keyframes scanMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(100px); }
        }

        header {
            width: 100%;
            text-align: center;
            margin-top: 30px;
        }

        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.8rem;
            font-weight: 900;
            background: linear-gradient(90deg, var(--primary-cyan), var(--hack-green));
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: 1.5px;
        }

        .tagline {
            font-size: 1rem;
            color: var(--hack-green);
            letter-spacing: 1.5px;
            font-family: 'Courier New', monospace;
            margin-top: 6px;
        }

        .terms-card {
            width: 100%;
            max-width: 1000px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(0, 212, 255, 0.1);
            overflow: hidden;
            margin-top: 30px;
            margin-bottom: 60px;
            padding: 30px 30px 40px;
        }

        .terms-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 12px;
            background: linear-gradient(90deg, var(--primary-cyan), var(--hack-green));
            -webkit-background-clip: text;
            color: transparent;
            text-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
        }

        .terms-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-family: 'Courier New', monospace;
            margin-bottom: 18px;
        }

        .terms-content {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 10px;
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .terms-content strong {
            color: var(--primary-cyan);
        }

        .terms-content ul {
            padding-left: 20px;
            margin-top: 6px;
            margin-bottom: 6px;
        }

        .terms-content li {
            margin-bottom: 6px;
        }

        footer {
            width: 100%;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            padding: 20px;
            margin-top: auto;
        }

        footer a {
            color: var(--text-muted);
            text-decoration: none;
            margin: 0 15px;
        }

        footer a:hover {
            color: var(--hack-green);
        }
    </style>
</head>

<body>

<div class="scan-lines"></div>

<header>
    <div class="logo-text">CYERA AI</div>
    <div class="tagline">TERMS & CONDITIONS & USER AGREEMENT</div>
</header>

<div class="terms-card">
    <div class="terms-title">Terms & Conditions</div>
    <div class="terms-subtitle">Last Updated: 01/01/2026</div>

    <div class="terms-content">

        1. <strong>LEGAL ENTITIES & GOVERNING PARTIES</strong><br>
        Cyera AI (“Cyera AI”, “We”, “Us”, “Our”) operates as a blockchain-based decentralized digital ecosystem through the following registered entities:<br><br>

        <strong>Singapore Entity</strong><br>
        Registered Address: 6 Raffles Quay, #14-006, Singapore – 48580<br>
        Registration No.: 202139673D<br><br>

        <strong>United Kingdom Entity</strong><br>
        Company Name: Cyera AI<br>
        Company No.: 14951300<br>
        Registered Office: 7 Copperfield Road, Coventry, West Midlands, England, CV2 4AQ<br><br>

        Cyera AI operates globally through decentralized technology and does not restrict access based on geography unless required by law.<br><br>

        2. <strong>ACCEPTANCE OF TERMS (DIGITAL ACCEPTANCE)</strong><br>
        By accessing, registering, using, staking, transacting, or interacting with any Cyera AI platform including but not limited to:<br>
        <ul>
            <li>Cyera AI Website</li>
            <li>Cyera AI WebApp</li>
            <li>Cyera AI Mobile App</li>
            <li>Meta Wallet Mobile App</li>
        </ul>
        you confirm, agree, and legally accept these Terms & Conditions.<br><br>

        ✔ Clicking “I Agree”, “Accept”, “Continue”, or similar action<br>
        ✔ Using the platform after publication of these terms<br>
        ✔ Making any transaction, staking, or wallet interaction<br><br>

        shall constitute valid digital acceptance, legally binding and equivalent to a signed physical agreement, enforceable in courts of law worldwide.<br><br>

        3. <strong>ELIGIBILITY</strong><br>
        You confirm that:<br>
        <ul>
            <li>You are at least 18 years of age</li>
            <li>You are legally allowed to use crypto assets in your jurisdiction</li>
            <li>You are not restricted by any local or international law</li>
            <li>You understand blockchain, crypto assets, and digital wallets</li>
        </ul>
        Cyera AI does not provide legal, tax, or financial advice.<br><br>

        4. <strong>NATURE OF CYERA AI PLATFORM</strong><br>
        Cyera AI is a blockchain-based decentralized ecosystem designed to provide access to:<br>
        <ul>
            <li>Decentralized applications (DApps)</li>
            <li>Wallet services</li>
            <li>Crypto staking mechanisms</li>
            <li>Blockchain-based reward systems</li>
            <li>Digital asset utilities</li>
        </ul>
        Cyera AI is NOT:<br>
        <ul>
            <li>A bank</li>
            <li>A financial institution</li>
            <li>A regulated investment advisor</li>
            <li>A deposit-taking entity</li>
            <li>A guaranteed returns platform</li>
        </ul><br>

        5. <strong>INVESTMENT WARNING & RISK DISCLOSURE</strong><br>
        <strong>IMPORTANT INVESTMENT WARNING</strong><br>
        Crypto assets and blockchain technologies involve high risk.<br><br>

        By using Cyera AI, you explicitly acknowledge and agree that:<br>
        <ul>
            <li>Cryptocurrency markets are extremely volatile</li>
            <li>Digital assets can lose value rapidly or entirely</li>
            <li>Smart contracts may fail or behave unexpectedly</li>
            <li>Regulatory changes may impact access, value, or legality</li>
            <li>Blockchain networks may face congestion, hacks, or failures</li>
        </ul>
        You understand that past performance does not guarantee future results.<br><br>

        6. <strong>NO GUARANTEED RETURNS</strong><br>
        <ul>
            <li>Any staking rewards, yields, or returns shown are indicative only</li>
            <li>Returns are not fixed, guaranteed, or assured</li>
            <li>Cyera AI does not promise profits</li>
            <li>Returns depend on market conditions, protocols, liquidity, and blockchain performance</li>
        </ul>
        You participate entirely at your own risk.<br><br>

        7. <strong>LIMITATION OF LIABILITY</strong><br>
        To the maximum extent permitted by law:<br>
        Cyera AI, its directors, officers, developers, partners, affiliates, and service providers shall not be liable for:<br>
        <ul>
            <li>Market losses</li>
            <li>Price fluctuations</li>
            <li>Missed rewards</li>
            <li>Technical failures</li>
            <li>Smart contract vulnerabilities</li>
            <li>Regulatory changes</li>
            <li>Third-party blockchain failures</li>
            <li>Wallet compromises not caused by Cyera AI’s direct negligence</li>
        </ul><br>

        8. <strong>PRINCIPAL RECOVERY CLAUSE (LIMITED LIABILITY)</strong><br>
        In the event of any mishap, failure, or loss directly attributable to Cyera AI:<br>
        <ul>
            <li>Cyera AI’s maximum responsibility shall be limited to principal recovery only</li>
            <li>Any income, rewards, incentives, bonuses, or earnings already received shall be deducted</li>
            <li>No additional compensation, damages, or claims shall be entertained</li>
        </ul>
        Under no circumstances shall Cyera AI be liable for:<br>
        <ul>
            <li>Lost profits</li>
            <li>Opportunity costs</li>
            <li>Emotional distress</li>
            <li>Indirect or consequential damages</li>
        </ul><br>

        9. <strong>USER RESPONSIBILITIES</strong><br>
        You are solely responsible for:<br>
        <ul>
            <li>Safeguarding private keys and wallet access</li>
            <li>Verifying transaction details before confirmation</li>
            <li>Understanding staking lock-in periods</li>
            <li>Compliance with local laws and taxes</li>
            <li>Maintaining device and cybersecurity hygiene</li>
        </ul>
        Loss of access due to user negligence is not recoverable.<br><br>

        10. <strong>NO FUTURE CLAIMS OR RIGHTS</strong><br>
        By accepting these Terms, you confirm:<br>
        <ul>
            <li>No ownership, equity, or voting rights in Cyera AI</li>
            <li>No claim on future funding rounds, token issuance, valuations, or business decisions</li>
            <li>No entitlement beyond platform utility access</li>
        </ul><br>

        11. <strong>REGULATORY DISCLAIMER</strong><br>
        Cyera AI operates as a decentralized technology platform.<br>
        We do not:<br>
        <ul>
            <li>Register as an investment fund</li>
            <li>Offer securities</li>
            <li>Solicit investments in jurisdictions where prohibited</li>
        </ul>
        Users are solely responsible for determining legality in their jurisdiction.<br><br>

        12. <strong>SUSPENSION & TERMINATION</strong><br>
        Cyera AI reserves the right to:<br>
        <ul>
            <li>Suspend or terminate accounts</li>
            <li>Restrict access</li>
            <li>Freeze transactions if required by law, security, or misuse</li>
        </ul>
        No compensation shall arise from lawful suspension.<br><br>

        13. <strong>INTELLECTUAL PROPERTY</strong><br>
        All branding, logos, designs, interfaces, code, and content belong to Cyera AI or its licensors.<br>
        Unauthorized use is strictly prohibited.<br><br>

        14. <strong>AMENDMENTS TO TERMS</strong><br>
        Cyera AI may update these Terms at any time.<br>
        Continued usage after updates constitutes acceptance of revised terms.<br><br>

        15. <strong>DISPUTE RESOLUTION & ARBITRATION</strong><br>
        <strong>Mandatory Arbitration</strong><br>
        <ul>
            <li>All disputes shall be resolved through binding arbitration only</li>
            <li>No class actions or collective claims permitted</li>
            <li>Courts shall only be used for enforcement of arbitration awards</li>
        </ul>
        <strong>Governing Law</strong><br>
        At Cyera AI’s discretion:<br>
        <ul>
            <li>Singapore Law or</li>
            <li>England & Wales Law</li>
        </ul>
        shall apply.<br><br>

        16. <strong>SEVERABILITY</strong><br>
        If any clause is found invalid, remaining clauses shall remain fully enforceable.<br><br>

        17. <strong>CONTACT INFORMATION</strong><br>
        For all queries, notices, or legal communication:<br>
        📧 Info: info@mwtmail.io<br>
        📧 Legal: legal@mwtmail.io<br><br>

        18. <strong>FINAL ACKNOWLEDGEMENT</strong><br>
        By using Cyera AI, you confirm that:<br>
        <ul>
            <li>You have read and understood these Terms</li>
            <li>You accept all risks knowingly</li>
            <li>You waive claims beyond stated liability limits</li>
            <li>You accept digital acceptance as legally binding</li>
        </ul>

    </div>
</div>

<footer>
    <p>>_ © 2026 Cyera AI</p>
    <a href="/login">LOGIN</a>
    <!-- <a href="/privacy">PRIVACY</a>
    <a href="/support">SUPPORT</a> -->
</footer>

</body>
</html>
