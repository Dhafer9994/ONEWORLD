<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Formation gratuites pour les réfugiés">
    <meta name="author" content="One World Formations">
    <title>One World Formations - Pour les Réfugiés</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?= ASSETS_URL ?>/asset/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="<?= ASSETS_URL ?>/css/font-awesome.min.css" rel="stylesheet">
    <!-- Animate CSS -->
    <link href="<?= ASSETS_URL ?>/css/animate.css" rel="stylesheet">
    <!-- Owl-Carousel -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/owl.carousel.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/owl.theme.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/owl.transitions.css">
    <!-- Custom CSS -->
    <link href="<?= ASSETS_URL ?>/css/style.css" rel="stylesheet">
    <link href="<?= ASSETS_URL ?>/css/responsive.css" rel="stylesheet">
    <!-- Colors CSS -->
    <link rel="stylesheet" type="text/css" href="<?= ASSETS_URL ?>/css/color/green.css">
    <!-- Custom Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <!-- Modernizer js -->
    <script src="<?= ASSETS_URL ?>/js/modernizr.custom.js"></script>

    <style>
        .formation-card {
            background: #fff;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            transition: 0.3s;
            border-left: 5px solid #2ecc71;
        }

        .formation-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .badge-live {
            background-color: #ff3b30;
            color: white;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 10px;
            float: right;
        }

        .badge-classic {
            background-color: #6c757d;
            color: white;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 10px;
            float: right;
        }

        .modal-header-custom {
            background: #2ecc71;
            color: white;
        }
    </style>
</head>

<body class="index">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">One World</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a class="page-scroll" href="#page-top">Accueil</a></li>
                    <li><a class="page-scroll" href="#formations">Formations</a></li>
                    <li><a class="page-scroll" href="#contact">Contact</a></li>
                    <li><a href="index.php?route=admin" class="btn btn-primary navbar-btn"
                            style="margin-top: 10px; padding: 5px 15px;">ADMIN</a></li>
                </ul>
            </div>
        </div>
    </nav>