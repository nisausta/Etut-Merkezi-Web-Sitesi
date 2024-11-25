<?php include ("baglanti.php"); ?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pırıltı Bire Bir Etüt Merkezi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .carousel-item img {
            max-height: 400px;
            width: auto;
            margin: 0 auto;
        }
    .carousel-caption {
        font-size: 28px; /* Yazı boyutunu ayarla */
        text-align: center; /* Metni ortala */
        position: absolute;
        top: 50%; /* Yüksekliği yukarıdan aşağıya ortalama */
        left: 50%; /* Genişliği soldan sağa ortalama */
        transform: translate(-50%, -50%); /* Metni yatay ve dikey olarak ortala */
        color: white; /* Yazı rengini beyaz yap */
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 1); /* Hafif bir gölge ekle */
    }
</style>

</head>


<?php
include ("baglanti.php");
session_start();

// Yetkili Giriş İşlemleri
if (isset($_POST['yetkiliID'], $_POST['yetkiliSifre'])) {
    $yetkiliID = $_POST['yetkiliID'];
    $yetkiliSifre = $_POST['yetkiliSifre'];

    $stmt = $baglanti->prepare("SELECT * FROM yetkili WHERE yetkiliID = ? AND yetkiliSifre = ?");
    $stmt->bind_param("ss", $yetkiliID, $yetkiliSifre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['loggedin'] = true;
        $_SESSION['yetkiliID'] = $yetkiliID;
        header("Location: yetkiliAnasayfa.php");
        exit;
    } else {
        $error_message = 'Geçersiz yetkili ID veya şifre.';
    }
}
// Öğrenci Giriş İşlemleri
if (isset($_POST['ogrenciNumara'], $_POST['ogrenciSifre'])) {
    $ogrenciNumara = $_POST['ogrenciNumara'];
    $ogrenciSifre = $_POST['ogrenciSifre'];

    $stmt = $baglanti->prepare("SELECT * FROM ogrenciler WHERE ogrenciNumara = ? AND ogrenciSifre = ?");
    $stmt->bind_param("ss", $ogrenciNumara, $ogrenciSifre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['ogrenciID'] = $row['ogrenciID'];
        $_SESSION['ogrenciNumara'] = $ogrenciNumara;
        header("Location: ogrenciAnasayfa.php");
        exit;
    } else {
        $error_message = 'Geçersiz öğrenci numarası veya şifre.';
    }
}
// Öğretmen Giriş İşlemleri
if (isset($_POST['ogretmenNumara'], $_POST['ogretmenSifre'])) {
    $ogretmenNumara = $_POST['ogretmenNumara'];
    $ogretmenSifre = $_POST['ogretmenSifre'];

    $stmt = $baglanti->prepare("SELECT * FROM ogretmenler WHERE ogretmenNumara = ? AND ogretmenSifre = ?");
    $stmt->bind_param("ss", $ogretmenNumara, $ogretmenSifre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['ogretmenID'] = $row['ogretmenID'];
        $_SESSION['ogretmenNumara'] = $ogretmenNumara;
        header("Location: ogretmenAnasayfa.php");
        exit;
    } else {
        $error_message = 'Geçersiz öğretmen numarası veya şifre.';
    }
}
//Bize Ulaşın formu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form gönderildiğinde bu blok çalışacak
    if (isset($_POST['ulasanEmail']) && isset($_POST['ulasanMesaj'])) {
        $email = mysqli_real_escape_string($baglanti, $_POST['ulasanEmail']);
        $mesaj = mysqli_real_escape_string($baglanti, $_POST['ulasanMesaj']);

        // Veritabanına ekleme sorgusu
        $ekleme_sorgusu = "INSERT INTO bize_ulaşın (ulasanEmail, ulasanMesaj) VALUES ('$email', '$mesaj')";

        // Sorguyu çalıştır
        if (mysqli_query($baglanti, $ekleme_sorgusu)) {
            // Başarılı ekleme durumunda JavaScript kodunu kullanarak bir alert göster
            echo '<script>alert("Mesajınız başarıyla gönderildi.");</script>';
        } else {
            // Hata durumunda JavaScript kodunu kullanarak bir alert göster
            echo '<script>alert("Hata: ' . mysqli_error($baglanti) . '");</script>';
        }
    } else {
        // Eksik veya hatalı veri girişi durumunda JavaScript kodunu kullanarak bir alert göster
        echo '<script>alert("Eksik veya hatalı veri girişi.");</script>';
    }
}

