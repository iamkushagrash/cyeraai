<!DOCTYPE html>
<html lang="en">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{$data['subject']}}</title>

  <script src="https://kit.fontawesome.com/99d643d875.js" crossorigin="anonymous"></script>
</head>

<body>

  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .template {
      background-color: #ffffff;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 90%;
      max-width: 600px;
      margin: 20px;
      padding: 20px;
      text-align: center;
      transition: box-shadow 0.3s;
    }

    .template:hover {
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .Header {
      background-color: #1c4966;
      border-radius: 15px 15px 0 0;
      padding: 20px;
      margin-bottom: 30px;
    }

    .Header img {
      max-height: 60px;
      margin-bottom: 10px;
    }


    .name {
      margin-bottom: 20px;
      /* Align text to the left */
    }

    .name p {
      font-size: 1.8em;
      font-weight: bold;
      margin: 0;
      color: #fff;
    }

    .second-content {
      margin-top: 20px;
      text-align: left;
      /* Align text to the left */
    }

    .content-name p {
      font-size: 1.4em;
      font-weight: bold;
      color: #333;
      margin-bottom: 10px;
    }


    .below-para p {
      font-size: 1.1em;
      color: #555;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .table-container {
      background-color: #f2f2f2;
      padding: 20px;
      border-radius: 0 0 15px 15px;
      margin-bottom: 30px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th {
      background-color: #1c4966;
      ;
      color: #fff;
      padding: 12px;
      text-align: left;
    }

    table td {
      background-color: #ffffff;
      color: #333;
      padding: 12px;
      text-align: left;
    }

    .table-container table th,
    .table-container table td {
      border: 1px solid #ccc;
    }

    .table-container table th:first-child,
    .table-container table td:first-child {
      border-left: none;
    }

    .table-container table th:last-child,
    .table-container table td:last-child {
      border-right: none;
    }

    .table-container table th:hover,
    .table-container table td:hover {
      background-color: #f7f7f7;
      color: black;
    }

    .button {
      text-align: center;
      margin-top: 20px;
    }

    .button button {
      background-color: #1c4966;
      ;
      color: #fff;
      border: none;
      padding: 12px 24px;
      font-size: 1em;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.3s;
    }

    .button button:hover {
      background-color: #1c4966;
      ;
      transform: scale(1.05);
    }

    .social-icons {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .social-icons a {
      margin: 0 15px;
      font-size: 24px;
      color: #333;
      transition: transform 0.3s, color 0.3s;
    }

    .social-icons a:hover {
      transform: scale(1.2);
    }

    .fa-instagram:hover {
      color: #f29605;
    }

    .fa-facebook:hover {
      color: #f29605;
    }

    .fa-twitter:hover {
      color: #f29605;
    }

    .fa-whatsapp:hover {
      color: #f29605;
    }

    .fourth-section-para {
      font-size: 1.1em;
      color: #555;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .next-line {
      font-size: 1.1em;
      color: #555;
      margin-bottom: 20px;
    }

    .closing {
      font-size: 1.2em;
      margin-top: 20px;
      color: #333;
    }

    .closing p {
      margin: 5px 0;
      font-weight: bold;
    }
  </style>


  <div class="template">
    <div class="Header">
      <div class="logo">
        <h2 style="color: #6366f1; margin: 0; font-size: 24px;">Cyera AI</h2>

      </div>

      <div class="name">
        <p>Alert! </p>
      </div>
    </div>
    <div class="second-content">
      <div class="content-name">
        <p>Dear {{ $data['useruuid']}},</p>
      </div>
      <div class="below-para">
      	<p>You made a withdraw request of Amount ${{$data['amountusdt']}} at address <b>{{$data['address']}}</b>. There will be deuction of 10% . After deduction You will get ${{$data['amountusdt']*0.90}}. </p>
        <p>Your OTP for Cyera AI is <b>{{$data['token']}}</b>. If you have not made this request, please contact our support team immediately.</p>
      </div>
    </div>
    
    <div class="fourth-section">
      <div class="fourth-section-para">
        <p>Should you have any questions regarding your account, please do not hesitate to contact our support team.</p>
      </div>
      <!-- <div class="next-line">Enjoy your new plan and all the benefits that come with it!</div> -->
    </div>
    <div class="closing">
      <p>Best regards,</p>
      <p>The Cyera AI Team</p>
    </div>
    <div class="button">
      <button>© 2026 Cyera AI. All rights reserved.</button>
    </div>
    <div class="social-icons">
        <a href="#" target="_blank">
          <img src="https://cyera.ai/ctassets/img/user/instagram.png" width="24" height="24">
        </a>
        <a href="#" target="_blank">
          <img src="https://cyera.ai/ctassets/img/user/facebook.png" width="24" height="24">
        </a>
        <a href="#" target="_blank">
          <img src="https://cyera.ai/ctassets/img/user/twitter.png" width="24" height="24">
        </a>
        <a href="#" target="_blank">
          <img src="https://cyera.ai/ctassets/img/user/telegram.png" width="30" height="30">
        </a>
      </div>
  </div>
</body>

</html>