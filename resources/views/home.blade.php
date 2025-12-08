<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>PowerGO</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/landingpage_adit.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg bg-secondary fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#page-top">PowerGO</a>
            <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive"
                aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#home">Home</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#fitur">Fitur</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#anggota">Anggota</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="{{ url('login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead section-1 text-white text-center">
        <div class="container d-flex align-items-center flex-column">
            <img class="masthead-avatar mb-5 logo" src="{{ asset('images/icon_powergo.png') }}" alt="PowerGO" id="home" />
            <h1 class="masthead-heading mb-0">PowerGO</h1>
            <p class="teks" style="margin: 20px 5px;">Mulailah dari sini, siap menatap masa depan</p>
            <a href="{{ url('login') }}"><img src="{{ asset('images/icon_mulai.png') }}" class="next-btn" alt="PowerGO" /></a>
        </div>
    </header>
    <!-- Portfolio Section-->
    <div class="section-2 sections" id="fitur">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="profil">
                    <div class="pp-profil1 gambar-2">
                        <img src="{{ asset('images/icon_dashboard2.png') }}" alt="">
                    </div>
                    <div class="info-profil1">
                        <h3 class="judul-teks">Kemudahan dalam satu aplikasi.</h3>
                        <p class="teks">Dengan PowerGO, nikmati kemudahan pembayaran listrik yang langsung mengisi ke meteran Anda
                            dimanapun kapanpun dalam satu aplikasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-3 sections">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="profil">
                    <div class="info-profil2">
                        <h3 class="judul-teks">Makin terupdate. Dimanapun dan kapanpun.</h3>
                        <p class="teks">Kini, tidak perlu khawatir dengan status meteran Anda, satu aplikasi dapat menampilkan
                            informasi terkini kWh yang tersisa, penggunaan daya saat ini, dan banyak lagi!</p>
                    </div>
                    <div class="pp-profil2 gambar-3">
                        <img src="{{ asset('images/icon_landingpage3.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-4 sections">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="profil">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <img src="{{ asset('images/icon_dashboard4.png') }}" alt="">
                        </div>
                        <div class="col-sm-12 col-md-8 info-profil1">
                            <h3 class="judul-teks">Riwayat transaksi.</h3>
                            <p class="teks">Tidak perlu khawatir dengan transaksi sebelumnya, PowerGO menyimpan seluruh riwayat
                                transaksi Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section" id="anggota">
        <div class="section-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-sm-12 col-md-12">
                        <div class="section-heading testimonial-heading">
                            <h1>Anggota <br>kelompok</h1>
                            <p class="card-text teks-hitam">Berikut adalah orang-orang yang berjuang setengah mati dibalik seluruh
                                mahakarya ini.</p>
                        </div>
                    </div>
                    <div class="col-lg-9 col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="section-5">
                                    <div class="test-author-thumb d-flex">
                                        <img src="{{ asset('images/anggota_anra.png') }}" alt="Testimonial author" class="img-fluid pp-profil3">
                                        <div class="test-author-info">
                                            <h4>Anugerah Ramadhan Arinal</h4>
                                            <h6>Backend developer</h6>
                                        </div>
                                    </div>
                                    <p class="card-text teks-hitam">Sang Pengcarry tim yang handal. konon katanya sekali memegang
                                        keyboard tugas apaapun dapat di selesaikan dengan sekejap. ia juga yang mengerjakan bagian backend
                                        sendiran.</p>
                                    <i class="fa fa-quote-right"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="section-5">
                                    <div class="test-author-thumb d-flex">
                                        <img src="{{ asset('images/anggota_gwehj.png') }}" alt="Testimonial author" class="img-fluid pp-profil3">
                                        <div class="test-author-info">
                                            <h4>Satriya Adhi Pradana</h4>
                                            <h6>Pencetus ide & Frontend developer</h6>
                                        </div>
                                    </div>
                                    <p class="card-text teks-hitam">Sang pendesign dari tampilan projek. ia bertugas membuat design
                                        tampilan baik itu figma maupun memperbaiki design yang kurang tepat. ia termasuk yang mengacarry tim
                                        setelah bang kulbet. konon katanya ide dari projek ini berasal dari dia.</p>
                                    <i class="fa fa-quote-right"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="section-5">
                                    <div class="test-author-thumb d-flex">
                                        <img src="{{ asset('images/anggota_adit.png') }}" alt="Testimonial author" class="img-fluid pp-profil3">
                                        <div class="test-author-info">
                                            <h4>Aditya Juliawan Suryaputra</h4>
                                            <h6>Dokumenter & Frontend developer</h6>
                                        </div>
                                    </div>
                                    <p class="card-text teks-hitam">Sang pelooting handal. ia bertugas dalam pembuatan frontend walaupun
                                        ia mengambilnya dari template dan mengeditnya untuk menambahkan code dari native. Menurut info yang
                                        beredar anak ini merupakan anak yang baik dan suka menolong sesama.</p>
                                    <i class="fa fa-quote-right"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="section-5">
                                    <div class="test-author-thumb d-flex">
                                        <img src="{{ asset('images/anggota_khoer.png') }}" alt="Testimonial author" class="img-fluid pp-profil3">
                                        <div class="test-author-info">
                                            <h4>Khoer Fadillah Faturokhman</h4>
                                            <h6>Frontend developer</h6>
                                        </div>
                                    </div>
                                    <p class="card-text teks-hitam">sang ahli design yang bertugas dalam pembuatan figma dan membuat
                                        poster. ia bekerja diam-diam sehingga tidak ada yang mengetahui kapan dan dimana ia mengerjakan.</p>
                                    <i class="fa fa-quote-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <!-- SB Forms JS-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>
