{extends file="base.tpl"}
{block name="styleoverride"}
<link href="{$cWebPath}/style/mainpage.css" rel="stylesheet">

<style type="text/css">
    body {
        padding-bottom: 40px;
    }

    .sidebar-nav {
        padding: 9px 0;
    }

    @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
            float: none;
            padding-left: 5px;
            padding-right: 5px;
        }
    }
</style>

{/block}

{block name="navbar"}
<div class="navbar-wrapper">
    <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
    <div class="container">

        <div class="navbar navbar-inverse">
            <div class="navbar-inner">
                <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                {include file="navigation.tpl"}
            </div><!-- /.navbar-inner -->
        </div><!-- /.navbar -->

    </div> <!-- /.container -->
</div><!-- /.navbar-wrapper -->
{/block}

{block name="precontent"}
<!-- Carousel
    ================================================== -->
<div id="imageCarousel" class="carousel slide">
    <div class="carousel-inner">
        <div class="item active">
            <img src="/usercontent/content.php/99ed74cd55792e42e18b84251ea1abc86a552802844b48d27c779df4b759085d" alt="">
        </div>
        <div class="item">
            <img src="/usercontent/content.php/9760efb94d5b9fc2601e26747c82369be421829d475f24474bf335434ec035c3" alt="">
        </div>
        <div class="item">
            <img src="/usercontent/content.php/c0d4d7dbcbec2c02e942a4109bf6a57086611969034d24db34974b8f10d6fe44" alt="">
        </div>
        <div class="item">
            <img src="/usercontent/content.php/a05d4c6225647bef4a6783ed463a9b49f2952f59a0a66d81606d7ae3d7d61b3e" alt="">
        </div>
    </div>
    <a class="left carousel-control" href="#imageCarousel" data-slide="prev">&lsaquo;</a>
    <a class="right carousel-control" href="#imageCarousel" data-slide="next">&rsaquo;</a>
</div><!-- /.carousel -->
{/block}

{block name="maincontent"}
{$cmsPageContent}
{/block}

{block name="scriptfooter"}
<script>
    !function ($) {
        $(function() {
            $('#imageCarousel').carousel();
        });
    }(window.jQuery)
</script>
{/block}