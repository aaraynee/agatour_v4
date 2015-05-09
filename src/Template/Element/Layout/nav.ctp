<style>
    nav.uk-navbar {
        background: #ffffff; }

    nav.uk-navbar ul.uk-navbar-nav li a {
        font-family:'Roboto Condensed',sans-serif;
        font-weight: 700;
        font-size: 18px;
        text-transform: uppercase;
        color: #104f91; }

    nav.uk-navbar ul.uk-navbar-nav li a:hover,
    nav.uk-navbar ul.uk-navbar-nav li.uk-active a:hover,
    nav.uk-navbar ul.uk-navbar-nav li.uk-active a {
        background: #d8d8d8; }

</style>


<nav class="uk-navbar">
    <ul class="uk-navbar-nav uk-hidden-small">

        <li class=" " ><a href="/">home</a>
        </li>
        <li class="uk-parent " data-uk-dropdown><a href="/schedule">tournaments</a>
            <div class="uk-dropdown uk-dropdown-navbar">
                <ul class="uk-nav uk-nav-navbar">
                    <li><a href="/schedule/cup">cup</a></li>
                    <li><a href="/schedule/exhibition">exhibition</a></li>
                    <li><a href="/schedule/practice">practice</a></li>
                </ul>
            </div>
        </li>
        <li class=" " ><a href="/nandoscup">nandoscup</a>
        </li>
        <li class=" " ><a href="/rankings">rankings</a>
        </li>
        <li class=" " ><a href="/players">players</a>
        </li>
        <li class=" " ><a href="/blog">blog</a>
        </li>
        <li class=" " ><a href="/awards">awards</a>
        </li>
        <li class="uk-parent " data-uk-dropdown><a href="/about">about</a>
            <div class="uk-dropdown uk-dropdown-navbar">
                <ul class="uk-nav uk-nav-navbar">
                    <li><a href="/courses">courses</a></li>
                </ul>
            </div>
        </li>

    </ul>
    <a class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas="{target:'#my-id'}"></a>
</nav>

<div id="my-id" class="uk-offcanvas">
    <div class="uk-offcanvas-bar">
        <ul class="uk-nav uk-nav-offcanvas" data-uk-nav>
            <li class=" " ><a href="/">home</a>
            <li class="uk-parent " data-uk-dropdown><a href="/schedule">tournaments</a>
                <div class="uk-dropdown uk-dropdown-navbar">
                    <ul class="uk-nav uk-nav-navbar">
                        <li><a href="/schedule/cup">cup</a></li>
                        <li><a href="/schedule/exhibition">exhibition</a></li>
                        <li><a href="/schedule/practice">practice</a></li>
                    </ul>
                </div>
            <li class=" " ><a href="/schedule/cup">cup</a>
            <li class=" " ><a href="/schedule/exhibition">exhibition</a>
            <li class=" " ><a href="/schedule/practice">practice</a>
            <li class=" " ><a href="/nandoscup">nandoscup</a>
            <li class=" " ><a href="/rankings">rankings</a>
            <li class=" " ><a href="/players">players</a>
            <li class=" " ><a href="/blog">blog</a>
            <li class=" " ><a href="/awards">awards</a>
            <li class="uk-parent " data-uk-dropdown><a href="/about">about</a>
                <div class="uk-dropdown uk-dropdown-navbar">
                    <ul class="uk-nav uk-nav-navbar">
                        <li><a href="/courses">courses</a></li>
                    </ul>
                </div>
            <li class=" " ><a href="/courses">courses</a>

        </ul>
    </div>
</div> 