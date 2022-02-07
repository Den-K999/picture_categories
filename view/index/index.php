<!doctype html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta name="referrer" content="origin">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://dcsaascdn.net/js/dc-sdk-1.0.5.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/browser-image-compression@1.0.17/dist/browser-image-compression.js"></script>
    <script>
        (function() {
            'use strict';

            var styles;

            if (localStorage.getItem('styles')) {
                styles = JSON.parse(localStorage.getItem('styles'));
                injectStyles(styles);
            }

            window.shopAppInstance = new ShopApp(function(app) {
                app.init(null, function(params, app) {
                    if (localStorage.getItem('styles') === null) {
                        injectStyles(params.styles);
                    }
                    localStorage.setItem('styles', JSON.stringify(params.styles));

                    app.show(null, function() {
                        app.adjustIframeSize();
                    });
                }, function(errmsg, app) {
                    alert(errmsg);
                });
            }, true);

            function injectStyles(styles) {
                var i;
                var el;
                var sLength;

                sLength = styles.length;
                for (i = 0; i < sLength; ++i) {
                    el = document.createElement('link');
                    el.rel = 'stylesheet';
                    el.type = 'text/css';
                    el.href = styles[i];
                    document.getElementsByTagName('head')[0].appendChild(el);
                }
            }
        }());
    </script>
    <link rel="stylesheet" type="text/css" href="/picture_categories/assets/styles/backend_style.css?v=1.7">
</head>

<body>
    <main>
        <div class="top-wrapper">
            <img src="assets/images/sparkcode_logo.svg" alt="">
            <div class="wrapper">
                <span><img src="assets/images/download.svg">Instrukcja osługi</span>
                <span><img src="assets/images/help.svg">Pomoc</span>
            </div>
        </div>
        <aside>
            <nav>
                <ul class="menu-mobile">
                    <li class="config"><img src="assets/images/cog-solid.svg" alt=""></li>
                    <li class="images"><img src="assets/images/image-solid.svg" alt=""></li>
                </ul>
                <ul class="menu-desktop">
                    <li class="head">Menu</li>
                    <li class="config">Ustawienia główne</li>
                    <li class="images">Ustawienia obrazków</li>
                </ul>
            </nav>
        </aside>
        <section class="wrapper">
            <div class="placeholder">
                <img src="assets/images/sparkcode_logo.svg" alt="">
            </div>
            <div class="config">
                <h3 class="head">1. Ustawienia wyświetlania</h3>
                <div class="options">
                    <div class="activity">
                        <span class="label">Aktywność aplikacji</span>
                        <div class="values">
                            <div class="single-value">
                                <button class="activity-button" data-active="<?php echo App::escapeHtml($setting['activity']); ?>" onclick="toggleActivity(this)"></button>
                            </div>
                        </div>
                    </div>
                    <div class="quantity">
                        <span class="label">Ilosć obrazków w rzędzie</span>
                        <div class="values">
                            <div class="single-value">
                                <div class="icons"><img class="screen" src="assets/images/mobile-solid.svg"><img src="assets/images/hint.svg" class="hint"><span class="tooltip">Dla szerokości poniżej 766px</span></div>
                                <select name="mwidth" class="mwidth">
                                    <option value="1" <?php echo ($setting['mobile_width'] == '1') ?  ' selected="selected"' : "";  ?>>1</option>
                                    <option value="2" <?php echo ($setting['mobile_width'] == '2') ?  ' selected="selected"' : "";  ?>>2</option>
                                </select>
                            </div>
                            <div class="single-value">
                                <div class="icons"><img class="screen" src="assets/images/tablet-solid.svg"><img src="assets/images/hint.svg" class="hint"><span class="tooltip">Dla szerokości większej lub równej 766px ale mniejszej od 1024px</span></div>
                                <select name="twidth" class="twidth">
                                    <option value="1" <?php echo ($setting['tablet_width'] == '1') ?  ' selected="selected"' : "";  ?>>1</option>
                                    <option value="2" <?php echo ($setting['tablet_width'] == '2') ?  ' selected="selected"' : "";  ?>>2</option>
                                    <option value="2" <?php echo ($setting['tablet_width'] == '3') ?  ' selected="selected"' : "";  ?>>3</option>
                                </select>
                            </div>
                            <div class="single-value">
                                <div class="icons"><img class="screen" src="assets/images/desktop-solid.svg"><img src="assets/images/hint.svg" class="hint"><span class="tooltip">Dla szerokości większej lub równej 1024px</span></div>
                                <select name="dwidth" class="dwidth" value="<?php echo App::escapeHtml($setting['desktop_width']); ?>">
                                    <option value="1" <?php echo ($setting['desktop_width'] == '1') ?  ' selected="selected"' : "";  ?>>1</option>
                                    <option value="2" <?php echo ($setting['desktop_width'] == '2') ?  ' selected="selected"' : "";  ?>>2</option>
                                    <option value="3" <?php echo ($setting['desktop_width'] == '3') ?  ' selected="selected"' : "";  ?>>3</option>
                                    <option value="4" <?php echo ($setting['desktop_width'] == '4') ?  ' selected="selected"' : "";  ?>>4</option>
                                    <option value="5" <?php echo ($setting['desktop_width'] == '5') ?  ' selected="selected"' : "";  ?>>5</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="category-name-position">
                        <span class="label">Układ obrazków</span>
                        <div class="single-value">
                            <img class="screen" src="assets/images/text-bottom.svg">
                            <label><input class="pos-input" type="radio" value="bottom" <?php echo ($setting['position'] == 'bottom') ?  "checked" : "";  ?> name="position">Nazwa pod obrazkiem</label>
                        </div>
                        <div class="single-value">
                            <img class="screen" src="assets/images/text-right.svg">
                            <label><input class="pos-input" type="radio" value="right" <?php echo ($setting['position'] == 'right') ?  "checked" : "";  ?> name="position">Nazwa po prawej</label>
                        </div>
                    </div>
                    <button class="sbmt" type="submit" onclick="saveConfigInfo('<?php echo $this->params['shop'] ?>')">Zapisz</button>
                </div>
            </div>
            <div class="images">
                <span class="head">Obrazki kategorii</span>
                <ul class="images-list">
                    <?php
                    echo $category_tree;
                    ?>
                </ul>
                <div class="edition-form tab-page " data-tab-current="true" data-tab-page="index">
                    <div class="clearfix"></div>
                </div>
            </div>
        </section>
        <div class="settings-changed">
            Ustawienia zostały zmienione
        </div>
        <div class="settings-changed-error">
            Ups! Coś poszło nie tak
        </div>
        <div class="modal-container">
            <div class="modal">
                <button class='close' onclick='closeModal()'>X</button>
            </div>
        </div>
    </main>
    <style>
        .image-upload>input {
            display: none;
        }
    </style>
    <!-- <script src="assets/scripts/script_backend.js?v=1.96"></script> -->
    <script src="assets/scripts/script_backend.js?v=1.97"></script>
</body>

</html>