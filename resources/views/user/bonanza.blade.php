<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bonanza Winners</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="{{asset('ctassets/css/scriptui.css')}}" rel="stylesheet">
</head>

<style>
/* ================= BONANZA ================= */

.bonanza-section{
    margin-top:20px;
}
/* HEADER */
.bonanza-header{
    text-align:center;
    margin-bottom:28px;
    position:relative;
}

.bonanza-title{
    font-size:2.2rem;
    font-weight:900;
    letter-spacing:.8px;
    background:linear-gradient(
        90deg,
        var(--primary-blue),
        var(--accent-green),
        var(--primary-blue)
    );
    -webkit-background-clip:text;
    background-clip:text;
    color:transparent;
    text-shadow:0 0 18px rgba(0,212,255,.35);
}

/* Accent line below heading */
.bonanza-header::after{
    content:"";
    display:block;
    width:70px;
    height:3px;
    margin:14px auto 0;
    border-radius:10px;
    background:linear-gradient(
        90deg,
        transparent,
        var(--primary-blue),
        var(--accent-green),
        transparent
    );
}

.bonanza-subtitle{
    color:rgba(255,255,255,.65);
    font-size:.95rem;
    margin-top:10px;
    letter-spacing:.4px;
}


/* ================= SEARCH ================= */

.bonanza-search{
    display:flex;
    justify-content:center;
    margin:22px 0 30px;
}

.bonanza-search input{
    max-width:420px;
    width:100%;
    padding:14px 18px;
    font-size:1rem;
    border-radius:14px;
    border:1px solid rgba(0,212,255,.25);
    background:rgba(0,0,0,.35);
    color:#fff;
    outline:none;
    text-align:center;
    transition:.3s ease;
    box-shadow:inset 0 0 0 rgba(0,0,0,0);
}

.bonanza-search input::placeholder{
    color:rgba(255,255,255,.45);
    letter-spacing:.4px;
}

.bonanza-search input:focus{
    border-color:var(--primary-blue);
    box-shadow:
        0 0 0 3px rgba(0,212,255,.15),
        0 0 20px rgba(0,212,255,.35);
    background:rgba(0,0,0,.45);
}

/* ================= GRID ================= */

.bonanza-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:18px;
}

/* ================= CARD ================= */

.bonanza-card{
    background:var(--card-bg);
    border:1px solid rgba(0,212,255,.18);
    border-radius:14px;
    padding:20px 16px;
    text-align:center;
    position:relative;
    transition:.3s ease;
}

.bonanza-card:hover{
    transform:translateY(-4px);
    box-shadow:
        0 0 18px rgba(0,212,255,.25),
        0 12px 25px rgba(0,0,0,.6);
}

/* BADGE */
.bonanza-badge{
    position:absolute;
    top:10px;
    left:10px;
    font-size:.7rem;
    padding:3px 9px;
    border-radius:20px;
    background:rgba(0,212,255,.15);
    border:1px solid rgba(0,212,255,.35);
    color:#7fe7ff;
}

/* FLAG */
.bonanza-flag{
    position:absolute;
    top:10px;
    right:10px;
    width:32px;
    height:20px;
    border-radius:4px;
    border:1px solid rgba(255,255,255,.25);
}

/* AVATAR */
.bonanza-avatar{
    width:60px;
    height:60px;
    border-radius:50%;
    background:linear-gradient(135deg,var(--primary-blue),var(--accent-green));
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 10px;
    font-size:1.4rem;
    font-weight:900;
    color:#02131f;
    box-shadow:0 0 12px rgba(0,212,255,.35);
}

/* TEXT */
.bonanza-name{
    font-size:1rem;
    font-weight:700;
    color:var(--primary-blue);
}

.bonanza-id{
    font-size:.8rem;
    color:var(--text-muted);
    margin-top:3px;
    letter-spacing:.4px;
}

.bonanza-country{
    font-size:.75rem;
    margin-top:5px;
    color:#7fe7ff;
}

