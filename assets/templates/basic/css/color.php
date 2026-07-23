<?php
header("Content-Type:text/css");
$color = "#f0f";
$secondColor = "#ff8";

function checkhexcolor($color)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) and $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color or !checkhexcolor($color)) {
    $color = "#336699";
}


function checkhexcolor2($secondColor)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $secondColor);
}

if (isset($_GET['secondColor']) and $_GET['secondColor'] != '') {
    $secondColor = "#" . $_GET['secondColor'];
}

if (!$secondColor or !checkhexcolor2($secondColor)) {
    $secondColor = "#14233c";
}
?>

.preloader .icon {
text-shadow: 0 0 10px <?php echo $color ?>e6;
}
.custom--accordion-two .accordion-button:not(.collapsed), .preloader .icon, .about-item__icon i, .overview-item__number, .inlne-menu li a:hover, .package-sidebar-list li i, .contact-item i, .contact-item p a:hover {
color: <?php echo $color ?>;
}
body::-webkit-scrollbar-thumb, .preloader .rounded-circle::after, .find-tabs .nav-item .nav-link.active, .location-slider .slick-arrow:hover, .trip-card__price, .trip-slider .slick-dots li.slick-active button, .subscribe-form button, .tour-plan-block .title span, .package-sidebar-widget .thumb .price, .single-review .progress .progress-bar {
background-color: <?php echo $color ?>;
}

.header .main-menu li a:hover, .header .main-menu li a:focus {
color: <?php echo $color ?>;
}

.btn--base {
background-color: <?php echo $color ?>;
}
.btn--base:hover,
.btn--base:active {
    background-color: <?php echo $color ?>c1 !important;
    border-color: <?php echo $color ?>c1 !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
background-color: <?php echo $color ?>;
}

.select2-container--default .select2-results__option[aria-selected=true]{
    background-color: <?php echo $color ?> !important;
    color: #fff !important;
}


.select2-container--open .select2-selection.select2-selection--single, .select2-container--open .select2-selection.select2-selection--multiple .form--control:focus, .select2-container--default .select2-search--dropdown .select2-search__field:focus, .select2-container--default .select2-selection--single,
.form--control:focus,
.search-area .select2-container--default.select2-container--open.select2-container--below .select2-selection--single {
border-color: <?php echo $color ?> !important;
}

a:hover {
color: <?php echo $color ?>;
}

.trip-card__price {
box-shadow: 0 5px 15px 2px <?php echo $color ?>66;
}

.post-card__meta li a:hover {
color: <?php echo $color ?>;
}

.text--btn {
color: <?php echo $color ?>;
}

.footer__bottom::before {
background-color: <?php echo $secondColor ?>;
}

.form--control:focus {
box-shadow: 0 0 5px <?php echo $color ?>59;
}

.custom--checkbox input:checked ~ label::before {
color: <?php echo $color ?>;
}

.page-breadcrumb li:first-child::before {
color: <?php echo $color ?>;
}

.custom--nav-tabs .nav-item .nav-link.active {
background-color: <?php echo $color ?>;
}

.text--base, .main-menu li a.active {
color: <?php echo $color ?> !important;
}

.form--control {
border: 1px solid <?php echo $color ?>;
}

.header .main-menu li .sub-menu li a:hover {
color: <?php echo $color ?>;
}

.header .main-menu li.menu_has_children:hover > a::before , .nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover {
color: <?php echo $color ?>;
}

.profile-thumb .avatar-edit label , .payment-card-title{
background-color: <?php echo $color ?>;
}

.page-breadcrumb li a:hover {
color: <?php echo $color ?>;
}

.custom--file-upload ~ label, .payment-system-list.is-scrollable::-webkit-scrollbar-thumb {
background-color: <?php echo $color ?>;
}

.pagination .page-item.active .page-link {
background-color: <?php echo $color ?>;
}
.pagination .page-item .page-link {
border: 1px solid <?php echo $color ?>40;
}
.pagination .page-item .page-link:hover {
background-color: <?php echo $color ?>;
border-color: <?php echo $color ?>;
}
.header .main-menu li .sub-menu li a:hover, .social-icons li a:hover {
background-color: <?php echo $color ?>0d;
}
.form-check-input:checked, .action-widget__title.no-icon::after {
background: <?php echo $color ?>;
border-color: <?php echo $color ?>;
}
.cmn-list li::before, .d-widget__icon i {
color: <?php echo $color ?>;
}
.phone-number li a i {
color: <?php echo $color ?>;
}

element.style {
}

.payment-system-list {
--hover-border-color: <?php echo $color ?>;
}

.payment-item:has(.payment-item__radio:checked) .payment-item__check {
border: 3px solid <?php echo $color ?>;
}
.payment-item__check {
border: 1px solid <?php echo $color ?>;

}
.bg--base , .dropdown-list>.dropdown-list__item:hover{
background-color: <?php echo $color ?> !important;
}

.account-section .left{
background-color: <?php echo $secondColor ?>c9;
}
body.page-trns-active::before, .section--bg2, .dark--overlay::before, .dark--overlay-two::before, .hero::before, .select2-dropdown ::-webkit-scrollbar-thumb, .header.menu-fixed .header__bottom, .header .main-menu li .sub-menu, .inner-hero::before, .package-sidebar-widget, .account-section .left{
background-color: <?php echo $secondColor ?>;
}
.social-icons li a {
background: <?php echo $secondColor ?>;
}

.social-icons li a:hover {
background: <?php echo $secondColor ?>c1;
}

.contact-item .cont a:hover {
  color: <?php echo $color ?>!important;
}