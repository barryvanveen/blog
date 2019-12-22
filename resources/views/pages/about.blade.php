@extends('layouts.base')

@section('body')
    <section>

        <h1>About</h1>

        <p>Hi! I'm Barry, a software developer from the Netherlands. For over 10 years I've been building software professionally. My specialisation is web development, mainly working with PHP, Javascript and the related tooling.</p>

        <p>This blog is my way of documenting lessons I've learned and want to share. If you want to get in touch, post a comment below one of the articles or email me at barryvanveen at gmail dot com.</p>

        <p>Besides programming I enjoy cooking (and eating), running, reading and music.</p>

        <h2>Projects</h2>

        <ul>
            <li>
                <a href="https://github.com/barryvanveen/php-cca">barryvanveen/php-cca</a><br>
                build cyclic cellular automaton and save them as (animated) images.
            </li>
            <li>
                <a href="https://github.com/barryvanveen/lastfm">barryvanveen/lastfm</a><br>
                a last.fm API client for PHP7 that comes with a Laravel service provider.
            </li>
        </ul>

        <h2>Personal stuff</h2>

        <ul>
            <li><a href="about/now">What I'm up to now</a></li>
            <li><a href="about/music">Music that I listen to</a></li>
            <li><a href="about/books">Books that I read</a></li>
        </ul>

        <h2>Contact me</h2>

        <ul>
            <li>Email</li>
            <li>LinkedIn</li>
            <li>GitHub</li>
        </ul>

    </section>
@endsection