?>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php"
                style="font-family: 'Arial Black', sans-serif; font-size: 24px; font-weight: bold;">
                <img src="image/pirilti.png" alt="Pırıltı Logo" style="height: 150px;">
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hakkimizda.php">Hakkımızda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hizmetler.php">Hizmetler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="openiletisimModal()">İletişim</a>
                    </li>
                    <!-- Giriş Kısmı -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Giriş Yap
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#" onclick="openOgrenciGirisModal()">Öğrenci Giriş</a>
                            </li>
                            <li><a class="dropdown-item" href="#" onclick="openOgretmenGirisModal()">Öğretmen Giriş</a>
                            </li>
                            <li><a class="dropdown-item" href="#" onclick="openYetkiliGirisModal()">Yetkili Giriş</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<!-- Slider -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php
        $sql = "SELECT * FROM anasayfa_foto";
        $result = mysqli_query($baglanti, $sql);
        $indicator_count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $active_class = ($indicator_count == 0) ? 'active' : '';
            ?>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $indicator_count ?>" class="<?= $active_class ?>" aria-current="<?= $active_class ?>"></button>
            <?php
            $indicator_count++;
        }
        ?>
    </div>
    <div class="carousel-inner">
        <?php
        $item_count = 0; // item_count değişkenini tanımladık
        mysqli_data_seek($result, 0); // Sonraki döngü için imleci başa alıyoruz
        while ($row = mysqli_fetch_assoc($result)) {
            $active_class = ($item_count == 0) ? 'active' : '';
            ?>
            <div class="carousel-item <?= $active_class ?>">
                <img src="data:image/jpeg;base64,<?= base64_encode($row["fotolar"]) ?>" class="d-block w-100" >
                <div class="carousel-caption d-none d-md-block">
                    <h4><?= $row['altyazı'] ?></h4>
                </div>
            </div>
            <?php
            $item_count++;
        }
        ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Önceki</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Sonraki</span>
    </button>
</div>


<?php

// Veritabanından verileri çekme sorgusu
$sorgu = "SELECT * FROM anasayfa_card"; // ananasayfa_Card tablosundaki tüm verileri çekiyoruz

// Sorguyu çalıştırma
$sonuc = mysqli_query($baglanti, $sorgu);

// Verileri kullanarak HTML çıktısını oluşturma
echo '<div class="temel-card" style=" display:flex; flex-direction:row; margin:50px 150px; ">';
while ($satir = mysqli_fetch_assoc($sonuc)) {
    // Her bir kart için içeriği dolduruyoruz
    echo '<div class="card" style="width: 18rem; margin:50px; ">';
    echo '<img src="data:image/jpeg;base64,' . base64_encode($satir['sinif_foto']) . '" class="card-img-top" alt="...">'; // Sinif fotoğrafını belirtiyoruz
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $satir['sinif_baslik'] . '</h5>'; // Sinif basligini çekiyoruz
    echo '<p class="card-text">' . $satir['sinif_aciklama'] . '</p>'; // Sinif aciklamasini çekiyoruz
    echo '</div>';
    echo '</div>';

    // Kadromuz karti
    echo '<div class="card" style="width: 18rem; margin:50px; ">';
    echo '<img src="data:image/jpeg;base64,' . base64_encode($satir['kadromuz_foto']) . '" class="card-img-top" alt="...">'; // Kadromuz fotoğrafını belirtiyoruz
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $satir['kadromuz_baslik'] . '</h5>'; // Kadromuz basligini çekiyoruz
    echo '<p class="card-text">' . $satir['kadromuz_aciklama'] . '</p>'; // Kadromuz aciklamasini çekiyoruz
    echo '</div>';
    echo '</div>';

    // Dersler karti
    echo '<div class="card" style="width: 18rem; margin:50px; ">';
    echo '<img src="data:image/jpeg;base64,' . base64_encode($satir['ders_foto']) . '" class="card-img-top" alt="...">'; // Ders fotoğrafını belirtiyoruz
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $satir['ders_baslik'] . '</h5>'; // Ders basligini çekiyoruz
    echo '<p class="card-text">' . $satir['ders_aciklama'] . '</p>'; // Ders aciklamasini çekiyoruz
    echo '</div>';
    echo '</div>';
}
echo '</div>';

