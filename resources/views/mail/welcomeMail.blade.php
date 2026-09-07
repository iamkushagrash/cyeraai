<!DOCTYPE html>
<html lang="en-US">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome</title>
  
  <script src="https://kit.fontawesome.com/99d643d875.js" crossorigin="anonymous"></script>
<style type="text/css">
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
    background-color: #fff;
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
  }

  .name .greeting {
    font-size: 1.8em;
    font-weight: bold;
    color: #fff;
  }

  .name .welcome {
    font-size: 1.2em;
    color: #fff;
  }

  .second-content {
    margin-top: 20px;
  }

  .below-para {
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

  .table-container2 {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 0 0 15px 15px;
      margin-bottom: 30px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table-container2 button {
      background-color: #1c4966;
      color: #fff;
      border: none;
      padding: 12px 24px;
      font-size: 1em;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.3s;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table-container2 button:hover {
      background-color: #005CB2;
      transform: scale(1.05);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

  .table-container {
    margin: 20px 0;
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  table th,
  table td {
    padding: 12px;
    text-align: left;
  }

  table th {
    width: 30%;

    color: #1c4966;
    border: 1px solid #ddd;
  }


  table td {
    background-color: #ffffff;
    color: #333;
    border: 1px solid #ddd;
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
    transition: background-color 0.3s;
  }

  .button {
    text-align: center;
    margin-top: 20px;
  }

  .button button {
    background-color: #1c4966;
    color: #fff;
    border: none;
    padding: 12px 24px;
    font-size: 1em;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .button button:hover {
    background-color: #005CB2;
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }

  .fourth-section-para {
    font-size: 1em;
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
  }

  .closing {
    margin-top: 20px;
    font-size: 1.2em;
  }

  .closing p {
    margin: 5px 0;
    font-weight: bold;
  }

  .social-icons {
    display: inline-flex;
    justify-content: center;
    margin-top: 30px;
  }

  .social-icons a {
    margin: 0 15px;
    font-size: 24px;
    color: #1c4966;
    /*transition: transform 0.3s, color 0.3s;*/
  }

  .social-icons a:hover {
    transform: scale(1.2);
  }
</style>
</head>

<body>
  <div class="template">
    <div class="Header">
      <div class="logo">
        <h2 style="color: #6366f1; margin: 0; font-size: 24px;">Cyera AI</h2>
      </div>
      <div class="name">
        <div class="greeting">
          <p>Welcome to Cyera AI<!-- ,$name --></p>
          
        </div>
        <div class="welcome">
          <p>We are excited to have you onboard.</p>
        </div>


      </div>
    </div>
    <div class="second-content">
      <div class="content-name">

      </div>
      <div class="below-para">
        <p>New account has been created. Please use the following credentials to access the new account.</p>
      </div>
    </div>
    <div class="table-container">
      <table>
        <tr>
          <th>UserID:</th>
          <td>{{$data['uniqueid']}}</td>
        </tr>
        <tr>
          <th>Password:</th>
          <td>{{$data['password']}}</td>
        </tr>
      </table>
    </div>
    <!-- <div class="table-container2">
      <a href="#"><button>Verify Your E-Mail</button></a>
    </div> -->
    <div class="fourth-section">
      <div class="fourth-section-para">
        <p>We are thrilled to have you on board and look forward to providing you with the best service. If you have any
          questions or need assistance, our support team is always here to help.</p>
      </div>
      <div class="next-line">Enjoy your new plan and all the benefits that come with it!</div>
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