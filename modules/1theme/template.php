<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="robots" content="<?= $page->robots == 0 ? 'all' : 'noindex, follow' ?>"/>

  <title><?= $page->title ? $page->get_title() : $page->get_name(); ?></title>
  <meta name="description" content="<?php $page->get_description();?>">

  <?php $page->get_headhtml();?>
  
  <?= $Config->yaweb ? '<meta name="yandex-verification" content="' . $Config->yaweb . '"/>' : '' ?>

  <link href="<?php echo $Config->icon;?>" rel="icon">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


  <link href="/modules/1theme/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="/modules/1theme/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/modules/1theme/assets/css/main.css" rel="stylesheet">
  <link href="/modules/1theme/assets/css/media.css" rel="stylesheet">

  <?php echo isset($Config->ya) ? $Config->ya : ''; ?>
  

 
</head>

<?php echo var_dump($newsConfig->indexCat); ?>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <?php if ($page->id == 'index'):?>

        <span class="logo d-flex align-items-center">
          <img src="<?php echo $Config->logo;?>" alt="<?php $page->get_header();?>">
          <div class="logo-text d-flex flex-column">
             <span class="sitename"><?php $page->get_header();?></span>
              <?php $page->get_slogan();?>
          </div>
        </span>

      <?php else:?>

        <a href="/" class="logo d-flex align-items-center">
          <img src="<?php echo $Config->logo;?>" alt="<?php $page->get_header();?>">
          <div class="logo-text d-flex flex-column">
            <span class="sitename"><?php $page->get_header();?></span>
            <?php $page->get_slogan();?>
          </div>
        </a>

      <?php endif;?>

      <div class="d-flex align-items-center gap-3">

        <nav id="navmenu" class="navmenu">
          <ul>
            <?php $page->get_menu('li');?>
          </ul>
        </nav>

      </div>

      

        <div class="head-contact d-flex align-items-center gap-3">
          <a class="link-icon wa" href="https://wa.me/<?= $Contact->wa; ?>" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i></a>
          <a class="link-icon tg" href="https://t.me/<?= $Contact->wa; ?>" target="_blank" rel="noopener noreferrer"><i class="bi bi-telegram"></i></a>
          <strong class="me-2"><?= $Contact->tel1; ?></strong>
        </div>

     

      
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </div>
  </header>

  <main class="main">
    <?php if ($page->id == 'index'):?>

      <!-- hero -->
      <section id="hero">
        <div class="container">
          <div class="wrap">

            <div class="content">
              <h1><?php $page->get_h1();?></h1>
              <p id="h1-sub" class="editable"><?=$Customize->iss('h1-sub')?$Customize->get('h1-sub'):'Cервисное и техническое обслуживание кондиционеров в Якутске';?></p>
              <a class="btn btn-light" href="<?= $Contact->mail; ?>">Получить консультацию</a>
              <a class="btn btn-light" href="<?= $Contact->mail; ?>">Каталог</a>
            </div>

            <div class="img">
              <img src="<?php $page->get_baner();?>" alt="<?php $page->get_h1();?>">
            </div>

          </div>
        </div>
      </section>

      <?php $Page->get_column('left'); ?>

      <?php $Page->get_column('right'); ?>

      <!-- featured-services-index -->
      <section id="featured-services-index">
        <div class="container">
          
              <div class="item">
                <div id="featured-img" class="editable"><?=$Customize->get('featured-img');?></div>
                <h4 id="featured-title" class="editable"><?=$Customize->iss('featured-title')?$Customize->get('featured-title'):'Преимущество 1';?></h4>
                <p id="featured-sub" class="editable"><?=$Customize->iss('featured-sub')?$Customize->get('featured-sub'):'Мы любим животных и стараемся поддерживать тех из них, кому не посчастливилось иметь ласковых хозяев и тёплый кров.';?></p>
              </div>
           
              <div class="item">
              <div id="featured-img4" class="editable"><?=$Customize->get('featured-img4');?></div>
                <h4 id="featured-title2" class="editable"><?=$Customize->iss('featured-title2')?$Customize->get('featured-title2'):'Преимущество 2';?></h4>
                <p id="featured-sub2" class="editable"><?=$Customize->iss('featured-sub2')?$Customize->get('featured-sub2'):'Мы любим животных и стараемся поддерживать тех из них, кому не посчастливилось иметь ласковых хозяев и тёплый кров.';?></p>
              </div>
           
              <div class="item">
              <div id="featured3-img3" class="editable"><?=$Customize->get('featured3-img3');?></div>
                <h4 id="featured-title3" class="editable"><?=$Customize->iss('featured-title3')?$Customize->get('featured-title3'):'Преимущество 3';?></h4>
                <p id="featured-sub3" class="editable"><?=$Customize->iss('featured-sub3')?$Customize->get('featured-sub3'):'Мы любим животных и стараемся поддерживать тех из них, кому не посчастливилось иметь ласковых хозяев и тёплый кров.';?></p>
              </div>
            
              <div class="item">
              <div id="featured4-img4" class="editable"><?=$Customize->get('featured4-img4');?></div>
                <h4 id="featured-title4" class="editable"><?=$Customize->iss('featured-title4')?$Customize->get('featured-title4'):'Преимущество 3';?></h4>
                <p id="featured-sub4" class="editable"><?=$Customize->iss('featured-sub4')?$Customize->get('featured-sub4'):'Мы любим животных и стараемся поддерживать тех из них, кому не посчастливилось иметь ласковых хозяев и тёплый кров.';?></p>
              </div>
            
        </div>
      </section>
    
   

     <!-- Services Section -->
     <section id="services">
        <div class="container">

          <h2 class="text-center"><span>Наши услуги</span></h2>

          <div class="wrap">
            <?php echo UslugiCategory(false, 6,'
            <a class="service-item" href="#uri#" class="stretched-link">
                    <h3>#header#</h3>
                    <p class="prev">#prev#</p>
                    <strong class="price">от #price# ₽</strong>
                  </a>
            ');?>
          </div>

        </div>
    </section>

    <!-- Services Section -->
     <section id="services" class="bg-white">
        <div class="container">

          <h2 class="text-center"><span>Часто задаваемые вопросы</span></h2>

            <?php echo QuestionsCategory(false, 100);?>

        </div>
    </section>

    
      
    <?php else:?>

      <div class="page-title">
            <div class="container d-lg-flex flex-column align-items-center">
              <h1 class="mb-2"><?php echo $page->get_name(); ?></h1>
              <nav class="breadcrumbs">
                  <?php require('data/breadcrumbs.dat');?>
              </nav>
            </div>
          </div>

      <div class="container">
        <div class="row">

          <?php $page->get_content();?>

          </div>
        </div> 
        <?php endif; ?>

       

      <section id="contact" data-aos="fade-up" data-aos-delay="100">
        <div class="container">
          <h2 class="text-center"><span>Контакты</span></h2>
          <div class="wrap">

            <div class="contact-info">
              <div class="item"><strong>Адрес:</strong><span><?php echo $Contact->city;?>, <?php echo $Contact->address;?></span></div>
              <div class="item">
                <strong>Телефон:</strong>
                <span><?php echo $Contact->tel1;?></span>
                <span><?php echo $Contact->tel2;?></span>
                <span><?php echo $Config->tel3;?></span>
              </div>
              <div class="item"><strong>Email:</strong><span><?php echo $Contact->mail;?></span></div>
              <div class="item">
                <strong>Режим работы:</strong>

                <?php if($Contact->hour1) { ?><span>Пн-Пт: <?php echo $Contact->hour1;?></span><?php } ?>
                
                
                <?php if(!$Contact->hour2 && !$Contact->hour3 ): ?>
                  <span>Сб-Вс: Выходной</span>
                  <?php else: ?>
                    <?php if($Contact->hour2) { ?><span>Сб: <?php echo $Contact->hour2;?></span><?php } ?>
                    <?php if($Contact->hour3) { ?><span>Вс: <?php echo $Contact->hour3;?></span><?php } ?>
                <?php endif; ?>
              </div>

            </div>

            <div class="map"><?php echo $Contact->location;?></div>

          </div>
        </div>
      </section>

  </main>

  <footer id="footer" class="footer">

    <div class="footer-newsletter">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-lg-6">
            <h4>Join Our Newsletter</h4>
            <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
            <form action="forms/newsletter.php" method="post" class="php-email-form">
              <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your subscription request has been sent. Thank you!</div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="d-flex align-items-center">
            <span class="sitename">eNno</span>
          </a>
          <div class="footer-contact pt-3">
            <p>A108 Adam Street</p>
            <p>New York, NY 535022</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
            <p><strong>Email:</strong> <span>info@example.com</span></p>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12">
          <h4>Follow Us</h4>
          <p>Cras fermentum odio eu feugiat lide par naso tierra videa magna derita valies</p>
          <div class="social-links d-flex">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">eNno</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>



  <!-- Vendor JS Files -->
  <script src="/modules/1theme/assets/js/bootstrap.bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="/modules/1theme/assets/js/main.js"></script>
  <?php if(function_exists('CustomizeInit')) CustomizeInit(); ?>
  <?php $page->get_endhtml();?>


    <!-- Clean zero space -->

    <script>
    window.addEventListener('load', function() {
    var textNodes = document.evaluate(
    "//text()",
    document,
    null,
    XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE,
    null
    );

    for (var i = 0; i < textNodes.snapshotLength; i++) {
    var node = textNodes.snapshotItem(i);
    var text = node.nodeValue;
    var cleanedText = text.replace(/\u200B/g, '');
    node.nodeValue = cleanedText;
    }
    });
    </script>



</body>

</html>