?>


    <!-- Footer -->
    <footer class="text-center text-lg-start bg-light text-muted">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Pırıltı Bire Bir Etüt Merkezi</h5>
                    <p>
                        Her Öğrenciye Parıldayan Bir Gelecek!
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Hizmetler</h5>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="hizmetler.php" class="text-muted">9.Sınıf</a>
                        </li>
                        <li>
                            <a href="hizmetler.php" class="text-muted">10.Sınıf</a>
                        </li>
                        <li>
                            <a href="hizmetler.php" class="text-muted">11.Sınıf</a>
                        </li>
                        <li>
                            <a href="hizmetler.php" class="text-muted">12.Sınıf</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0 " style="list-style-type:none">
                    <h5 class="text-uppercase">İletişim</h5>
                    <?php
                    // Veritabanından iletişim bilgilerini çekme
                    $query = "SELECT yetkiliNumara, yetkiliEmail FROM yetkili";
                    $result = mysqli_query($baglanti, $query);

                    // Verileri alırken hata kontrolü yapın
                    if ($result) {
                        // Veri varsa, HTML içine yerleştirme
                        $row = mysqli_fetch_assoc($result);
                        $telefon = $row['yetkiliNumara'];
                        $email = $row['yetkiliEmail'];
                        ?>
                        <!-- İletişim Bilgilerini Gösterme -->
                        <li>
                            <i class="fas fa-envelope me-2"></i> <?php echo $email; ?>
                        </li>
                        <li>
                            <i class="fas fa-phone me-2"></i> <?php echo $telefon; ?>
                        </li>
                        <?php
                    } else {
                        // Veri yoksa veya hata oluştuysa bir hata mesajı gösterme
                        echo "İletişim bilgileri alınamadı.";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2024 Pırıltı Bire Bir Etüt Merkezi Tüm Hakları Saklıdır.
        </div>
    </footer>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>


    <!-- Yetkili Giriş Modalını Açmak İçin JavaScript -->
    <script>
        // Yetkili Giriş Modalını Açmak İçin Fonksiyon
        function openYetkiliGirisModal() {
            $('#yetkiliGirisModal').modal('show'); // Modalı aç
        }
    </script>
    <!-- Yetkili Giriş Modalı -->
    <div class="modal fade" id="yetkiliGirisModal" tabindex="-1" aria-labelledby="yetkiliGirisModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="yetkiliGirisModalLabel">Yetkili Giriş</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="image/girislogo.png" alt="Giriş Logo" style="max-width: 100%; height: auto;">
                        </div>
                        <div class="col-md-6">
                            <!-- Yetkili giriş formu -->
                            <form action="index.php" method="POST">
                                 <div class="mb-3">
                                    <label for="yetkiliID" class="form-label">Kullanıcı Numarası</label>
                                    <input type="text" class="form-control" id="yetkiliID" name="yetkiliID" required>
                                 </div>
                                <div class="mb-3">
                                    <label for="yetkiliSifre" class="form-label">Şifre</label>
                                    <input type="password" class="form-control" id="yetkiliSifre" name="yetkiliSifre" required>
                                </div>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Giriş Yap</button>
                                </form>
                                <div class="mt-3">
                                    <a href="index.php" class="btn btn-danger btn-sm btn-block" style="box-shadow: 0px 0px 5px 0px rgba(255, 0, 0, 0.75);">İptal Et</a>
                                    <a href="#" onclick="openYetkiliSifremiUnuttumModal()" class="btn btn-link btn-sm btn-block" style="box-shadow: 0px 0px 5px 0px rgba(0, 0, 255, 0.75);">Şifremi Unuttum</a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Öğrenci Giriş Modalını Açmak İçin JavaScript -->
    <script>
        function openOgrenciGirisModal() {
            $('#ogrenciGirisModal').modal('show');
        }
    </script>
    <!-- Öğrenci Giriş Modalı -->
    <div class="modal fade" id="ogrenciGirisModal" tabindex="-1" aria-labelledby="ogrenciGirisModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ogrenciGirisModalLabel">Öğrenci Giriş</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="image/girislogo.png" alt="Giriş Logo" style="max-width: 100%; height: auto;">
                        </div>
                        <div class="col-md-6">
                            <!-- Öğrenci giriş formu -->
                            <form action="index.php" method="POST">
                                <div class="mb-3">
                                    <label for="ogrenciNumara" class="form-label">Kullanıcı Numarası</label>
                                    <input type="text" class="form-control" id="ogrenciNumara" name="ogrenciNumara"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="ogrenciSifre" class="form-label">Şifre</label>
                                    <input type="password" class="form-control" id="ogrenciSifre" name="ogrenciSifre"
                                        required>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg btn-block">Giriş Yap</button>
                            </form>
                            <div class="mt-3">
                                    <a href="index.php" class="btn btn-danger btn-sm btn-block"style="box-shadow: 0px 0px 5px 0px rgba(255, 0, 0, 0.75);">İptal Et</a>
                                    <a href="#" onclick="openOgrenciSifremiUnuttumModal()" class="btn btn-link btn-sm btn-block" style="box-shadow: 0px 0px 5px 0px rgba(0, 0, 255, 0.75);">Şifremi Unuttum</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Öğretmen Giriş Modalını Açmak İçin JavaScript -->
    <script>
        function openOgretmenGirisModal() {
            $('#ogretmenGirisModal').modal('show');
        }
    </script>
 <!-- Öğrenci modal formu -->
<div class="modal fade" id="ogretmenGirisModal" tabindex="-1" aria-labelledby="ogretmenGirisModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ogretmenGirisModalLabel">Öğretmen Giriş</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="image/girislogo.png" alt="Giriş Logo" style="max-width: 100%; height: auto;">
                        </div>
                        <div class="col-md-6">
                            <!-- Öğretmen giriş formu -->
                            <form action="index.php" method="POST">
                                <div class="mb-3">
                                    <label for="ogretmenNumara" class="form-label">Kullanıcı Numarası</label>
                                    <input type="text" class="form-control" id="ogretmenNumara" name="ogretmenNumara"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="ogretmenSifre" class="form-label">Şifre</label>
                                    <input type="password" class="form-control" id="ogretmenSifre" name="ogretmenSifre"
                                        required>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg btn-block">Giriş Yap</button>
                            </form>
                            <div class="mt-3">
    <a href="index.php" class="btn btn-danger btn-sm btn-block" style="box-shadow: 0px 0px 5px 0px rgba(255, 0, 0, 0.75);">İptal Et</a>
    <a href="#" onclick="openOgretmenSifremiUnuttumModal()" class="btn btn-link btn-sm btn-block" style="box-shadow: 0px 0px 5px 0px rgba(0, 0, 255, 0.75);">Şifremi Unuttum</a>
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- İletişim Modalını Açmak İçin JavaScript -->
    <script>
        function openiletisimModal() {
            $('#iletisimModal').modal('show');
        }
    </script>

    <div class="modal fade" id="iletisimModal" tabindex="-1" aria-labelledby="iletisimModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="iletisimModalLabel">Bize Ulaşın</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Bize Ulaşın formu -->
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="ulasanEmail" class="form-label">E-posta Adresiniz</label>
                            <input type="email" class="form-control" id="ulasanEmail" name="ulasanEmail"
                                placeholder="E-posta adresinizi girin">
                        </div>
                        <div class="mb-3">
                            <label for="ulasanMesaj" class="form-label">Mesajınız</label>
                            <textarea class="form-control" id="ulasanMesaj" name="ulasanMesaj" rows="4"
                                placeholder="Mesajınızı buraya yazın"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit">Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Form gönderildikten sonra, bir alert göster
    window.onload = function() {
        var formSubmitted = localStorage.getItem('formSubmitted');
        if(formSubmitted) {
            alert(formSubmitted);
            localStorage.removeItem('formSubmitted'); // Alert gösterildikten sonra localStorage'ı temizle
        }
    }
</script>


<!-- Öğretmen Şifremi Unuttum Modalı -->
<div class="modal fade" id="ogretmenSifremiUnuttumModal" tabindex="-1" aria-labelledby="ogretmenSifremiUnuttumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa;">
                <h5 class="modal-title" id="ogretmenSifremiUnuttumModalLabel" style="font-size: 1.5rem;">Şifremi Unuttum Formu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
                
                        <?php
                        if(isset($_GET['error']) && $_GET['error'] == 'not_found') {
                            echo '<p class="text-danger text-center">Bu e-posta adresi veritabanında bulunamadı.</p>';
                        }
                        ?>
                        <form action="ogretmenmailgonder.php" method="POST">
                            <div class="mb-3">
                                <input type="email" name="eposta" class="form-control" placeholder="Eposta giriniz" style="border-radius: 5px; border: 1px solid #ced4da; box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.1);" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 1rem; border-radius: 5px; transition: all 0.3s ease;">GÖNDER</button>
                            </div>
                        </form>
                   
            </div>
        </div>
    </div>
</div>

<!-- Öğretmen Şifremi Unuttum Modalını Açmak İçin JavaScript -->
<script>
    function openOgretmenSifremiUnuttumModal() {
        $('#ogretmenSifremiUnuttumModal').modal('show');
    }
</script>
<!-- Yetkili Şifremi Unuttum Modalı -->
<div class="modal fade" id="yetkiliSifremiUnuttumModal" tabindex="-1" aria-labelledby="yetkiliSifremiUnuttumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa;">
                <h5 class="modal-title" id="yetkiliSifremiUnuttumModalLabel" style="font-size: 1.5rem;">Şifremi Unuttum Formu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
               
                        <?php
                        if(isset($_GET['error']) && $_GET['error'] == 'not_found') {
                            echo '<p class="text-danger text-center">Bu e-posta adresi veritabanında bulunamadı.</p>';
                        }
                        ?>
                        <form action="yetkilimailgonder.php" method="POST">
                            <div class="mb-3">
                                <input type="email" name="eposta" class="form-control" placeholder="Eposta giriniz" style="border-radius: 5px; border: 1px solid #ced4da; box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.1);" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 1rem; border-radius: 5px; transition: all 0.3s ease;">GÖNDER</button>
                            </div>
                        </form>
                   
            </div>
        </div>
    </div>
</div>

<!-- Yetkili Şifremi Unuttum Modalını Açmak İçin JavaScript -->
<script>
    function openYetkiliSifremiUnuttumModal() {
        $('#yetkiliSifremiUnuttumModal').modal('show');
    }
</script>

<!-- Öğrenci Şifremi Unuttum Modalı -->
<div class="modal fade" id="ogrenciSifremiUnuttumModal" tabindex="-1" aria-labelledby="ogrenciSifremiUnuttumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa;">
                <h5 class="modal-title" id="ogrenciSifremiUnuttumModalLabel" style="font-size: 1.5rem;">Şifremi Unuttum Formu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
            <?php
                        if(isset($_GET['error']) && $_GET['error'] == 'not_found') {
                            echo '<p class="text-danger text-center">Bu e-posta adresi veritabanında bulunamadı.</p>';
                        }
                        ?>
                        <form action="ogrencimailgonder.php" method="POST">
                            <div class="mb-3">
                                <input type="email" name="eposta" class="form-control" placeholder="Eposta giriniz" style="border-radius: 5px; border: 1px solid #ced4da; box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.1);" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 1rem; border-radius: 5px; transition: all 0.3s ease;">GÖNDER</button>
                            </div>
                        </form>
            </div>
        </div>
    </div>
</div>

<!-- Öğrenci Şifremi Unuttum Modalını Açmak İçin JavaScript -->
<script>
    function openOgrenciSifremiUnuttumModal() {
        $('#ogrenciSifremiUnuttumModal').modal('show');
    }
</script>

    <?php
// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>

</body>

</html>