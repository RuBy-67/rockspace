<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>

<head>
  <title>Moa_Cloud_RockSpace</title>
  <style>
    body {
      background-color: #111;
      color: #fff;
      font-family: Arial, sans-serif;
    }

    h1 {
      text-align: center;
      margin-top: 50px;
    }

    table {
      margin: 0 auto;
      width: 80%;
      border-collapse: collapse;
      margin-top: 50px;
      margin-bottom: 50px;
    }

    th {
      text-align: left;
      font-size: 1.2em;
      padding: 10px;
      border-bottom: 1px solid #666;
    }

    td {
      font-size: 1.1em;
      padding: 10px;
      border-bottom: 1px solid #666;
    }

    a {
      color: #fff;
      text-decoration: none;
    }

    a:hover {
      color: #00ffff;
    }

    #logo {
      display: block;
      margin: 0 auto;
      max-width: 80%;
      height: auto;
      margin-top: 50px;
      margin-bottom: 50px;
    }

    #welcome {
      text-align: center;
      font-size: 1.5em;
    }

    #menu {
      text-align: center;
      margin-top: 50px;
      margin-bottom: 50px;
    }

    #menu ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    #menu li {
      display: inline-block;
      margin-right: 20px;
      font-size: 1.2em;
    }

    #menu li:last-child {
      margin-right: 0;
    }

    #menu a {
      color: #fff;
      text-decoration: none;
    }

    #menu a:hover {
      color: #00ffff;
    }
  </style>
</head>

<body>
  <div id="menu">
    <ul>
      <li><a href="#">Accueil</a></li>
      <li><a href="#">Services</a></li>
      <li><a href="#">Aide</a></li>
      <li><a href="https://tenor.com/fr/view/ac-ac-creator-softr-sav-fan-site-gif-17130910">Contact</a></li>
    </ul>
  </div>
  <h1>Moa_Cloud</h1>
  <table>
    <thead>
      <tr>
        <th><img src="/icons/blank.gif" alt="[ICO]"></th>
        <th><a href="?C=N;O=D">Name</a></th>
        <th><a href="?C=M;O=A">Last modified</a></th>
        <th><a href="?C=S;O=A">Size</a></th>
        <th><a href="?C=D;O=A">Description</a></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $files = glob("*");
      foreach ($files as $file) {
        if (is_dir($file)) {
          echo "<tr>";
          echo "<td><img src=\"/icons/folder.gif\" alt=\"[DIR]\"></td>";
          echo "<td><a href=\"$file/\">$file/</a></td>";
          echo "<td align=\"right\">" . date("Y-m-d H:i", filemtime($file)) . "</td>";
          echo "<td align=\"right\">-</td>";
          echo "<td>&nbsp;</td>";
          echo "</tr>";
        } else {
          echo "<tr>";
          echo "<td><img src=\"/icons/text.gif\" alt=\"[TXT]\"></td>";
          echo "<td><a href=\"$file\">$file</a></td>";
          echo "<td align=\"right\">" . date("Y-m-d H:i", filemtime($file)) . "</td>";
          echo "<td align=\"right\">" . filesize($file) . "</td>";
          echo "<td>&nbsp;</td>";
          echo "</tr>";
        }
      }
      ?>
    </tbody>
  </table>
  </div>
  <footer>
    <address>Apache/2.4.54 (Debian) Server at 127.0.0.1 Port 80</address>
  </footer>
</body>

</html>