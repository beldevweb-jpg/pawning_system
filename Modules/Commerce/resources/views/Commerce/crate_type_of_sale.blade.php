<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โอ๋ โมบาย</title>
    <link rel="stylesheet" href="type_of_sale.css">
    <script src="app.js" defer></script> 
    
</head>
<body>
    <header class="header">
    <div class="user-info">
      <div class="avatar"></div>
      <span class="username">username</span>
    </div>
    <button class="logout">ออกจากระบบ</button>
  </header>

  <main class="container">
    <h1 class="title">โอ๋ โมบาย</h1>

    <section class="menu-card">
      <div class="menu-item" onclick="goPage('pawning.html')" name="pawning" type="button" >ขาย</div>
      <div class="menu-item" name="sale" type="button" >ขายหน้าร้าน</div>
      <div class="menu-item" name="service" type="button">บริการ</div>
    </section>
  </main>
</body>
</html>