/* MOBILE */
@media(max-width:576px){
    .bonanza-grid{
        grid-template-columns:repeat(2,1fr);
        gap:14px;
    }
    .bonanza-title{
        font-size:1.5rem;
    }
    .bonanza-search input{
        max-width:100%;
    }
}
</style>

<body>

<!-- BACKGROUND -->
<div class="bg-animation"></div>
<div class="grid-lines"></div>
<div class="energy-wave"></div>
<div class="crypto-icons"></div>

<div class="container">

    @include('ui.sidebaruser')

    <div class="main-content">

        @include('ui.topbaruser')

        <!-- BONANZA -->
        <div class="bonanza-section">

         <div class="bonanza-header">
    <h2 class="bonanza-title">Winter Blast Bonanza Winners</h2>
    <div class="bonanza-subtitle">
        🎉 Check if your ID is among our lucky winners
    </div>
</div>


            <!-- SEARCH -->
            <div class="bonanza-search">
                <input type="text" id="searchID"
                       placeholder="Enter Your Winner ID (eg: CAI8596363)">
            </div>
@php
      $winners = [
      ['name' => 'NATARAJU', 'ID' => 'CAI6679211', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Dhyan Singh', 'ID' => 'CAI7627161', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'CHARANJEET', 'ID' => 'CAI1560691', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sonali kamble', 'ID' => 'CAI4037284', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Varun', 'ID' => 'CAI8010293', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sachin Kumar', 'ID' => 'CAI5990136', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'ramniwas Pohal', 'ID' => 'CAI7911660', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Dr. Premlata', 'ID' => 'CAI6153645', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Madhav', 'ID' => 'CAI6662113', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shree balkrushna9', 'ID' => 'CAI5530218', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'AryanS', 'ID' => 'CAI2275524', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rathi 22', 'ID' => 'CAI8097407', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sandeep rathee', 'ID' => 'CAI1451118', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sampati Chawatt', 'ID' => 'CAI9716510', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sunil Kumar Baroliya', 'ID' => 'CAI4835363', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ram', 'ID' => 'CAI6046516', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Reena', 'ID' => 'CAI5380412', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sunil khatkar', 'ID' => 'CAI4913381', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Banti4', 'ID' => 'CAI7848861', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Divyanshi Gehlot', 'ID' => 'CAI2718901', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kosal d', 'ID' => 'CAI8687455', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shweta', 'ID' => 'CAI8813065', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Anil kumar', 'ID' => 'CAI1780590', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Devansh', 'ID' => 'CAI4484143', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ankit Kumar', 'ID' => 'CAI5372726', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'MD AKHEEL AHMED', 'ID' => 'CAI2300555', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'RAVI', 'ID' => 'CAI6467339', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'George Selvan', 'ID' => 'CAI8891622', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'pradeep & Zakir', 'ID' => 'CAI8077540', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'BTC KING', 'ID' => 'CAI6119236', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sreeramula Srinivas', 'ID' => 'CAI7080697', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'B P Singh', 'ID' => 'CAI5226749', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'WINNER', 'ID' => 'CAI9128622', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ani1', 'ID' => 'CAI2106775', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sandeep ekka', 'ID' => 'CAI2456970', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'PREETI SINGH', 'ID' => 'CAI6274132', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Leader 3', 'ID' => 'CAI2059823', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rakesh Kumar', 'ID' => 'CAI4566656', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rambabu', 'ID' => 'CAI6035732', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Anu', 'ID' => 'CAI4904768', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sanju', 'ID' => 'CAI8852598', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Radha', 'ID' => 'CAI4258075', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Baldev Singh', 'ID' => 'CAI8557853', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'VINAY SHARMA', 'ID' => 'CAI7236800', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Gold', 'ID' => 'CAI8037104', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Amit', 'ID' => 'CAI9702322', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'B R 68 1', 'ID' => 'CAI5508411', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rajesh Kumar', 'ID' => 'CAI3210699', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Gopal singh Bhati', 'ID' => 'CAI1236702', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manoj', 'ID' => 'CAI7091449', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SUMIT KUMAR', 'ID' => 'CAI3157497', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SHREE GANESH11', 'ID' => 'CAI8312925', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'JAI MAHAKAL KRIPA', 'ID' => 'CAI6074989', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'S Silver Stone Six', 'ID' => 'CAI3788127', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manjeet Kohli', 'ID' => 'CAI9239805', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'KINGAD', 'ID' => 'CAI8332270', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Robin Chabra', 'ID' => 'CAI6873718', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shree', 'ID' => 'CAI8541141', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'KGN Enterprises', 'ID' => 'CAI9230986', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Abhishek', 'ID' => 'CAI3321996', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SHEKHAR BHUTAD', 'ID' => 'CAI6781238', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Hzb01', 'ID' => 'CAI6994612', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kapil khanna', 'ID' => 'CAI7462527', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Riya', 'ID' => 'CAI8171606', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'CAI', 'ID' => 'CAI4948406', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Devansh', 'ID' => 'CAI1821818', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'vijay pal jat', 'ID' => 'CAI3511829', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Zakir', 'ID' => 'CAI7801701', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Budram', 'ID' => 'CAI1413014', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manikandan', 'ID' => 'CAI4680889', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Abhi', 'ID' => 'CAI3392461', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Munni', 'ID' => 'CAI9084467', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manikandan', 'ID' => 'CAI4456964', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Satish Jat', 'ID' => 'CAI6810681', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sanjay Rajbhoj', 'ID' => 'CAI5304276', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ruby', 'ID' => 'CAI5699856', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ruby 1', 'ID' => 'CAI7268143', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Dhirender', 'ID' => 'CAI5935651', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ravi', 'ID' => 'CAI7537223', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Dhiraj patidar', 'ID' => 'CAI2703240', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Abhishek Pal', 'ID' => 'CAI7136002', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Navnath musmade', 'ID' => 'CAI7819179', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Budhram1', 'ID' => 'CAI8174164', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Abhi1', 'ID' => 'CAI6044048', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Abhi2', 'ID' => 'CAI6195119', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Daksh', 'ID' => 'CAI1647917', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vasanth', 'ID' => 'CAI8219949', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vasanth', 'ID' => 'CAI8265635', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vasanth', 'ID' => 'CAI1995533', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vasanth', 'ID' => 'CAI1240092', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SHANTHI', 'ID' => 'CAI5675393', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SHANTHI', 'ID' => 'CAI6850422', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manoj sharma', 'ID' => 'CAI9040771', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'RINDESH KUMAR', 'ID' => 'CAI9000820', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vijay Makhija1', 'ID' => 'CAI3980925', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'JAKHAR', 'ID' => 'CAI2159318', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'H S JAKHAR', 'ID' => 'CAI7844554', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kumar', 'ID' => 'CAI5488664', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Jai Shree Krishna', 'ID' => 'CAI5967436', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'A khandelwal 1', 'ID' => 'CAI4531352', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'cyera ai RR', 'ID' => 'CAI8363423', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ashok Gupta', 'ID' => 'CAI9631812', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Aumf2', 'ID' => 'CAI1412613', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'BL BHATT', 'ID' => 'CAI3743851', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Priyanshi', 'ID' => 'CAI5495790', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'BABAJI01', 'ID' => 'CAI1903100', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'ROUBIN THAKUR', 'ID' => 'CAI1870550', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Depika', 'ID' => 'CAI9742108', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Harendra', 'ID' => 'CAI8288980', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Amit jain', 'ID' => 'CAI5008211', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vishwa', 'ID' => 'CAI4315999', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'CYERA AI', 'ID' => 'CAI6657745', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ranjeet', 'ID' => 'CAI1753693', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Naresh Kumar', 'ID' => 'CAI9071275', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Digambar Singh 1', 'ID' => 'CAI1125255', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rajeev Kumar', 'ID' => 'CAI5106090', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Jati ji 5', 'ID' => 'CAI5441216', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SUSHIL KUMAR SAINI', 'ID' => 'CAI1633651', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Indarjeet singh', 'ID' => 'CAI7659374', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Dev 9', 'ID' => 'CAI1816150', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Om prakash kumar', 'ID' => 'CAI9026302', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Suman Gupta', 'ID' => 'CAI1210526', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rajeev', 'ID' => 'CAI4521320', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'BHUNESHWARI', 'ID' => 'CAI4289634', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Narendra Singh', 'ID' => 'CAI5838525', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sahil kashish1', 'ID' => 'CAI6126025', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sk3000', 'ID' => 'CAI4376740', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Subhash dogra', 'ID' => 'CAI5032727', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'NKK', 'ID' => 'CAI8516363', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Aslam', 'ID' => 'CAI8122169', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Jai Shree Shyam', 'ID' => 'CAI5885173', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Joydeb mondal', 'ID' => 'CAI7137798', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'MD SIDDIQUE', 'ID' => 'CAI8313445', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'NILKANTH KHUTEL', 'ID' => 'CAI5639043', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Jai Mahakal', 'ID' => 'CAI2340125', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'RAJENDRA SINGH RATHORE', 'ID' => 'CAI7819493', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sachin Sankhla', 'ID' => 'CAI6395420', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sai shwet choudhary', 'ID' => 'CAI6152757', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Pooja', 'ID' => 'CAI5502504', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rashid Ansari', 'ID' => 'CAI2276804', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Anand kumar', 'ID' => 'CAI6152905', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Satish 04', 'ID' => 'CAI5071739', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Nasheman khan', 'ID' => 'CAI5548406', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rathi 3', 'ID' => 'CAI7095058', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kkk', 'ID' => 'CAI2436183', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Drashay Singh Saini', 'ID' => 'CAI4847548', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ankit Kumar', 'ID' => 'CAI5948376', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Jitendra Singh', 'ID' => 'CAI6435650', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kavya', 'ID' => 'CAI9698353', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Parveen rathi', 'ID' => 'CAI5187442', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'achuma', 'ID' => 'CAI5249754', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ravi', 'ID' => 'CAI5760081', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sonu', 'ID' => 'CAI9764032', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'S choudhary', 'ID' => 'CAI8117289', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'G K ASSOCIATES', 'ID' => 'CAI6168904', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Pankaj kumar', 'ID' => 'CAI2773467', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'K R MEENA', 'ID' => 'CAI1244173', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Zubair5', 'ID' => 'CAI9389482', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Amit Kumar', 'ID' => 'CAI5637261', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'S Silver Stone Four', 'ID' => 'CAI4459219', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'TEAM ONE', 'ID' => 'CAI9630105', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Satish 01', 'ID' => 'CAI3812774', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Madan Lal Salodia', 'ID' => 'CAI8704039', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'venkatesh', 'ID' => 'CAI4259458', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Madhu', 'ID' => 'CAI8107836', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Hitesh kumar 001', 'ID' => 'CAI7730835', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vidyadhar Saini', 'ID' => 'CAI5989127', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manikandan', 'ID' => 'CAI1347006', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manikandan', 'ID' => 'CAI4519000', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manikandan', 'ID' => 'CAI2501362', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Akbar sherrif', 'ID' => 'CAI4845095', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Akbar sherrif', 'ID' => 'CAI2784510', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Akbar sherrif', 'ID' => 'CAI7499168', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Akbar sherrif', 'ID' => 'CAI6275022', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sathish kumar', 'ID' => 'CAI4732058', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sathish kumar', 'ID' => 'CAI7780911', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sathish kumar', 'ID' => 'CAI7488211', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sathish kumar', 'ID' => 'CAI7160987', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'MD Khalndar ', 'ID' => 'CAI9217033', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Jai balaji', 'ID' => 'CAI5038285', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'vidyut kavach', 'ID' => 'CAI8596363', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'meta group', 'ID' => 'CAI4992327', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'KHUSHI', 'ID' => 'CAI8789242', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'ASHOK', 'ID' => 'CAI7714779', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'RAJ', 'ID' => 'CAI9649760', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Devendra singh', 'ID' => 'CAI8054173', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'PRASHANT GORDE', 'ID' => 'CAI8814534', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Jai Shree Krishna', 'ID' => 'CAI9064818', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Aumf', 'ID' => 'CAI8816026', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Aumf1', 'ID' => 'CAI5725183', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Manoj Kumar', 'ID' => 'CAI6855479', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ravi Kumar', 'ID' => 'CAI3164795', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ravi Kumar', 'ID' => 'CAI1385504', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Robin Chhabra', 'ID' => 'CAI1743237', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kahi2121', 'ID' => 'CAI8397727', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Radiant group', 'ID' => 'CAI4891833', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Saksham/sabu', 'ID' => 'CAI4892889', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'A/G 1', 'ID' => 'CAI6689796', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Saksham1', 'ID' => 'CAI4972099', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sabu1', 'ID' => 'CAI3263526', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Jaswant', 'ID' => 'CAI1743148', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'GOODLUCK', 'ID' => 'CAI4025243', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'DK Sahab', 'ID' => 'CAI3262823', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Parveen rathi', 'ID' => 'CAI4109601', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shayam baba', 'ID' => 'CAI3490759', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sohan Lal', 'ID' => 'CAI4139753', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'JASWANT 1', 'ID' => 'CAI9012520', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Sanjeet Kumar', 'ID' => 'CAI1615142', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'CHANDAN KUMAR JAYSWAL', 'ID' => 'CAI2589425', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kirti Singh', 'ID' => 'CAI3945967', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Seth sawariya', 'ID' => 'CAI8690098', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shyam kumar ray', 'ID' => 'CAI7988008', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Praveen kumar', 'ID' => 'CAI5248565', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Jain', 'ID' => 'CAI7244937', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Anil Kumar Jain', 'ID' => 'CAI6358701', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Mahir Sanjay', 'ID' => 'CAI9609534', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Guru ji', 'ID' => 'CAI3214200', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'BALBIR SINGH JAMWAL', 'ID' => 'CAI6963316', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Gangadharan', 'ID' => 'CAI9518144', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Uttam Sarangdhar Kolhe', 'ID' => 'CAI5571053', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Satish Kayat', 'ID' => 'CAI5594905', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'CAI', 'ID' => 'CAI8577907', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Taqi khan', 'ID' => 'CAI2632557', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rajesh kumar', 'ID' => 'CAI8686239', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Devender', 'ID' => 'CAI6478404', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ravi Kumar', 'ID' => 'CAI5300116', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'JVN ASSOCIATE', 'ID' => 'CAI7096015', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rocks Bhahda', 'ID' => 'CAI7058099', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sahiba sayyed', 'ID' => 'CAI3821579', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shiv9', 'ID' => 'CAI6777854', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'CHANDAN KUMAR JAYSWAL', 'ID' => 'CAI7902924', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Radhe', 'ID' => 'CAI8605035', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SERVICE', 'ID' => 'CAI9596089', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shradha', 'ID' => 'CAI7514514', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Annu', 'ID' => 'CAI5493136', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'KUMARI SHOBHA', 'ID' => 'CAI3990484', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SAMANTHA', 'ID' => 'CAI8936065', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'DEV KUMAR PASWAN', 'ID' => 'CAI9231619', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Dream2', 'ID' => 'CAI2706797', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Gittika Sharma', 'ID' => 'CAI3063181', 'ID' => 'CAI8596363', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Virendra Joshi', 'ID' => 'CAI8784120', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manoj Kumar', 'ID' => 'CAI7268120', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Hardeep', 'ID' => 'CAI1719025', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Govind kumar', 'ID' => 'CAI8563140', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Mahadev', 'ID' => 'CAI6625078', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rajesh', 'ID' => 'CAI2960265', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'AADHYA SAHU', 'ID' => 'CAI2017310', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Radhe foji', 'ID' => 'CAI6016542', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'OKA+++', 'ID' => 'CAI2736521', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'OkA+++A', 'ID' => 'CAI2425340', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'OKA+++B', 'ID' => 'CAI6538924', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Aaryan', 'ID' => 'CAI6655582', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'VIKASH KUMAR PASWAN', 'ID' => 'CAI2630147', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Dayashankar', 'ID' => 'CAI5758064', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vksoni', 'ID' => 'CAI7751795', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Santosh kumar', 'ID' => 'CAI8704652', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Dheeraj kumar', 'ID' => 'CAI4273405', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Naveen pahal', 'ID' => 'CAI4745869', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Raman Garg', 'ID' => 'CAI4173699', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sachin', 'ID' => 'CAI7207282', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ranjan Kumar', 'ID' => 'CAI4247190', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Parveen  saharan', 'ID' => 'CAI2283827', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Parveen  saharan', 'ID' => 'CAI3241737', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Parveen  saharan', 'ID' => 'CAI5435764', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Thai2', 'ID' => 'CAI3541872', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manish Kumar', 'ID' => 'CAI1565728', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sohan Lal', 'ID' => 'CAI5702290', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sumit kumar shaw', 'ID' => 'CAI5916300', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'jai Shree shayam', 'ID' => 'CAI2784434', 'country' => 'Country', 'flag_code' => 'in'],
      ['name' => 'Suraj tiwari', 'ID' => 'CAI4779677', 'country' => 'India', 'flag_code' => 'sg'],
      ['name' => 'JAIMATADI', 'ID' => 'CAI2585097', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'XYZ', 'ID' => 'CAI9378183', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vikash & Chauhan', 'ID' => 'CAI9227487', 'country' => 'India', 'flag_code' => 'sg'],
      ['name' => 'Mukesh sharma', 'ID' => 'CAI9973181', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Tarunika m  Gamit', 'ID' => 'CAI2396698', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Abhinav', 'ID' => 'CAI1336088', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sandeep Krishna Sawant', 'ID' => 'CAI7279479', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sultan', 'ID' => 'CAI9521660', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Meta1', 'ID' => 'CAI6644014', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sk CAI', 'ID' => 'CAI1901866', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SATHIYABALAN P', 'ID' => 'CAI3365949', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Guru ji 10', 'ID' => 'CAI3156573', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'JAY BHERUNATH JI', 'ID' => 'CAI7702680', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Jai', 'ID' => 'CAI3431643', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'jitu', 'ID' => 'CAI1523323', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Radhey meta', 'ID' => 'CAI7351572', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Cyera AI Project', 'ID' => 'CAI2598382', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Jai Shree Shyam', 'ID' => 'CAI9783681', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'OM JAI', 'ID' => 'CAI8640693', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'SMRPS GROUP', 'ID' => 'CAI5965152', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'DUBAI KING', 'ID' => 'CAI6959620', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'JAY SHREE KRISHNA', 'ID' => 'CAI1666283', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'NARENDRA BIJORIYA', 'ID' => 'CAI1671648', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'HASI', 'ID' => 'CAI4140629', 'country' => 'United Kingdom', 'flag_code' => 'gb'],
      ['name' => 'Jai', 'ID' => 'CAI4101341', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sugandiya', 'ID' => 'CAI8894910', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Gagan Jain', 'ID' => 'CAI5595842', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Narendra', 'ID' => 'CAI9573696', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'DR RASHID', 'ID' => 'CAI7536314', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Hashim Alam', 'ID' => 'CAI2612655', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'MASARKAR', 'ID' => 'CAI8812875', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'AVIFLXO', 'ID' => 'CAI2094434', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Mehar singh Rana', 'ID' => 'CAI8578067', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Mehar Singh', 'ID' => 'CAI5581335', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shyambaba', 'ID' => 'CAI7123512', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'deswal', 'ID' => 'CAI9953044', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'UDPR', 'ID' => 'CAI1329934', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'VS PUVARASAN', 'ID' => 'CAI2256887', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'JAI SHREE MAHAKAL', 'ID' => 'CAI3616647', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Khushi Atela', 'ID' => 'CAI8902560', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'CHEEKU', 'ID' => 'CAI9591043', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'VISION ALGO', 'ID' => 'CAI2145146', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Parmanand Pawar', 'ID' => 'CAI4470900', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'U shahab', 'ID' => 'CAI2840146', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'CHINMAY SWAMI', 'ID' => 'CAI7399088', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Hardik', 'ID' => 'CAI3189092', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'AD&DR', 'ID' => 'CAI9620064', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sanjay Kumar', 'ID' => 'CAI1287229', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rajshekhar Hiremath', 'ID' => 'CAI9235847', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SATISH JADHAO', 'ID' => 'CAI7261133', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Govind Bhahda', 'ID' => 'CAI2713058', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Mukesh Verma', 'ID' => 'CAI6343440', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'AGP3', 'ID' => 'CAI2700625', 'country' => 'United Arab Emirates', 'flag_code' => 'ae'],
      ['name' => 'Satish Prabhakar More', 'ID' => 'CAI5733471', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'RSBL', 'ID' => 'CAI7789965', 'country' => 'United Arab Emirates', 'flag_code' => 'ae'],
      ['name' => 'AGP9', 'ID' => 'CAI1961550', 'country' => 'United Arab Emirates', 'flag_code' => 'ae'],
      ['name' => 'Champion', 'ID' => 'CAI4693259', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Om namah shivaay 1', 'ID' => 'CAI9846110', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Aaryn ji 1008', 'ID' => 'CAI4966958', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vijender Singh', 'ID' => 'CAI4776780', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Bhim Dev', 'ID' => 'CAI4210145', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sai Sidhi 2', 'ID' => 'CAI1314883', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Sivaprabu', 'ID' => 'CAI3802384', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SUNIL VERMA', 'ID' => 'CAI9818438', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vikram Singh', 'ID' => 'CAI3002448', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sonal bhakta', 'ID' => 'CAI8842177', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Alpesh Bhakta', 'ID' => 'CAI1435809', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sonal Bhakta', 'ID' => 'CAI8816955', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Md Manawar Ansari', 'ID' => 'CAI2101016', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vipul Bhakta', 'ID' => 'CAI1691707', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Guddi bibi', 'ID' => 'CAI3532072', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Manawar Ansari',  'ID' => 'CAI4686504','country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Royal Jharkhand', 'ID' => 'CAI5043723', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Priya CAI', 'ID' => 'CAI5376151', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ajay Kumar Singh', 'ID' => 'CAI1790107', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'SHANKAR KUMAR SHARMA', 'ID' => 'CAI1299595', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shivansh', 'ID' => 'CAI9383567', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'King kohli', 'ID' => 'CAI2475409', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rahul raj', 'ID' => 'CAI2234400', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'India01', 'ID' => 'CAI6203633', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rajesh Pal', 'ID' => 'CAI7314524', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'TABISH REHAN', 'ID' => 'CAI6200127', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Chrish ketan mehta', 'ID' => 'CAI7562232', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Chandrashekhar Bheem Singh',  'ID' => 'CAI4969916','country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Chandrabhan yadav', 'ID' => 'CAI1421183', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'satyendrasinh rajput', 'ID' => 'CAI6466890', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Rewa ram sahu', 'ID' => 'CAI4047069', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vijender Singh', 'ID' => 'CAI5559927', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kushal Gupta', 'ID' => 'CAI4106137', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'USDTMILLIONAIRE', 'ID' => 'CAI5858047', 'country' => 'Malaysia', 'flag_code' => 'my'],
      ['name' => 'Legacy7', 'ID' => 'CAI4262513', 'country' => 'Malaysia', 'flag_code' => 'my'],
      ['name' => 'CHAMPION B', 'ID' => 'CAI1177961', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'ANKITA 1', 'ID' => 'CAI9910564', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Alam', 'ID' => 'CAI1490770', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Arush Tyagi', 'ID' => 'CAI6633933', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sonika', 'ID' => 'CAI4704741', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shubham Kadam', 'ID' => 'CAI6004174', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Varun', 'ID' => 'CAI6488584', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Dinesh oraon', 'ID' => 'CAI5069191', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Renu kumari', 'ID' => 'CAI8351532', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Md Saif', 'ID' => 'CAI9675676', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Mridul', 'ID' => 'CAI2471285', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Roshini', 'ID' => 'CAI1220023', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Vijayakumar V', 'ID' => 'CAI7855182', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SATISHKING', 'ID' => 'CAI4298740', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'A.Shanmugasundaram', 'ID' => 'CAI4437878', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Kalpanadevi', 'ID' => 'CAI5444320', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Panchasaram K', 'ID' => 'CAI4045749', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Vinay', 'ID' => 'CAI7351774', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'KANNAN',  'ID' => 'CAI5902018','country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Radhe Radhe', 'ID' => 'CAI4632113', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ritesh', 'ID' => 'CAI3425605', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Shubham kumar', 'ID' => 'CAI1945238', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Tulsi yadav', 'ID' => 'CAI8345864', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'Vijay pal', 'ID' => 'CAI1541905', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kartik Singh', 'ID' => 'CAI8787886', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Balaji01', 'ID' => 'CAI1170847', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Meta11', 'ID' => 'CAI7515092', 'country' => 'Singapore', 'flag_code' => 'sg'],
      ['name' => 'unique dream', 'ID' => 'CAI1191459', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Suresh Kumar', 'ID' => 'CAI3713110', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SUNIL YADAV', 'ID' => 'CAI5358775', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Anuj', 'ID' => 'CAI5919648', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Ajay', 'ID' => 'CAI3470245', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kp', 'ID' => 'CAI6523599', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Amit2', 'ID' => 'CAI3048558', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'SYNDICATE 1', 'ID' => 'CAI7034570', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Kp1', 'ID' => 'CAI7201450', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Monu', 'ID' => 'CAI4381328', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Sushma', 'ID' => 'CAI7748394', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'shree shyam', 'ID' => 'CAI9565010', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Nagpur', 'ID' => 'CAI4225690', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Saroj', 'ID' => 'CAI9000404', 'country' => 'India', 'flag_code' => 'in'],
      ['name' => 'Durga Dutt', 'ID' => 'CAI4711424', 'country' => 'India', 'flag_code' => 'in'],
      ];
      @endphp

            <div class="bonanza-grid" id="bonanzaGrid">
                @foreach($winners as $w)
                <div class="bonanza-card glow-effect" data-id="{{$w['ID']}}">
                    <div class="bonanza-badge">WINNER</div>
                    <img class="bonanza-flag" src="https://flagcdn.com/w40/{{$w['flag_code']}}.png">
                    <div class="bonanza-avatar">{{ strtoupper(substr($w['name'],0,1)) }}</div>
                    <div class="bonanza-name">{{$w['name']}}</div>
                    <div class="bonanza-id">{{$w['ID']}}</div>
                    <div class="bonanza-country">{{$w['country']}}</div>
                </div>
                @endforeach
            </div>

        </div>

        <div class="footer">
            <p>© 2026 Cyera AI. All rights reserved.</p>
        </div>

    </div>
</div>

<script src="{{asset('ctassets/js/scriptui.js')}}"></script>

<script>
document.getElementById('searchID').addEventListener('keyup', function () {
    let value = this.value.toUpperCase();
    document.querySelectorAll('.bonanza-card').forEach(card => {
        card.style.display = card.dataset.id.includes(value) ? '' : 'none';
    });
});
</script>

</body>
</html>
